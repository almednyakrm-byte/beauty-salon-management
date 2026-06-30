**create_دفتر-المبيعات.php**

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

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    // Check if fields are not empty
    if (!empty($name) && !empty($description)) {
        // Insert data into database
        $sql = "INSERT INTO دفتر_المبيعات (name, description) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $name, $description);
        $stmt->execute();

        // Redirect back to list page
        header('Location: list_دفتر-المبيعات.php');
        exit;
    } else {
        $error = 'Please fill in all fields';
    }
}

// Include header
require_once '../includes/header.php';

// Include premium Tailwind UI form
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-8">
    <div class="bg-white rounded-lg shadow-md p-4">
        <h2 class="text-lg font-bold text-teal-500 mb-4">Create New دفتر المبيعات</h2>
        <form id="create-form" method="post">
            <div class="mb-4">
                <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Name</label>
                <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Description</label>
                <textarea id="description" name="description" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500" required></textarea>
            </div>
            <button type="submit" name="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded-lg">Create</button>
        </form>
        <?php if (isset($error)) : ?>
            <p class="text-red-500 mt-4"><?= $error ?></p>
        <?php endif; ?>
    </div>
</div>

<?php
// Include footer
require_once '../includes/footer.php';
?>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/دفتر-المبيعات.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_دفتر-المبيعات.php';
                    } else {
                        alert('Error creating new record');
                    }
                }
            });
        });
    });
</script>


**../backend/دفتر-المبيعات.php**

<?php
// Include database connection
require_once '../config/db.php';

// Check if form data has been submitted
if (isset($_POST['name']) && isset($_POST['description'])) {
    // Insert data into database
    $name = $_POST['name'];
    $description = $_POST['description'];
    $sql = "INSERT INTO دفتر_المبيعات (name, description) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $name, $description);
    $stmt->execute();

    // Return success message
    echo 'success';
    exit;
}