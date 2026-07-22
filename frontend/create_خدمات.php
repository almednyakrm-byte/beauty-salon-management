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
require_once '../backend/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);

    if (!empty($name) && !empty($description) && !empty($price)) {
        // Insert data into database
        $query = "INSERT INTO خدمات (name, description, price) VALUES ('$name', '$description', '$price')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Redirect back to list page
            header('Location: list_خدمات.php');
            exit;
        } else {
            echo 'Error inserting data';
        }
    } else {
        echo 'Please fill in all fields';
    }
}

// Include header
require_once '../backend/header.php';

?>

<!-- Create new service form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-pink-600 mb-4">Create New Service</h2>
    <form id="create-service-form" method="POST">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
            <input type="text" id="name" name="name" class="block w-full py-2 pl-10 text-sm text-gray-700 border-gray-300 rounded-lg focus:border-pink-600 focus:ring-pink-600" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description:</label>
            <textarea id="description" name="description" class="block w-full py-2 pl-10 text-sm text-gray-700 border-gray-300 rounded-lg focus:border-pink-600 focus:ring-pink-600" required></textarea>
        </div>
        <div class="mb-4">
            <label for="price" class="block text-sm font-medium text-gray-700">Price:</label>
            <input type="number" id="price" name="price" class="block w-full py-2 pl-10 text-sm text-gray-700 border-gray-300 rounded-lg focus:border-pink-600 focus:ring-pink-600" required>
        </div>
        <button type="submit" name="submit" class="bg-rose-500 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded">Create Service</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../backend/footer.php'; ?>


**create_خدمات.js**
javascript
$(document).ready(function() {
    // Submit form via AJAX
    $('#create-service-form').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '../backend/خدمات.php',
            data: $(this).serialize(),
            success: function(response) {
                if (response === 'true') {
                    window.location.href = 'list_خدمات.php';
                } else {
                    alert('Error creating service');
                }
            }
        });
    });
});


**../backend/خدمات.php**

<?php
// Include database connection
require_once '../backend/db.php';

// Check if form data has been submitted
if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['price'])) {
    // Insert data into database
    $query = "INSERT INTO خدمات (name, description, price) VALUES ('$_POST[name]', '$_POST[description]', '$_POST[price]')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo 'true';
    } else {
        echo 'Error inserting data';
    }
}