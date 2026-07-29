<?php
require_once 'db.php';

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Check if the user is an admin
if ($method === 'PUT' || $method === 'DELETE') {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
}

// Get the request body
$body = json_decode(file_get_contents('php://input'), true);

// Handle GET requests
if ($method === 'GET') {
    // Validate the request parameters
    if (!isset($body['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Prepare the SQL query
    $query = 'SELECT * FROM appointments WHERE id = :id';
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $body['id']);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch();

    // Return the result
    if ($result) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($result);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Not found']);
    }
}

// Handle POST requests
if ($method === 'POST') {
    // Validate the request parameters
    if (!isset($body['title']) || !isset($body['date']) || !isset($body['time'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Sanitize the request parameters
    $body['title'] = htmlspecialchars($body['title']);
    $body['date'] = htmlspecialchars($body['date']);
    $body['time'] = htmlspecialchars($body['time']);

    // Prepare the SQL query
    $query = 'INSERT INTO appointments (title, date, time) VALUES (:title, :date, :time)';
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':title', $body['title']);
    $stmt->bindParam(':date', $body['date']);
    $stmt->bindParam(':time', $body['time']);
    $stmt->execute();

    // Return the result
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Appointment created successfully']);
}

// Handle PUT requests
if ($method === 'PUT') {
    // Validate the request parameters
    if (!isset($body['id']) || !isset($body['title']) || !isset($body['date']) || !isset($body['time'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Sanitize the request parameters
    $body['title'] = htmlspecialchars($body['title']);
    $body['date'] = htmlspecialchars($body['date']);
    $body['time'] = htmlspecialchars($body['time']);

    // Prepare the SQL query
    $query = 'UPDATE appointments SET title = :title, date = :date, time = :time WHERE id = :id';
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $body['id']);
    $stmt->bindParam(':title', $body['title']);
    $stmt->bindParam(':date', $body['date']);
    $stmt->bindParam(':time', $body['time']);
    $stmt->execute();

    // Return the result
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Appointment updated successfully']);
}

// Handle DELETE requests
if ($method === 'DELETE') {
    // Validate the request parameters
    if (!isset($body['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Prepare the SQL query
    $query = 'DELETE FROM appointments WHERE id = :id';
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $body['id']);
    $stmt->execute();

    // Return the result
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Appointment deleted successfully']);
}