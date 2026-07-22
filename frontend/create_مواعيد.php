**create_مواعيد.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
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
    $date = trim($_POST['date']);
    $time = trim($_POST['time']);

    // Check if all fields are filled
    if (!empty($name) && !empty($description) && !empty($date) && !empty($time)) {
        // Insert new record into database
        $sql = "INSERT INTO مواعيد (name, description, date, time) VALUES ('$name', '$description', '$date', '$time')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            // Redirect back to list page
            header('Location: list_مواعيد.php');
            exit;
        } else {
            echo 'Error inserting record';
        }
    } else {
        echo 'Please fill all fields';
    }
}

// Include header
require_once '../backend/header.php';
?>

<!-- Create new record form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-rose-500 mb-4">Create New مواعيد</h2>
    <form id="create-form" method="post">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-gray-700 placeholder-gray-400 border border-gray-300 rounded-lg focus:ring-pink-600 focus:border-pink-600" placeholder="Enter name">
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-gray-700 placeholder-gray-400 border border-gray-300 rounded-lg focus:ring-pink-600 focus:border-pink-600" placeholder="Enter description"></textarea>
        </div>
        <div class="mb-4">
            <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
            <input type="date" id="date" name="date" class="block w-full p-2 pl-10 text-sm text-gray-700 placeholder-gray-400 border border-gray-300 rounded-lg focus:ring-pink-600 focus:border-pink-600" placeholder="Enter date">
        </div>
        <div class="mb-4">
            <label for="time" class="block text-sm font-medium text-gray-700">Time</label>
            <input type="time" id="time" name="time" class="block w-full p-2 pl-10 text-sm text-gray-700 placeholder-gray-400 border border-gray-300 rounded-lg focus:ring-pink-600 focus:border-pink-600" placeholder="Enter time">
        </div>
        <button type="submit" name="submit" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">Create</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../backend/footer.php'; ?>


**create_مواعيد.js**
javascript
// Get form element
const form = document.getElementById('create-form');

// Add event listener to form submission
form.addEventListener('submit', (e) => {
    // Prevent default form submission
    e.preventDefault();

    // Get form data
    const formData = new FormData(form);

    // Send AJAX request to backend
    fetch('../backend/مواعيد.php', {
        method: 'POST',
        body: formData
    })
    .then((response) => response.json())
    .then((data) => {
        // Redirect back to list page
        window.location.href = 'list_مواعيد.php';
    })
    .catch((error) => {
        console.error(error);
    });
});


**مواعيد.php (backend)**

<?php
// Include database connection
require_once '../backend/db.php';

// Check if form data has been sent
if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['date']) && isset($_POST['time'])) {
    // Insert new record into database
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $date = trim($_POST['date']);
    $time = trim($_POST['time']);

    $sql = "INSERT INTO مواعيد (name, description, date, time) VALUES ('$name', '$description', '$date', '$time')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo 'Record created successfully';
    } else {
        echo 'Error creating record';
    }
}
?>