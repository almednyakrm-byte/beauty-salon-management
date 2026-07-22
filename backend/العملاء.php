<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Check if user is logged in
if (!$userID) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Handle GET request
if ($method === 'GET') {
    // Validate and sanitize input
    $limit = filter_var($_GET['limit'], FILTER_SANITIZE_NUMBER_INT);
    $offset = filter_var($_GET['offset'], FILTER_SANITIZE_NUMBER_INT);

    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM العملاء LIMIT :limit OFFSET :offset');
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch and return data
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Handle POST request
if ($method === 'POST') {
    // Read JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($input['phone'], FILTER_SANITIZE_NUMBER_INT);

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('INSERT INTO العملاء (name, email, phone) VALUES (:name, :email, :phone)');
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':phone', $phone, PDO::PARAM_INT);
    $stmt->execute();

    // Return success response
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Client created successfully'));
    exit;
}

// Handle PUT request
if ($method === 'PUT') {
    // Read JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($input['phone'], FILTER_SANITIZE_NUMBER_INT);

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('UPDATE العملاء SET name = :name, email = :email, phone = :phone WHERE id = :id');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':phone', $phone, PDO::PARAM_INT);
    $stmt->execute();

    // Return success response
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Client updated successfully'));
    exit;
}

// Handle DELETE request
if ($method === 'DELETE') {
    // Validate and sanitize input
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('DELETE FROM العملاء WHERE id = :id');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Return success response
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Client deleted successfully'));
    exit;
}

// Return error response for unsupported methods
http_response_code(405);
echo json_encode(array('error' => 'Method not allowed'));
exit;