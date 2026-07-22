**edit_customers.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get customer ID from URL
$id = $_GET['id'];

// Fetch customer details via AJAX
$customer = json_decode(file_get_contents('../backend/customers.php?id=' . $id), true);

// Check if customer exists
if (empty($customer)) {
    echo 'Customer not found';
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-lg font-bold text-rose-500 mb-4">Edit Customer</h2>
        <form id="edit-customer-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 border border-gray-300 rounded-md" value="<?= $customer['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="block w-full p-2 mt-1 border border-gray-300 rounded-md" value="<?= $customer['email'] ?>">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                <input type="tel" id="phone" name="phone" class="block w-full p-2 mt-1 border border-gray-300 rounded-md" value="<?= $customer['phone'] ?>">
            </div>
            <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">Save Changes</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-customer-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/customers.php',
                    data: formData,
                    success: function(response) {
                        window.location.href = 'list_customers.php';
                    }
                });
            });
        });
    </script>
</body>
</html>


**customers.php (backend)**

<?php
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get customer ID from URL
$id = $_GET['id'];

// Fetch customer details from database
// Replace with your actual database query
$customer = array(
    'id' => $id,
    'name' => 'John Doe',
    'email' => 'john.doe@example.com',
    'phone' => '1234567890'
);

echo json_encode($customer);


**list_customers.php (example)**

<?php
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch customers from database
// Replace with your actual database query
$customers = array(
    array('id' => 1, 'name' => 'John Doe', 'email' => 'john.doe@example.com', 'phone' => '1234567890'),
    array('id' => 2, 'name' => 'Jane Doe', 'email' => 'jane.doe@example.com', 'phone' => '9876543210')
);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-lg font-bold text-rose-500 mb-4">Customers</h2>
        <table class="w-full">
            <thead>
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Phone</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $customer) { ?>
                <tr>
                    <td class="px-4 py-2"><?= $customer['id'] ?></td>
                    <td class="px-4 py-2"><?= $customer['name'] ?></td>
                    <td class="px-4 py-2"><?= $customer['email'] ?></td>
                    <td class="px-4 py-2"><?= $customer['phone'] ?></td>
                    <td class="px-4 py-2">
                        <a href="edit_customers.php?id=<?= $customer['id'] ?>" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">Edit</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>