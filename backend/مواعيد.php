<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = [
    '/appointments' => [
        'GET' => function() use ($input) {
            // Validate and sanitize input
            $limit = isset($input['limit']) ? intval($input['limit']) : 10;
            $offset = isset($input['offset']) ? intval($input['offset']) : 0;

            // Prepare SQL query
            $stmt = $pdo->prepare('SELECT * FROM appointments LIMIT :limit OFFSET :offset');
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            // Fetch and return data
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($data);
        },
        'POST' => function() use ($input) {
            // Validate and sanitize input
            $title = isset($input['title']) ? trim($input['title']) : '';
            $description = isset($input['description']) ? trim($input['description']) : '';
            $date = isset($input['date']) ? date('Y-m-d', strtotime($input['date'])) : '';

            // Check if user is admin
            if ($_SESSION['user_role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(['error' => 'Forbidden']);
                exit;
            }

            // Prepare SQL query
            $stmt = $pdo->prepare('INSERT INTO appointments (title, description, date) VALUES (:title, :description, :date)');
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':date', $date);
            $stmt->execute();

            // Return created ID
            http_response_code(201);
            header('Content-Type: application/json');
            echo json_encode(['id' => $pdo->lastInsertId()]);
        }
    ],
    '/appointments/:id' => [
        'GET' => function($id) use ($input) {
            // Validate and sanitize input
            $id = intval($id);

            // Prepare SQL query
            $stmt = $pdo->prepare('SELECT * FROM appointments WHERE id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Fetch and return data
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$data) {
                http_response_code(404);
                echo json_encode(['error' => 'Not Found']);
                exit;
            }
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($data);
        },
        'PUT' => function($id) use ($input) {
            // Validate and sanitize input
            $id = intval($id);
            $title = isset($input['title']) ? trim($input['title']) : '';
            $description = isset($input['description']) ? trim($input['description']) : '';
            $date = isset($input['date']) ? date('Y-m-d', strtotime($input['date'])) : '';

            // Check if user is admin
            if ($_SESSION['user_role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(['error' => 'Forbidden']);
                exit;
            }

            // Prepare SQL query
            $stmt = $pdo->prepare('UPDATE appointments SET title = :title, description = :description, date = :date WHERE id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':date', $date);
            $stmt->execute();

            // Return updated ID
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['id' => $id]);
        },
        'DELETE' => function($id) {
            // Validate and sanitize input
            $id = intval($id);

            // Check if user is admin
            if ($_SESSION['user_role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(['error' => 'Forbidden']);
                exit;
            }

            // Prepare SQL query
            $stmt = $pdo->prepare('DELETE FROM appointments WHERE id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Return deleted ID
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['id' => $id]);
        }
    ]
];

// Match route and method
$match = explode('/', $_SERVER['REQUEST_URI']);
$method = $_SERVER['REQUEST_METHOD'];
$routes[$match[1]][$method]($match[2] ?? '');