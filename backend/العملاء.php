<?php

require_once 'db.php';

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

// Get the user role and ID from the session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Check if the user is logged in
if (!$userID) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Handle GET requests
if ($method === 'GET') {
    // Get the customer ID from the URL query string
    $customerID = $_GET['id'] ?? null;

    // Check if the user is an admin to allow edits/deletions
    if ($userRole !== 'admin' && ($customerID || $customerID === 0)) {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Select all customers or a single customer by ID
    if ($customerID) {
        $stmt = $pdo->prepare('SELECT * FROM العملاء WHERE id = :id');
        $stmt->bindParam(':id', $customerID);
        $stmt->execute();
        $customer = $stmt->fetch();
        if (!$customer) {
            http_response_code(404);
            echo json_encode(array('error' => 'Not Found'));
            exit;
        }
        echo json_encode($customer);
    } else {
        $stmt = $pdo->prepare('SELECT * FROM العملاء');
        $stmt->execute();
        $customers = $stmt->fetchAll();
        echo json_encode($customers);
    }
}

// Handle POST requests
if ($method === 'POST') {
    // Get the customer data from the request body
    $customerData = json_decode(file_get_contents('php://input'), true);

    // Validate the customer data
    if (!isset($customerData['name']) || !isset($customerData['email'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Bad Request'));
        exit;
    }

    // Sanitize the customer data
    $customerData['name'] = htmlspecialchars($customerData['name']);
    $customerData['email'] = htmlspecialchars($customerData['email']);

    // Insert the customer into the database
    $stmt = $pdo->prepare('INSERT INTO العملاء (name, email) VALUES (:name, :email)');
    $stmt->bindParam(':name', $customerData['name']);
    $stmt->bindParam(':email', $customerData['email']);
    $stmt->execute();
    $customerID = $pdo->lastInsertId();
    echo json_encode(array('id' => $customerID));
}

// Handle PUT requests
if ($method === 'PUT') {
    // Get the customer ID and data from the URL query string and request body
    $customerID = $_GET['id'];
    $customerData = json_decode(file_get_contents('php://input'), true);

    // Check if the user is an admin to allow edits
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate the customer data
    if (!isset($customerData['name']) || !isset($customerData['email'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Bad Request'));
        exit;
    }

    // Sanitize the customer data
    $customerData['name'] = htmlspecialchars($customerData['name']);
    $customerData['email'] = htmlspecialchars($customerData['email']);

    // Update the customer in the database
    $stmt = $pdo->prepare('UPDATE العملاء SET name = :name, email = :email WHERE id = :id');
    $stmt->bindParam(':name', $customerData['name']);
    $stmt->bindParam(':email', $customerData['email']);
    $stmt->bindParam(':id', $customerID);
    $stmt->execute();
    echo json_encode(array('message' => 'Customer updated successfully'));
}

// Handle DELETE requests
if ($method === 'DELETE') {
    // Get the customer ID from the URL query string
    $customerID = $_GET['id'];

    // Check if the user is an admin to allow deletions
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Delete the customer from the database
    $stmt = $pdo->prepare('DELETE FROM العملاء WHERE id = :id');
    $stmt->bindParam(':id', $customerID);
    $stmt->execute();
    echo json_encode(array('message' => 'Customer deleted successfully'));
}