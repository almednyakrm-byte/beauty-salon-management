**list_services.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .bg-rose-500 {
            background-color: #e83e8c;
        }
        .text-pink-600 {
            color: #ff69b4;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto p-4">
        <header class="bg-rose-500 p-4 text-white">
            <nav class="flex justify-between">
                <a href="index.php" class="text-pink-600 hover:text-white">Back to Index</a>
                <div class="flex items-center">
                    <span class="text-pink-600">Welcome, <?= $_SESSION['username'] ?></span>
                    <button class="ml-4 bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">Logout</button>
                </div>
            </nav>
        </header>
        <main class="p-4">
            <h1 class="text-3xl text-pink-600">Services</h1>
            <button class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_services.php'">Add New Item</button>
            <div class="mt-4">
                <input type="search" id="search" class="w-full p-2 mb-2 border border-gray-400 rounded" placeholder="Search...">
            </div>
            <table class="w-full border-collapse border border-gray-400">
                <thead>
                    <tr>
                        <th class="p-2 border border-gray-400">ID</th>
                        <th class="p-2 border border-gray-400">Name</th>
                        <th class="p-2 border border-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody id="services-table">
                </tbody>
            </table>
        </main>
    </div>

    <script>
        const searchInput = document.getElementById('search');
        const tableBody = document.getElementById('services-table');

        searchInput.addEventListener('input', () => {
            const searchQuery = searchInput.value.toLowerCase();
            const services = document.querySelectorAll('#services-table tr');
            services.forEach((service) => {
                const serviceName = service.querySelector('td:nth-child(2)').textContent.toLowerCase();
                if (serviceName.includes(searchQuery)) {
                    service.style.display = 'table-row';
                } else {
                    service.style.display = 'none';
                }
            });
        });

        async function fetchServices() {
            try {
                const response = await fetch('../backend/services.php');
                const services = await response.json();
                const tableHtml = services.map((service) => {
                    return `
                        <tr>
                            <td class="p-2 border border-gray-400">${service.id}</td>
                            <td class="p-2 border border-gray-400">${service.name}</td>
                            <td class="p-2 border border-gray-400">
                                <a href="edit_services.php?id=${service.id}" class="text-pink-600 hover:text-white">Edit</a>
                                <button class="ml-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteService(${service.id})">Delete</button>
                            </td>
                        </tr>
                    `;
                }).join('');
                tableBody.innerHTML = tableHtml;
            } catch (error) {
                console.error(error);
            }
        }

        async function deleteService(id) {
            try {
                const response = await fetch('../backend/services.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id })
                });
                if (response.ok) {
                    fetchServices();
                } else {
                    console.error('Error deleting service');
                }
            } catch (error) {
                console.error(error);
            }
        }

        fetchServices();
    </script>
</body>
</html>


**services.php (backend)**

<?php
// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch services
$query = "SELECT * FROM services";
$result = $conn->query($query);

$services = array();
while ($row = $result->fetch_assoc()) {
    $services[] = array(
        'id' => $row['id'],
        'name' => $row['name']
    );
}

// Output services as JSON
echo json_encode($services);

// Close connection
$conn->close();
?>


**create_services.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Service</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto p-4">
        <header class="bg-rose-500 p-4 text-white">
            <nav class="flex justify-between">
                <a href="index.php" class="text-pink-600 hover:text-white">Back to Index</a>
                <div class="flex items-center">
                    <span class="text-pink-600">Welcome, <?= $_SESSION['username'] ?></span>
                    <button class="ml-4 bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">Logout</button>
                </div>
            </nav>
        </header>
        <main class="p-4">
            <h1 class="text-3xl text-pink-600">Create Service</h1>
            <form action="../backend/create_service.php" method="post">
                <div class="mt-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                    <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Service Name">
                </div>
                <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">Create Service</button>
            </form>
        </main>
    </div>
</body>
</html>


**edit_services.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get service ID
$id = $_GET['id'];

// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch service
$query = "SELECT * FROM services WHERE id = '$id'";
$result = $conn->query($query);

$service = array();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $service = array(
        'id' => $row['id'],
        'name' => $row['name']
    );
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Service</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto p-4">
        <header class="bg-rose-500 p-4 text-white">
            <nav class="flex justify-between">
                <a href="index.php" class="text-pink-600 hover:text-white">Back to Index</a>
                <div class="flex items-center">
                    <span class="text-pink-600">Welcome, <?= $_SESSION['username'] ?></span>
                    <button class="ml-4 bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">Logout</button>
                </div>
            </nav>
        </header>
        <main class