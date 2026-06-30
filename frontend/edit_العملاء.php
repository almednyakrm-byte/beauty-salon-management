**edit_العملاء.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details
$data = json_decode(file_get_contents('../backend/العملاء.php?id=' . $id), true);

// Check if data exists
if (empty($data)) {
    echo 'Error: Record not found';
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Client</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-lg font-bold text-rose-500 mb-4">Edit Client</h2>
        <form id="edit-client-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
                <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-gray-700 border-gray-300 rounded-md" value="<?= $data['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                <input type="email" id="email" name="email" class="block w-full p-2 pl-10 text-sm text-gray-700 border-gray-300 rounded-md" value="<?= $data['email'] ?>">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone:</label>
                <input type="tel" id="phone" name="phone" class="block w-full p-2 pl-10 text-sm text-gray-700 border-gray-300 rounded-md" value="<?= $data['phone'] ?>">
            </div>
            <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">Save Changes</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-client-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/العملاء.php',
                    data: formData,
                    success: function(response) {
                        if (response === 'success') {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                        } else {
                            alert('Error: ' + response);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/العملاء.php**

<?php
// Check if id exists
if (!isset($_GET['id'])) {
    echo 'Error: ID not found';
    exit;
}

// Connect to database
$conn = new PDO('dsn', 'username', 'password');

// Prepare query
$stmt = $conn->prepare('SELECT * FROM العملاء WHERE id = :id');
$stmt->bindParam(':id', $_GET['id']);
$stmt->execute();

// Fetch data
$data = $stmt->fetch();

// Return data as JSON
echo json_encode($data);

// Close connection
$conn = null;
?>


Note: Replace `'dsn'`, `'username'`, and `'password'` with your actual database connection details. Also, make sure to update the `list_<?= $_SESSION['mod_slug'] ?>.php` URL to match your actual list page URL.