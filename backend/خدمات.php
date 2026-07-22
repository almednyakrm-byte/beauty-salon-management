<?php
require_once 'db.php';

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get the user role
$user_role = $_SESSION['user_role'];

// Handle GET requests
if ($method === 'GET') {
    // Get the ID parameter from the URL
    $id = $_GET['id'] ?? null;

    // Check if the user is an admin to allow GET requests for all records
    if ($user_role !== 'admin' && $id !== null) {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get all records
    if ($id === null) {
        $stmt = $pdo->prepare('SELECT * FROM services');
        $stmt->execute();
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($services);
    } else {
        // Get a single record by ID
        $stmt = $pdo->prepare('SELECT * FROM services WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $service = $stmt->fetch(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($service);
    }
} elseif ($method === 'POST') {
    // Read the JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate the input
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Sanitize the input
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($input['description'], FILTER_SANITIZE_STRING);

    // Insert the new record
    $stmt = $pdo->prepare('INSERT INTO services (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Service created successfully']);
} elseif ($method === 'PUT') {
    // Get the ID parameter from the URL
    $id = $_GET['id'] ?? null;

    // Check if the user is an admin to allow PUT requests
    if ($user_role !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Read the JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate the input
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Sanitize the input
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($input['description'], FILTER_SANITIZE_STRING);

    // Update the existing record
    $stmt = $pdo->prepare('UPDATE services SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Service updated successfully']);
} elseif ($method === 'DELETE') {
    // Get the ID parameter from the URL
    $id = $_GET['id'] ?? null;

    // Check if the user is an admin to allow DELETE requests
    if ($user_role !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Delete the record
    $stmt = $pdo->prepare('DELETE FROM services WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Service deleted successfully']);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}