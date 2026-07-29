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
    '/get-all' => 'getAll',
    '/get-one' => 'getOne',
    '/create' => 'create',
    '/update' => 'update',
    '/delete' => 'delete'
);

// Get route
$match = null;
foreach ($routes as $route => $method) {
    if (strpos($_SERVER['REQUEST_URI'], $route) !== false) {
        $match = $route;
        break;
    }
}

// Call method
if ($match) {
    $method = $routes[$match];
    $method();
} else {
    http_response_code(404);
    echo json_encode(array('error' => 'Not found'));
}

// Methods
function getAll() {
    global $db;
    $stmt = $db->prepare('SELECT * FROM توصيات_الأزياء');
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($result);
}

function getOne() {
    global $db;
    $id = $_GET['id'];
    $stmt = $db->prepare('SELECT * FROM توصيات_الأزياء WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($result);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
}

function create() {
    global $db;
    // Validate input
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        return;
    }
    
    // Sanitize input
    $name = $db->quote($input['name']);
    $description = $db->quote($input['description']);
    
    // Insert data
    $stmt = $db->prepare('INSERT INTO توصيات_الأزياء (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();
    
    // Get ID of inserted row
    $id = $db->lastInsertId();
    
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('id' => $id));
}

function update() {
    global $db;
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        return;
    }
    
    // Get ID of item to update
    $id = $_GET['id'];
    
    // Validate input
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        return;
    }
    
    // Sanitize input
    $name = $db->quote($input['name']);
    $description = $db->quote($input['description']);
    
    // Update data
    $stmt = $db->prepare('UPDATE توصيات_الأزياء SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();
    
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Updated successfully'));
}

function delete() {
    global $db;
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        return;
    }
    
    // Get ID of item to delete
    $id = $_GET['id'];
    
    // Delete data
    $stmt = $db->prepare('DELETE FROM توصيات_الأزياء WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Deleted successfully'));
}
?>