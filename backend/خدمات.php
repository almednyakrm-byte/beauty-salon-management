<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = array(
    '/services' => array('GET', 'POST'),
    '/services/:id' => array('GET', 'PUT', 'DELETE')
);

// Get route and method
$matches = array();
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];
foreach ($routes as $route => $methods) {
    if (preg_match('#^' . preg_quote($route, '#') . '$#', $uri, $matches)) {
        $route = $matches[0];
        break;
    }
}

// Handle route and method
if ($route == '/services') {
    if (in_array($method, array('GET'))) {
        // GET all services
        $stmt = $pdo->prepare("SELECT * FROM services");
        $stmt->execute();
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($services);
    } elseif (in_array($method, array('POST'))) {
        // Validate input data
        if (!isset($input['name']) || !isset($input['description'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid input'));
            exit;
        }

        // Sanitize input data
        $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
        $description = filter_var($input['description'], FILTER_SANITIZE_STRING);

        // Insert new service
        $stmt = $pdo->prepare("INSERT INTO services (name, description) VALUES (:name, :description)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Service created successfully'));
    } else {
        http_response_code(405);
        echo json_encode(array('error' => 'Method not allowed'));
    }
} elseif ($route == '/services/:id') {
    $id = $matches[1];
    if (in_array($method, array('GET'))) {
        // GET service by ID
        $stmt = $pdo->prepare("SELECT * FROM services WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $service = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($service) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($service);
        } else {
            http_response_code(404);
            echo json_encode(array('error' => 'Service not found'));
        }
    } elseif (in_array($method, array('PUT'))) {
        // Validate input data
        if (!isset($input['name']) || !isset($input['description'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid input'));
            exit;
        }

        // Sanitize input data
        $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
        $description = filter_var($input['description'], FILTER_SANITIZE_STRING);

        // Update service
        $stmt = $pdo->prepare("UPDATE services SET name = :name, description = :description WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Service updated successfully'));
    } elseif (in_array($method, array('DELETE'))) {
        // Check if user is admin
        if ($_SESSION['role'] != 'admin') {
            http_response_code(403);
            echo json_encode(array('error' => 'Forbidden'));
            exit;
        }

        // Delete service
        $stmt = $pdo->prepare("DELETE FROM services WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Service deleted successfully'));
    } else {
        http_response_code(405);
        echo json_encode(array('error' => 'Method not allowed'));
    }
} else {
    http_response_code(404);
    echo json_encode(array('error' => 'Route not found'));
}