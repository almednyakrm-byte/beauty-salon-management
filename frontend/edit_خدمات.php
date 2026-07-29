**edit_خدمات.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$data = json_decode(file_get_contents('../backend/خدمات.php?id=' . $id), true);

// Check if record exists
if (empty($data)) {
    echo 'Record not found!';
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Service</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-rose-500 mb-4">Edit Service</h1>
        <form id="edit-service-form">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Name:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" type="text" value="<?= $data['name'] ?>">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Description:</label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="description"><?= $data['description'] ?></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="price">Price:</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="price" type="number" value="<?= $data['price'] ?>">
            </div>
            <button class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">Update Service</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-service-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/خدمات.php',
                    data: formData,
                    success: function(response) {
                        window.location.href = 'list_خدمات.php';
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr, status, error);
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/خدمات.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo 'ID not set!';
    exit;
}

// Get ID
$id = $_GET['id'];

// Connect to database
$conn = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');

// Prepare query
$stmt = $conn->prepare('SELECT * FROM services WHERE id = :id');
$stmt->bindParam(':id', $id);
$stmt->execute();

// Fetch record
$data = $stmt->fetch(PDO::FETCH_ASSOC);

// Return JSON data
echo json_encode($data);

// Close connection
$conn = null;