**create_إجراءات-الدفع.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/database.php';

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Sanitize input
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $amount = filter_var($_POST['amount'], FILTER_SANITIZE_NUMBER_INT);
    $date = filter_var($_POST['date'], FILTER_SANITIZE_STRING);

    // Insert data into database
    $query = "INSERT INTO إجراءات_الدفع (name, description, amount, date) VALUES (?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("sssi", $name, $description, $amount, $date);
    $stmt->execute();

    // Redirect back to list
    header('Location: list_إجراءات-الدفع.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة إجراء دفع</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .bg-rose-500 {
            background-color: #e2789f;
        }
        .text-lavender-600 {
            color: #c7b8ea;
        }
    </style>
</head>
<body>
    <div class="container mx-auto p-4 mt-4 bg-white rounded-lg shadow-md">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">إضافة إجراء دفع</h1>
        <form id="create-form" method="post">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">اسم الإجراء</label>
                <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">وصف الإجراء</label>
                <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
            </div>
            <div class="mb-4">
                <label for="amount" class="block text-gray-700 text-sm font-bold mb-2">مبلغ الإجراء</label>
                <input type="number" id="amount" name="amount" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="date" class="block text-gray-700 text-sm font-bold mb-2">تاريخ الإجراء</label>
                <input type="date" id="date" name="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <button type="submit" id="submit-btn" class="bg-rose-500 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">إضافة</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/إجراءات-الدفع.php',
                    data: $(this).serialize(),
                    success: function(data) {
                        if (data === 'success') {
                            window.location.href = 'list_إجراءات-الدفع.php';
                        } else {
                            alert('Error: ' + data);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>

**Note:** Make sure to replace `../backend/إجراءات-الدفع.php` with the actual path to your backend script that handles the form submission. Also, adjust the database connection and query to match your actual database schema.