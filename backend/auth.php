<?php
// Start the session to handle user authentication
session_start();

// Import the database connection script
require_once 'db.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, return a JSON response indicating their status
    echo json_encode(array('status' => 'logged_in', 'user_id' => $_SESSION['user_id']));
    exit;
}

// Handle the login action
if (isset($_POST['action']) && $_POST['action'] == 'login') {
    // Check if the required fields are present in the request
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        echo json_encode(array('status' => 'error', 'message' => 'Please provide both username and password'));
        exit;
    }

    // Sanitize the input fields to prevent SQL injection
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Prepare the SQL query to select the user
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);

    // Execute the query
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Fetch the user data
    $user = mysqli_fetch_assoc($result);

    // Check if the user exists and the password is correct
    if ($user && password_verify($password, $user['password'])) {
        // If the credentials are correct, log the user in and return a JSON response
        $_SESSION['user_id'] = $user['id'];
        echo json_encode(array('status' => 'logged_in', 'user_id' => $_SESSION['user_id']));
    } else {
        // If the credentials are incorrect, return an error message
        echo json_encode(array('status' => 'error', 'message' => 'Invalid username or password'));
    }
    mysqli_stmt_close($stmt);
}

// Handle the register action
if (isset($_POST['action']) && $_POST['action'] == 'register') {
    // Check if the required fields are present in the request
    if (!isset($_POST['username']) || !isset($_POST['email']) || !isset($_POST['password']) || !isset($_POST['confirm_password'])) {
        echo json_encode(array('status' => 'error', 'message' => 'Please provide all required fields'));
        exit;
    }

    // Sanitize the input fields to prevent SQL injection
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Check if the password and confirm password match
    if ($password !== $confirm_password) {
        echo json_encode(array('status' => 'error', 'message' => 'Passwords do not match'));
        exit;
    }

    // Hash the password using password_hash()
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query to insert the new user
    $stmt = mysqli_prepare($conn, "INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);

    // Execute the query
    if (mysqli_stmt_execute($stmt)) {
        // If the user is created successfully, return a JSON response
        echo json_encode(array('status' => 'registered', 'message' => 'User created successfully'));
    } else {
        // If the user creation fails, return an error message
        echo json_encode(array('status' => 'error', 'message' => 'Failed to create user'));
    }
    mysqli_stmt_close($stmt);
}

// Handle the logout action
if (isset($_POST['action']) && $_POST['action'] == 'logout') {
    // Destroy the session to log the user out
    session_destroy();
    echo json_encode(array('status' => 'logged_out'));
}

// If no action is specified, return a JSON response indicating the user status
echo json_encode(array('status' => 'logged_out'));


This code handles user registration, login, logout, and checks the current session user status. It uses prepared statements to prevent SQL injection and password hashing to store passwords securely. The code also includes input field validation and error handling to ensure a secure and robust authentication system.