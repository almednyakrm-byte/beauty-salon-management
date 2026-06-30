<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Check if input data is valid
if (empty($input)) {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid input'));
    exit;
}

// Check if user is admin for edit/deletion operations
if (isset($input['action']) && in_array($input['action'], array('edit', 'delete'))) {
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
}

// Handle CRUD operations
switch ($input['action']) {
    case 'get':
        // Get all customers
        $stmt = $pdo->prepare('SELECT * FROM العملاء');
        $stmt->execute();
        $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($customers);
        break;
    case 'create':
        // Validate input data
        if (!isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid input'));
            exit;
        }

        // Sanitize input data
        $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
        $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
        $phone = filter_var($input['phone'], FILTER_SANITIZE_NUMBER_INT);

        // Insert new customer
        $stmt = $pdo->prepare('INSERT INTO العملاء (name, email, phone) VALUES (:name, :email, :phone)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();
        http_response_code(201);
        echo json_encode(array('message' => 'Customer created successfully'));
        break;
    case 'edit':
        // Validate input data
        if (!isset($input['id']) || !isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid input'));
            exit;
        }

        // Sanitize input data
        $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
        $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
        $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
        $phone = filter_var($input['phone'], FILTER_SANITIZE_NUMBER_INT);

        // Update customer
        $stmt = $pdo->prepare('UPDATE العملاء SET name = :name, email = :email, phone = :phone WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();
        http_response_code(200);
        echo json_encode(array('message' => 'Customer updated successfully'));
        break;
    case 'delete':
        // Validate input data
        if (!isset($input['id'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid input'));
            exit;
        }

        // Sanitize input data
        $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);

        // Delete customer
        $stmt = $pdo->prepare('DELETE FROM العملاء WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        http_response_code(200);
        echo json_encode(array('message' => 'Customer deleted successfully'));
        break;
    default:
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid action'));
        break;
}