**create_خدمات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);

    if (!empty($name) && !empty($description) && !empty($price)) {
        // Insert data into database
        $query = "INSERT INTO services (name, description, price) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('sss', $name, $description, $price);
        $stmt->execute();

        // Redirect back to list page
        header('Location: list_خدمات.php');
        exit;
    } else {
        // Display error message
        $error = 'Please fill in all fields';
    }
}

// Include header
require_once '../includes/header.php';

// Include form
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12 2xl:p-12">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-8 2xl:p-8">
        <h2 class="text-lg font-bold text-rose-500 mb-4">Create New Service</h2>
        <?php if (isset($error)) : ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded text-sm" role="alert">
                <?= $error ?>
            </div>
        <?php endif; ?>
        <form id="create-service-form" method="post">
            <div class="mb-4">
                <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Service Name</label>
                <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-rose-500 focus:border-rose-500" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Service Description</label>
                <textarea id="description" name="description" class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-rose-500 focus:border-rose-500" required></textarea>
            </div>
            <div class="mb-4">
                <label for="price" class="block text-sm font-bold text-gray-700 mb-2">Service Price</label>
                <input type="number" id="price" name="price" class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-rose-500 focus:border-rose-500" required>
            </div>
            <button type="submit" class="bg-rose-500 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded">Create Service</button>
        </form>
    </div>
</div>

<?php
// Include footer
require_once '../includes/footer.php';
?>


**create_خدمات.js**
javascript
$(document).ready(function() {
    $('#create-service-form').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: '../backend/خدمات.php',
            data: formData,
            success: function(response) {
                if (response === 'success') {
                    window.location.href = 'list_خدمات.php';
                } else {
                    alert('Error creating service');
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr, status, error);
            }
        });
    });
});


**../backend/خدمات.php**

<?php
// Include database connection
require_once '../config/db.php';

// Check if form data is posted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);

    if (!empty($name) && !empty($description) && !empty($price)) {
        // Insert data into database
        $query = "INSERT INTO services (name, description, price) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('sss', $name, $description, $price);
        $stmt->execute();

        // Return success message
        echo 'success';
    } else {
        // Return error message
        echo 'Error creating service';
    }
}