<?php

require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($method === 'GET') {
    // Validate user role
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        return;
    }

    // Get services
    $stmt = $pdo->prepare('SELECT * FROM services');
    $stmt->execute();
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output services
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($services);
}

// Handle POST request
elseif ($method === 'POST') {
    // Validate user role
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        return;
    }

    // Validate input data
    if (!isset($inputData['name']) || !isset($inputData['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        return;
    }

    // Sanitize input data
    $name = htmlspecialchars($inputData['name']);
    $description = htmlspecialchars($inputData['description']);

    // Insert service
    $stmt = $pdo->prepare('INSERT INTO services (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Output service ID
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['serviceID' => $pdo->lastInsertId()]);
}

// Handle PUT request
elseif ($method === 'PUT') {
    // Validate user role
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        return;
    }

    // Validate input data
    if (!isset($inputData['serviceID']) || !isset($inputData['name']) || !isset($inputData['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        return;
    }

    // Sanitize input data
    $serviceID = (int) $inputData['serviceID'];
    $name = htmlspecialchars($inputData['name']);
    $description = htmlspecialchars($inputData['description']);

    // Update service
    $stmt = $pdo->prepare('UPDATE services SET name = :name, description = :description WHERE serviceID = :serviceID');
    $stmt->bindParam(':serviceID', $serviceID);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Output success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Service updated successfully']);
}

// Handle DELETE request
elseif ($method === 'DELETE') {
    // Validate user role
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        return;
    }

    // Validate input data
    if (!isset($inputData['serviceID'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        return;
    }

    // Sanitize input data
    $serviceID = (int) $inputData['serviceID'];

    // Delete service
    $stmt = $pdo->prepare('DELETE FROM services WHERE serviceID = :serviceID');
    $stmt->bindParam(':serviceID', $serviceID);
    $stmt->execute();

    // Output success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Service deleted successfully']);
}

// Output error message for invalid request method
else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}