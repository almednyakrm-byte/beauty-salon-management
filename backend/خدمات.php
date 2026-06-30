<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get user role
$userRole = $_SESSION['user_role'];

// Check if user is admin
$isAdmin = ($userRole == 'admin');

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Validate input data
if (empty($inputData)) {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request'));
    exit;
}

// Function to perform CRUD operations
function crudOperation($pdo, $method, $id = null, $data = null) {
    global $isAdmin;

    // Validate and sanitize input data
    if ($method == 'POST' || $method == 'PUT') {
        $requiredFields = array('name', 'description');
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                http_response_code(400);
                echo json_encode(array('error' => 'Missing required field: ' . $field));
                exit;
            }
            $data[$field] = htmlspecialchars($data[$field]);
        }
    }

    // Perform CRUD operation
    switch ($method) {
        case 'GET':
            // Select all records
            $stmt = $pdo->prepare('SELECT * FROM services');
            $stmt->execute();
            $result = $stmt->fetchAll();
            break;
        case 'POST':
            // Insert new record
            if (!$isAdmin) {
                http_response_code(403);
                echo json_encode(array('error' => 'Forbidden'));
                exit;
            }
            $stmt = $pdo->prepare('INSERT INTO services (name, description) VALUES (:name, :description)');
            $stmt->execute(array(':name' => $data['name'], ':description' => $data['description']));
            $result = array('message' => 'Service created successfully');
            break;
        case 'PUT':
            // Update existing record
            if (!$isAdmin) {
                http_response_code(403);
                echo json_encode(array('error' => 'Forbidden'));
                exit;
            }
            if ($id === null) {
                http_response_code(400);
                echo json_encode(array('error' => 'Invalid request'));
                exit;
            }
            $stmt = $pdo->prepare('UPDATE services SET name = :name, description = :description WHERE id = :id');
            $stmt->execute(array(':id' => $id, ':name' => $data['name'], ':description' => $data['description']));
            $result = array('message' => 'Service updated successfully');
            break;
        case 'DELETE':
            // Delete existing record
            if (!$isAdmin) {
                http_response_code(403);
                echo json_encode(array('error' => 'Forbidden'));
                exit;
            }
            if ($id === null) {
                http_response_code(400);
                echo json_encode(array('error' => 'Invalid request'));
                exit;
            }
            $stmt = $pdo->prepare('DELETE FROM services WHERE id = :id');
            $stmt->execute(array(':id' => $id));
            $result = array('message' => 'Service deleted successfully');
            break;
        default:
            http_response_code(405);
            echo json_encode(array('error' => 'Method not allowed'));
            exit;
    }

    // Return result
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($result);
}

// Handle requests
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        crudOperation($pdo, 'GET');
        break;
    case 'POST':
        crudOperation($pdo, 'POST', null, $inputData);
        break;
    case 'PUT':
        $id = (int) $_GET['id'];
        crudOperation($pdo, 'PUT', $id, $inputData);
        break;
    case 'DELETE':
        $id = (int) $_GET['id'];
        crudOperation($pdo, 'DELETE', $id);
        break;
    default:
        http_response_code(405);
        echo json_encode(array('error' => 'Method not allowed'));
        exit;
}