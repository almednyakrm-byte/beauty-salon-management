**create_توصيات-الأزياء.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/database.php';

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $image = trim($_POST['image']);

    // Validate input fields
    if (empty($name) || empty($description) || empty($image)) {
        $error = 'Please fill in all fields.';
    } else {
        // Insert new record
        $sql = "INSERT INTO توصيات_الأزياء (name, description, image) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $description, $image]);

        // Redirect back to list page
        header('Location: list_توصيات-الأزياء.php');
        exit;
    }
}

// Include header
require_once '../includes/header.php';

// Include Tailwind UI form
?>

<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-rose-500 mb-4">Create New توصيات الأزياء</h2>
    <form action="" method="post" class="space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
            <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500" required>
        </div>
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description:</label>
            <textarea id="description" name="description" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500" required></textarea>
        </div>
        <div>
            <label for="image" class="block text-sm font-medium text-gray-700">Image:</label>
            <input type="text" id="image" name="image" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500" required>
        </div>
        <button type="submit" class="bg-rose-500 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded-md">Create</button>
    </form>
</div>

<?php
// Include footer
require_once '../includes/footer.php';
?>


**create_توصيات-الأزياء.js**
javascript
$(document).ready(function() {
    // Submit form via AJAX
    $('form').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '../backend/توصيات-الأزياء.php',
            data: $(this).serialize(),
            success: function(response) {
                if (response === 'success') {
                    window.location.href = 'list_توصيات-الأزياء.php';
                } else {
                    alert('Error creating new record.');
                }
            }
        });
    });
});


**توصيات-الأزياء.php (backend)**

<?php
// Include database connection
require_once '../config/database.php';

// Check if form data has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $image = trim($_POST['image']);

    // Insert new record
    $sql = "INSERT INTO توصيات_الأزياء (name, description, image) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $description, $image]);

    // Return success message
    echo 'success';
    exit;
}
?>

Note: This code assumes you have a database connection set up in `config/database.php` and a table named `توصيات_الأزياء` with columns `name`, `description`, and `image`. You'll need to modify the code to fit your specific database schema and configuration.