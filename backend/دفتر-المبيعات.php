<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    http_response_code(403);
    echo json_encode(array('error' => 'Forbidden'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if (isset($_GET['action']) && $_GET['action'] == 'get_all') {
    try {
        // Prepare SQL query
        $stmt = $pdo->prepare('SELECT * FROM دفتر_المبيعات');
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($data);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'get_one') {
    try {
        // Prepare SQL query
        $stmt = $pdo->prepare('SELECT * FROM دفتر_المبيعات WHERE id = :id');
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($data);
        } else {
            http_response_code(404);
            echo json_encode(array('error' => 'Not Found'));
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle POST request
if (isset($_POST['action']) && $_POST['action'] == 'create') {
    try {
        // Validate input data
        if (!isset($input['name']) || !isset($input['description'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Bad Request'));
            exit;
        }

        // Sanitize input data
        $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
        $description = filter_var($input['description'], FILTER_SANITIZE_STRING);

        // Prepare SQL query
        $stmt = $pdo->prepare('INSERT INTO دفتر_المبيعات (name, description) VALUES (:name, :description)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();

        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Created successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle PUT request
if (isset($_PUT['action']) && $_PUT['action'] == 'update') {
    try {
        // Validate input data
        if (!isset($input['id']) || !isset($input['name']) || !isset($input['description'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Bad Request'));
            exit;
        }

        // Sanitize input data
        $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
        $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
        $description = filter_var($input['description'], FILTER_SANITIZE_STRING);

        // Prepare SQL query
        $stmt = $pdo->prepare('UPDATE دفتر_المبيعات SET name = :name, description = :description WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Updated successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle DELETE request
if (isset($_DELETE['action']) && $_DELETE['action'] == 'delete') {
    try {
        // Validate input data
        if (!isset($input['id'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Bad Request'));
            exit;
        }

        // Sanitize input data
        $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);

        // Prepare SQL query
        $stmt = $pdo->prepare('DELETE FROM دفتر_المبيعات WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Deleted successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

?>