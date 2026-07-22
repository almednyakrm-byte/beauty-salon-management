<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = array(
    '/customers' => array('GET', 'POST'),
    '/customers/:id' => array('GET', 'PUT', 'DELETE')
);

// Define allowed roles for each route
$allowedRoles = array(
    '/customers' => array('admin', 'user'),
    '/customers/:id' => array('admin')
);

// Check if route is valid
$match = false;
foreach ($routes as $route => $methods) {
    if (strpos($route, '/customers/') === 0) {
        $id = explode('/', $route)[2];
        if (isset($input['id']) && $input['id'] == $id) {
            $match = true;
            break;
        }
    } elseif ($route == '/customers') {
        $match = true;
        break;
    }
}

if (!$match) {
    http_response_code(404);
    echo json_encode(array('error' => 'Not found'));
    exit;
}

// Get allowed roles for current route
$allowedRolesForRoute = $allowedRoles[$route];

// Check if user has required role
if (!in_array($_SESSION['user_role'], $allowedRolesForRoute)) {
    http_response_code(403);
    echo json_encode(array('error' => 'Forbidden'));
    exit;
}

// Handle GET request
if (in_array('GET', $methods)) {
    // Validate input
    if (isset($input['id'])) {
        $id = filter_var($input['id'], FILTER_VALIDATE_INT);
        if ($id === false) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid ID'));
            exit;
        }
    }

    // Prepare SQL query
    $sql = 'SELECT * FROM customers';
    if (isset($id)) {
        $sql .= ' WHERE id = :id';
    }

    // Execute SQL query
    $stmt = $pdo->prepare($sql);
    if (isset($id)) {
        $stmt->bindParam(':id', $id);
    }
    $stmt->execute();

    // Fetch results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output results
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($results);
    exit;
}

// Handle POST request
if (in_array('POST', $methods)) {
    // Validate input
    $requiredFields = array('name', 'email', 'phone');
    foreach ($requiredFields as $field) {
        if (!isset($input[$field])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Missing required field: ' . $field));
            exit;
        }
    }

    // Sanitize input
    $input['name'] = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $input['email'] = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
    $input['phone'] = filter_var($input['phone'], FILTER_SANITIZE_NUMBER_INT);

    // Prepare SQL query
    $sql = 'INSERT INTO customers (name, email, phone) VALUES (:name, :email, :phone)';

    // Execute SQL query
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':email', $input['email']);
    $stmt->bindParam(':phone', $input['phone']);
    $stmt->execute();

    // Output result
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Customer created successfully'));
    exit;
}

// Handle PUT request
if (in_array('PUT', $methods)) {
    // Validate input
    $requiredFields = array('id', 'name', 'email', 'phone');
    foreach ($requiredFields as $field) {
        if (!isset($input[$field])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Missing required field: ' . $field));
            exit;
        }
    }

    // Sanitize input
    $input['name'] = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $input['email'] = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
    $input['phone'] = filter_var($input['phone'], FILTER_SANITIZE_NUMBER_INT);

    // Prepare SQL query
    $sql = 'UPDATE customers SET name = :name, email = :email, phone = :phone WHERE id = :id';

    // Execute SQL query
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $input['id']);
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':email', $input['email']);
    $stmt->bindParam(':phone', $input['phone']);
    $stmt->execute();

    // Output result
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Customer updated successfully'));
    exit;
}

// Handle DELETE request
if (in_array('DELETE', $methods)) {
    // Validate input
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Missing required field: id'));
        exit;
    }

    // Sanitize input
    $id = filter_var($input['id'], FILTER_VALIDATE_INT);
    if ($id === false) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid ID'));
        exit;
    }

    // Prepare SQL query
    $sql = 'DELETE FROM customers WHERE id = :id';

    // Execute SQL query
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Output result
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Customer deleted successfully'));
    exit;
}