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
    <div class="flex justify-between items-center p-4 bg-white">
        <h1 class="text-2xl font-bold text-rose-500">نظام إدارة صالون تجميل</h1>
        <button class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل خروج</button>
    </div>
    <div class="flex justify-center items-center p-4 bg-white">
        <div class="glassmorphism w-1/2 p-4">
            <h2 class="text-2xl font-bold text-rose-500">مرحباً بكم في نظام إدارة صالون تجميل</h2>
            <div class="flex justify-between items-center p-4">
                <div class="w-1/2">
                    <h3 class="text-lg font-bold text-rose-500">إحصائيات</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white rounded-lg shadow-md p-4">
                            <h4 class="text-lg font-bold text-rose-500">مواعيد</h4>
                            <p id="appointments-count" class="text-lg text-gray-600"></p>
                        </div>
                        <div class="bg-white rounded-lg shadow-md p-4">
                            <h4 class="text-lg font-bold text-rose-500">خدمات</h4>
                            <p id="services-count" class="text-lg text-gray-600"></p>
                        </div>
                        <div class="bg-white rounded-lg shadow-md p-4">
                            <h4 class="text-lg font-bold text-rose-500">العملاء</h4>
                            <p id="customers-count" class="text-lg text-gray-600"></p>
                        </div>
                    </div>
                </div>
                <div class="w-1/2">
                    <h3 class="text-lg font-bold text-rose-500">روابط سريعة</h3>
                    <ul class="list-none p-0">
                        <li class="py-2">
                            <a href="appointments.php" class="text-lg text-gray-600 hover:text-rose-500">مواعيد</a>
                        </li>
                        <li class="py-2">
                            <a href="services.php" class="text-lg text-gray-600 hover:text-rose-500">خدمات</a>
                        </li>
                        <li class="py-2">
                            <a href="customers.php" class="text-lg text-gray-600 hover:text-rose-500">العملاء</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        fetch('api/stats.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('appointments-count').textContent = data.appointments_count;
                document.getElementById('services-count').textContent = data.services_count;
                document.getElementById('customers-count').textContent = data.customers_count;
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


This code uses Tailwind CSS for styling and includes a session check to redirect to the login page if the user is not authenticated. It also uses a glassmorphism effect for the dashboard layout and includes a quick links section with links to manage appointments, services, and customers. The stats are fetched dynamically via a JavaScript API call to `api/stats.php`.

Please note that you need to create a `api/stats.php` file to handle the API request and return the stats data in JSON format. You also need to create the necessary PHP files for the login, logout, appointments, services, and customers pages.

Here's an example of what the `api/stats.php` file might look like:

<?php
// Connect to the database
$conn = mysqli_connect("localhost", "username", "password", "database");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query the database for stats
$query = "SELECT COUNT(*) as appointments_count, COUNT(*) as services_count, COUNT(*) as customers_count FROM appointments, services, customers";
$result = mysqli_query($conn, $query);

// Fetch the stats data
$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data = $row;
}

// Close the database connection
mysqli_close($conn);

// Return the stats data in JSON format
echo json_encode($data);
?>

This is just an example and you should adjust the code to fit your specific database schema and requirements.