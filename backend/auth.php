<?php

// Start the session to handle user authentication
session_start();

// Include the database connection file
require_once 'db.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, return a JSON response indicating success
    echo json_encode(array('success' => true, 'message' => 'User is already logged in'));
    exit;
}

// Check if the user is attempting to register
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'register') {
    // Check if all required fields are present
    if (!isset($_POST['username'], $_POST['email'], $_POST['password'], $_POST['confirm_password'])) {
        echo json_encode(array('success' => false, 'message' => 'Missing required fields'));
        exit;
    }

    // Sanitize and validate user input
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    $confirm_password = filter_var($_POST['confirm_password'], FILTER_SANITIZE_STRING);

    // Check if the password and confirm password match
    if ($password !== $confirm_password) {
        echo json_encode(array('success' => false, 'message' => 'Passwords do not match'));
        exit;
    }

    // Hash the password using password_hash()
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query to insert the new user
    $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
        // If the user is created successfully, return a JSON response indicating success
        echo json_encode(array('success' => true, 'message' => 'User created successfully'));
    } else {
        // If the user creation fails, return a JSON response indicating failure
        echo json_encode(array('success' => false, 'message' => 'Failed to create user'));
    }
    exit;
}

// Check if the user is attempting to login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'login') {
    // Check if all required fields are present
    if (!isset($_POST['username'], $_POST['password'])) {
        echo json_encode(array('success' => false, 'message' => 'Missing required fields'));
        exit;
    }

    // Sanitize and validate user input
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    // Prepare the SQL query to select the user
    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);

    // Execute the query
    $stmt->execute();
    $user = $stmt->fetch();

    // Check if the user exists and the password is correct
    if ($user && password_verify($password, $user['password'])) {
        // If the user is logged in successfully, return a JSON response indicating success
        echo json_encode(array('success' => true, 'message' => 'User logged in successfully'));
        $_SESSION['user_id'] = $user['id'];
    } else {
        // If the user login fails, return a JSON response indicating failure
        echo json_encode(array('success' => false, 'message' => 'Invalid username or password'));
    }
    exit;
}

// Check if the user is attempting to logout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'logout') {
    // Destroy the session to log out the user
    session_destroy();
    echo json_encode(array('success' => true, 'message' => 'User logged out successfully'));
    exit;
}

// Check if the user is making a GET request to check session status
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Return a JSON response indicating the current session status
    echo json_encode(array('success' => isset($_SESSION['user_id']), 'message' => 'Session status'));
    exit;
}


This code handles user registration, login, logout, and session status checks using prepared statements and secure input validation. It also includes JSON responses for AJAX calls and descriptive comments explaining the security checks and session handling.