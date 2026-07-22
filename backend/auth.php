<?php

// Start the session to handle user authentication
session_start();

// Include the database connection file
require_once 'db.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, return a JSON response with their details
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $response = array(
        'status' => 'logged_in',
        'user_id' => $user_id,
        'username' => $username
    );
    echo json_encode($response);
    exit;
}

// Check if the user is attempting to register or login
if (isset($_POST['action'])) {
    // Check if the action is register
    if ($_POST['action'] == 'register') {
        // Check if the required fields are present
        if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
            // Sanitize the input fields to prevent SQL injection
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $password = password_hash(mysqli_real_escape_string($conn, $_POST['password']), PASSWORD_DEFAULT);

            // Prepare the SQL query to insert the user details into the database
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $password);

            // Execute the query
            $stmt->execute();

            // Check if the user was successfully registered
            if ($stmt->affected_rows == 1) {
                // Return a JSON response with a success message
                $response = array(
                    'status' => 'registered',
                    'message' => 'User registered successfully'
                );
                echo json_encode($response);
            } else {
                // Return a JSON response with an error message
                $response = array(
                    'status' => 'error',
                    'message' => 'Error registering user'
                );
                echo json_encode($response);
            }
        } else {
            // Return a JSON response with an error message if the required fields are missing
            $response = array(
                'status' => 'error',
                'message' => 'Please fill in all fields'
            );
            echo json_encode($response);
        }
    }

    // Check if the action is login
    elseif ($_POST['action'] == 'login') {
        // Check if the required fields are present
        if (isset($_POST['username']) && isset($_POST['password'])) {
            // Sanitize the input fields to prevent SQL injection
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            $password = mysqli_real_escape_string($conn, $_POST['password']);

            // Prepare the SQL query to select the user from the database
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);

            // Execute the query
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if the user exists in the database
            if ($result->num_rows == 1) {
                // Fetch the user details from the result
                $user = $result->fetch_assoc();

                // Verify the password using password_verify()
                if (password_verify($password, $user['password'])) {
                    // If the password is correct, log the user in and return a JSON response with their details
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $response = array(
                        'status' => 'logged_in',
                        'user_id' => $user['id'],
                        'username' => $user['username']
                    );
                    echo json_encode($response);
                } else {
                    // Return a JSON response with an error message if the password is incorrect
                    $response = array(
                        'status' => 'error',
                        'message' => 'Incorrect password'
                    );
                    echo json_encode($response);
                }
            } else {
                // Return a JSON response with an error message if the user does not exist
                $response = array(
                    'status' => 'error',
                    'message' => 'User not found'
                );
                echo json_encode($response);
            }
        } else {
            // Return a JSON response with an error message if the required fields are missing
            $response = array(
                'status' => 'error',
                'message' => 'Please fill in all fields'
            );
            echo json_encode($response);
        }
    }

    // Check if the action is logout
    elseif ($_POST['action'] == 'logout') {
        // Destroy the session to log the user out
        session_destroy();
        $response = array(
            'status' => 'logged_out',
            'message' => 'User logged out successfully'
        );
        echo json_encode($response);
    }
}

// Close the database connection
$conn->close();

?>