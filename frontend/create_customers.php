**create_customers.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once '../backend/config.php';
require_once '../backend/functions.php';

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = filter_input(INPUT_POST, 'customer_name', FILTER_SANITIZE_STRING);
    $customer_email = filter_input(INPUT_POST, 'customer_email', FILTER_SANITIZE_EMAIL);
    $customer_phone = filter_input(INPUT_POST, 'customer_phone', FILTER_SANITIZE_STRING);
    $customer_address = filter_input(INPUT_POST, 'customer_address', FILTER_SANITIZE_STRING);

    // Validate form data
    if (empty($customer_name) || empty($customer_email) || empty($customer_phone) || empty($customer_address)) {
        $error = 'Please fill in all fields.';
    } else {
        // Insert customer data into database
        $query = "INSERT INTO customers (customer_name, customer_email, customer_phone, customer_address) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->execute([$customer_name, $customer_email, $customer_phone, $customer_address]);

        // Redirect back to list page
        header('Location: list_customers.php');
        exit;
    }
}

// Display form
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .rose-500 {
            background-color: #e2789a;
        }
        .pink-600 {
            background-color: #ff69b4;
        }
    </style>
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Create Customer</h1>
        <form id="create-customer-form" method="post">
            <div class="mb-4">
                <label for="customer_name" class="block text-gray-700 text-sm font-bold mb-2">Customer Name:</label>
                <input type="text" id="customer_name" name="customer_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="John Doe">
            </div>
            <div class="mb-4">
                <label for="customer_email" class="block text-gray-700 text-sm font-bold mb-2">Customer Email:</label>
                <input type="email" id="customer_email" name="customer_email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="john.doe@example.com">
            </div>
            <div class="mb-4">
                <label for="customer_phone" class="block text-gray-700 text-sm font-bold mb-2">Customer Phone:</label>
                <input type="text" id="customer_phone" name="customer_phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="123-456-7890">
            </div>
            <div class="mb-4">
                <label for="customer_address" class="block text-gray-700 text-sm font-bold mb-2">Customer Address:</label>
                <textarea id="customer_address" name="customer_address" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="123 Main St, Anytown, USA"></textarea>
            </div>
            <button type="submit" class="bg-rose-500 hover:bg-pink-600 text-white font-bold py-2 px-4 rounded">Create Customer</button>
        </form>
    </div>

    <script>
        document.getElementById('create-customer-form').addEventListener('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            fetch('../backend/customers.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_customers.php';
                } else {
                    alert(data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>

**customers.php (backend)**

<?php
require_once '../config.php';

// Check if form data has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = filter_input(INPUT_POST, 'customer_name', FILTER_SANITIZE_STRING);
    $customer_email = filter_input(INPUT_POST, 'customer_email', FILTER_SANITIZE_EMAIL);
    $customer_phone = filter_input(INPUT_POST, 'customer_phone', FILTER_SANITIZE_STRING);
    $customer_address = filter_input(INPUT_POST, 'customer_address', FILTER_SANITIZE_STRING);

    // Validate form data
    if (empty($customer_name) || empty($customer_email) || empty($customer_phone) || empty($customer_address)) {
        echo json_encode(['success' => false, 'error' => 'Please fill in all fields.']);
    } else {
        // Insert customer data into database
        $query = "INSERT INTO customers (customer_name, customer_email, customer_phone, customer_address) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->execute([$customer_name, $customer_email, $customer_phone, $customer_address]);

        // Return success message
        echo json_encode(['success' => true]);
    }
}