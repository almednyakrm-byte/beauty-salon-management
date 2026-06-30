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
        .glassmorphism {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold text-rose-500">نظام إدارة صالون تجميل</h1>
            <button class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل خروج</button>
        </div>
        <div class="glassmorphism bg-white p-4 mb-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">مرحباً <?= $_SESSION['username'] ?></h2>
            <p class="text-gray-600">من هنا يمكنك إدارة صالونك</p>
        </div>
        <div class="glassmorphism bg-white p-4 mb-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">إحصائيات</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">خدمات</h3>
                    <p class="text-gray-600" id="services-count"></p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">مواعيد</h3>
                    <p class="text-gray-600" id="appointments-count"></p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">العملاء</h3>
                    <p class="text-gray-600" id="customers-count"></p>
                </div>
            </div>
        </div>
        <div class="glassmorphism bg-white p-4 mb-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">إدارة</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <button class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='services.php'">خدمات</button>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <button class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='appointments.php'">مواعيد</button>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <button class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='customers.php'">العملاء</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        fetch('/api/stats')
            .then(response => response.json())
            .then(data => {
                document.getElementById('services-count').textContent = data.services_count;
                document.getElementById('appointments-count').textContent = data.appointments_count;
                document.getElementById('customers-count').textContent = data.customers_count;
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


This code uses Tailwind CSS for styling and makes API calls to fetch stats dynamically. The dashboard layout includes a welcome message, logout button, overview stats grid, and quick links to manage modules. The stats grid fetches data from the backend API and displays it on the page.