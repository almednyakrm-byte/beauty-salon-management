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
    '/muaadid' => array('GET', 'GET ALL'),
    '/muaadid' => array('POST', 'CREATE'),
    '/muaadid/:id' => array('GET', 'GET BY ID'),
    '/muaadid/:id' => array('PUT', 'UPDATE'),
    '/muaadid/:id' => array('DELETE', 'DELETE')
);

// Process route
$match = false;
foreach ($routes as $route => $methods) {
    if (strpos($route, '/') !== false) {
        $parts = explode('/', $route);
        if (count($parts) == 2 && $parts[0] == 'muaadid' && $parts[1] == ':id') {
            if (isset($input['id']) && is_numeric($input['id'])) {
                $id = $input['id'];
                $match = true;
                break;
            }
        } elseif ($route == 'muaadid') {
            $match = true;
            break;
        }
    } else {
        if ($route == 'muaadid') {
            $match = true;
            break;
        }
    }
}

if (!$match) {
    http_response_code(404);
    echo json_encode(array('error' => 'Not Found'));
    exit;
}

// Get HTTP method
$method = $_SERVER['REQUEST_METHOD'];

// Check if user has permission
if (in_array($method, array('PUT', 'DELETE')) && $_SESSION['role'] != 'admin') {
    http_response_code(403);
    echo json_encode(array('error' => 'Forbidden'));
    exit;
}

// Process request
switch ($method) {
    case 'GET':
        if ($route == 'muaadid') {
            // Get all muaadid
            $stmt = $pdo->prepare('SELECT * FROM muaadid');
            $stmt->execute();
            $muaadid = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($muaadid);
        } elseif (strpos($route, '/:id') !== false) {
            // Get muaadid by id
            $stmt = $pdo->prepare('SELECT * FROM muaadid WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $muaadid = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($muaadid) {
                http_response_code(200);
                header('Content-Type: application/json');
                echo json_encode($muaadid);
            } else {
                http_response_code(404);
                echo json_encode(array('error' => 'Not Found'));
            }
        }
        break;
    case 'POST':
        // Create new muaadid
        if (isset($input['name']) && isset($input['date']) && isset($input['time'])) {
            $name = $pdo->quote($input['name']);
            $date = $pdo->quote($input['date']);
            $time = $pdo->quote($input['time']);
            $stmt = $pdo->prepare('INSERT INTO muaadid (name, date, time) VALUES (' . $name . ', ' . $date . ', ' . $time . ')');
            $stmt->execute();
            http_response_code(201);
            header('Content-Type: application/json');
            echo json_encode(array('message' => 'Muaadid created successfully'));
        } else {
            http_response_code(400);
            echo json_encode(array('error' => 'Bad Request'));
        }
        break;
    case 'PUT':
        // Update existing muaadid
        if (isset($input['id']) && is_numeric($input['id']) && isset($input['name']) && isset($input['date']) && isset($input['time'])) {
            $id = $input['id'];
            $name = $pdo->quote($input['name']);
            $date = $pdo->quote($input['date']);
            $time = $pdo->quote($input['time']);
            $stmt = $pdo->prepare('UPDATE muaadid SET name = ' . $name . ', date = ' . $date . ', time = ' . $time . ' WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(array('message' => 'Muaadid updated successfully'));
        } else {
            http_response_code(400);
            echo json_encode(array('error' => 'Bad Request'));
        }
        break;
    case 'DELETE':
        // Delete existing muaadid
        if (isset($input['id']) && is_numeric($input['id'])) {
            $id = $input['id'];
            $stmt = $pdo->prepare('DELETE FROM muaadid WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(array('message' => 'Muaadid deleted successfully'));
        } else {
            http_response_code(400);
            echo json_encode(array('error' => 'Bad Request'));
        }
        break;
}

exit;
?>