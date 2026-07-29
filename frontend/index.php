<?php
session_start();

// Check if user is authenticated
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة صالون تجميل</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .glassmorphism-card {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>
<body class="bg-gray-100 h-screen">
    <div class="flex flex-col h-screen">
        <header class="bg-white shadow-md p-4">
            <div class="flex justify-between">
                <h1 class="text-2xl font-bold">نظام إدارة صالون تجميل</h1>
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل خروج</button>
            </div>
        </header>
        <main class="flex-1 p-4">
            <div class="glassmorphism-card p-4 mb-4">
                <h2 class="text-2xl font-bold text-rose-500">مرحباً</h2>
                <p class="text-gray-600">مرحباً بكم في نظام إدارة صالون تجميل</p>
            </div>
            <div class="glassmorphism-card p-4 mb-4">
                <h2 class="text-2xl font-bold text-rose-500">إحصائيات</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="bg-white shadow-md p-4 rounded">
                        <h3 class="text-lg font-bold text-pink-600">خدمات</h3>
                        <p id="services-count" class="text-gray-600"></p>
                    </div>
                    <div class="bg-white shadow-md p-4 rounded">
                        <h3 class="text-lg font-bold text-pink-600">مواعيد</h3>
                        <p id="appointments-count" class="text-gray-600"></p>
                    </div>
                    <div class="bg-white shadow-md p-4 rounded">
                        <h3 class="text-lg font-bold text-pink-600">العملاء</h3>
                        <p id="customers-count" class="text-gray-600"></p>
                    </div>
                </div>
            </div>
            <div class="glassmorphism-card p-4 mb-4">
                <h2 class="text-2xl font-bold text-rose-500">روابط سريعة</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <a href="services.php" class="bg-white shadow-md p-4 rounded hover:bg-gray-100">
                        <h3 class="text-lg font-bold text-pink-600">خدمات</h3>
                    </a>
                    <a href="appointments.php" class="bg-white shadow-md p-4 rounded hover:bg-gray-100">
                        <h3 class="text-lg font-bold text-pink-600">مواعيد</h3>
                    </a>
                    <a href="customers.php" class="bg-white shadow-md p-4 rounded hover:bg-gray-100">
                        <h3 class="text-lg font-bold text-pink-600">العملاء</h3>
                    </a>
                </div>
            </div>
        </main>
    </div>

    <script>
        fetch('/api/stats')
            .then(response => response.json())
            .then(data => {
                document.getElementById('services-count').innerText = data.services_count;
                document.getElementById('appointments-count').innerText = data.appointments_count;
                document.getElementById('customers-count').innerText = data.customers_count;
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


This code uses Tailwind CSS for styling and includes a session check to redirect to the login page if the user is not authenticated. The dashboard layout includes a welcome message, logout button, overview stats grid, and quick links to manage modules. The stats are fetched dynamically via a JavaScript API call to the backend files.

Please note that you need to replace `/api/stats` with the actual API endpoint that returns the stats data. Also, you need to create the backend files to handle the API requests and return the stats data.