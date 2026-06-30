**create_مواعيد.php**

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
    // Sanitize input data
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $date = filter_var($_POST['date'], FILTER_SANITIZE_STRING);
    $time = filter_var($_POST['time'], FILTER_SANITIZE_STRING);

    // Insert data into database
    $query = "INSERT INTO مواعيد (name, description, date, time) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $name, $description, $date, $time);
    $stmt->execute();

    // Redirect back to list page
    header('Location: list_مواعيد.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة موعد</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .rose-500 {
            background-color: #ff69b4;
        }
        .pink-600 {
            background-color: #ff99cc;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-gray-700 mb-4">إضافة موعد</h2>
        <form id="create-muaad-form" class="space-y-4">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="name">اسم الموعد</label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="name" type="text" name="name" required>
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="description">وصف الموعد</label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="description" type="text" name="description" required>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="date">تاريخ الموعد</label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="date" type="date" name="date" required>
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="time">ساعة الموعد</label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="time" type="time" name="time" required>
                </div>
            </div>
            <button type="submit" class="bg-rose-500 hover:bg-pink-600 text-white font-bold py-2 px-4 rounded">إضافة</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-muaad-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/مواعيد.php',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response === 'success') {
                            window.location.href = 'list_مواعيد.php';
                        } else {
                            alert('Error adding record');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>

**مواعيد.php (backend file)**

<?php
// Include database connection
require_once '../config/database.php';

// Check if form data has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $date = filter_var($_POST['date'], FILTER_SANITIZE_STRING);
    $time = filter_var($_POST['time'], FILTER_SANITIZE_STRING);

    // Insert data into database
    $query = "INSERT INTO مواعيد (name, description, date, time) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $name, $description, $date, $time);
    $stmt->execute();

    // Return success message
    echo 'success';
    exit;
}
?>