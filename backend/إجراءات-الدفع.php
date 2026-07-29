<?php

require_once 'db.php';

// Get user data from session
$user = $_SESSION['user'];

// Check if user is logged in
if (!$user) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Check if user is admin
$is_admin = $user['role'] == 'admin';

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle GET request
if ($method == 'GET') {
    // Get ID from URL parameter
    $id = $_GET['id'] ?? null;

    // Check if ID is provided
    if (!$id) {
        http_response_code(400);
        echo json_encode(array('error' => 'ID is required'));
        exit;
    }

    // Prepare SQL query to select data
    $stmt = $pdo->prepare('SELECT * FROM إجراءات_الدفع WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Fetch data
    $data = $stmt->fetch();

    // Check if data exists
    if (!$data) {
        http_response_code(404);
        echo json_encode(array('error' => 'Data not found'));
        exit;
    }

    // Return data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
}

// Handle POST request
elseif ($method == 'POST') {
    // Read input data
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($input['name']) || !isset($input['description']) || !isset($input['amount'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input data'));
        exit;
    }

    // Sanitize input data
    $name = $pdo->quote($input['name']);
    $description = $pdo->quote($input['description']);
    $amount = $pdo->quote($input['amount']);

    // Prepare SQL query to insert data
    $stmt = $pdo->prepare('INSERT INTO إجراءات_الدفع (name, description, amount) VALUES (:name, :description, :amount)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':amount', $amount);
    $stmt->execute();

    // Return ID of inserted data
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('id' => $pdo->lastInsertId()));
}

// Handle PUT request
elseif ($method == 'PUT') {
    // Get ID from URL parameter
    $id = $_GET['id'] ?? null;

    // Check if ID is provided
    if (!$id) {
        http_response_code(400);
        echo json_encode(array('error' => 'ID is required'));
        exit;
    }

    // Check if user is admin
    if (!$is_admin) {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Read input data
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($input['name']) || !isset($input['description']) || !isset($input['amount'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input data'));
        exit;
    }

    // Sanitize input data
    $name = $pdo->quote($input['name']);
    $description = $pdo->quote($input['description']);
    $amount = $pdo->quote($input['amount']);

    // Prepare SQL query to update data
    $stmt = $pdo->prepare('UPDATE إجراءات_الدفع SET name = :name, description = :description, amount = :amount WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':amount', $amount);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Data updated successfully'));
}

// Handle DELETE request
elseif ($method == 'DELETE') {
    // Get ID from URL parameter
    $id = $_GET['id'] ?? null;

    // Check if ID is provided
    if (!$id) {
        http_response_code(400);
        echo json_encode(array('error' => 'ID is required'));
        exit;
    }

    // Check if user is admin
    if (!$is_admin) {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare SQL query to delete data
    $stmt = $pdo->prepare('DELETE FROM إجراءات_الدفع WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Data deleted successfully'));
}

// Return error message if invalid request method
else {
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed'));
}