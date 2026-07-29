<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = array(
    '/salonat' => array('GET', 'POST'),
    '/salonat/:id' => array('GET', 'PUT', 'DELETE')
);

// Define allowed roles for each route
$allowedRoles = array(
    '/salonat' => array('admin', 'user'),
    '/salonat/:id' => array('admin')
);

// Check if route is allowed for current user
$match = false;
foreach ($routes as $route => $methods) {
    if (strpos($route, '/salonat/') === 0) {
        $id = explode('/', $route)[2];
        if (isset($input['id']) && $input['id'] == $id) {
            $match = true;
            break;
        }
    } elseif ($route == '/salonat') {
        $match = true;
        break;
    }
}
if (!$match) {
    http_response_code(404);
    echo json_encode(array('error' => 'Not Found'));
    exit;
}

// Check if user has allowed role
if (!in_array($_SESSION['user_role'], $allowedRoles[$route])) {
    http_response_code(403);
    echo json_encode(array('error' => 'Forbidden'));
    exit;
}

// Handle GET request
if (in_array('GET', $routes[$route])) {
    $sql = "SELECT * FROM salonat";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $salonats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($salonats);
}

// Handle POST request
if (in_array('POST', $routes[$route])) {
    // Validate input data
    if (!isset($input['name']) || !isset($input['address'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $name = htmlspecialchars($input['name']);
    $address = htmlspecialchars($input['address']);

    // Insert data into database
    $sql = "INSERT INTO salonat (name, address) VALUES (:name, :address)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':address', $address);
    $stmt->execute();

    // Return success response
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Salonat created successfully'));
}

// Handle PUT request
if (in_array('PUT', $routes[$route])) {
    // Validate input data
    if (!isset($input['id']) || !isset($input['name']) || !isset($input['address'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = htmlspecialchars($input['id']);
    $name = htmlspecialchars($input['name']);
    $address = htmlspecialchars($input['address']);

    // Update data in database
    $sql = "UPDATE salonat SET name = :name, address = :address WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':address', $address);
    $stmt->execute();

    // Return success response
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Salonat updated successfully'));
}

// Handle DELETE request
if (in_array('DELETE', $routes[$route])) {
    // Validate input data
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = htmlspecialchars($input['id']);

    // Delete data from database
    $sql = "DELETE FROM salonat WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return success response
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Salonat deleted successfully'));
}