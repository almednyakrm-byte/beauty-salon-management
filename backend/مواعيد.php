<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Check if user is admin
if (isset($_SESSION['role']) && $_SESSION['role'] != 'admin') {
    http_response_code(403);
    echo json_encode(array('error' => 'Forbidden'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    try {
        // Validate input
        if (!isset($input['id'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid request'));
            exit;
        }

        // Sanitize input
        $id = (int) $input['id'];

        // SQL query
        $stmt = $pdo->prepare('SELECT * FROM مواعيد WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Fetch data
        $data = $stmt->fetch();

        // Output
        if ($data) {
            http_response_code(200);
            echo json_encode($data);
        } else {
            http_response_code(404);
            echo json_encode(array('error' => 'Not found'));
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle POST request
elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Validate input
        if (!isset($input['title']) || !isset($input['date']) || !isset($input['time'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid request'));
            exit;
        }

        // Sanitize input
        $title = trim($input['title']);
        $date = trim($input['date']);
        $time = trim($input['time']);

        // SQL query
        $stmt = $pdo->prepare('INSERT INTO مواعيد (title, date, time) VALUES (:title, :date, :time)');
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':time', $time);
        $stmt->execute();

        // Output
        http_response_code(201);
        echo json_encode(array('message' => 'Created successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle PUT request
elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    try {
        // Validate input
        if (!isset($input['id']) || !isset($input['title']) || !isset($input['date']) || !isset($input['time'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid request'));
            exit;
        }

        // Sanitize input
        $id = (int) $input['id'];
        $title = trim($input['title']);
        $date = trim($input['date']);
        $time = trim($input['time']);

        // SQL query
        $stmt = $pdo->prepare('UPDATE مواعيد SET title = :title, date = :date, time = :time WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':time', $time);
        $stmt->execute();

        // Output
        http_response_code(200);
        echo json_encode(array('message' => 'Updated successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle DELETE request
elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    try {
        // Validate input
        if (!isset($input['id'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid request'));
            exit;
        }

        // Sanitize input
        $id = (int) $input['id'];

        // SQL query
        $stmt = $pdo->prepare('DELETE FROM مواعيد WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Output
        http_response_code(200);
        echo json_encode(array('message' => 'Deleted successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Set headers
header('Content-Type: application/json');