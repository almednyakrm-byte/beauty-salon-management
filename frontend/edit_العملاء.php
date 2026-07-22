**edit_العملاء.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details
$url = '../backend/العملاء.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if record exists
if (empty($data)) {
    echo 'Record not found';
    exit;
}

// Set form data
$form_data = $data;
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل عميل</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4 mt-12">
        <h1 class="text-3xl font-bold text-rose-500">تعديل عميل</h1>
        <form id="edit-form" class="bg-white p-4 mt-4 rounded-lg shadow-md">
            <div class="grid grid-cols-1 gap-4">
                <div class="col-span-1">
                    <label for="name" class="block text-sm font-medium text-gray-700">اسم العميل:</label>
                    <input type="text" id="name" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-600 focus:ring-pink-600" value="<?= $form_data['name'] ?>">
                </div>
                <div class="col-span-1">
                    <label for="email" class="block text-sm font-medium text-gray-700">البريد الإلكتروني:</label>
                    <input type="email" id="email" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-600 focus:ring-pink-600" value="<?= $form_data['email'] ?>">
                </div>
                <div class="col-span-1">
                    <label for="phone" class="block text-sm font-medium text-gray-700">رقم الهاتف:</label>
                    <input type="tel" id="phone" name="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-600 focus:ring-pink-600" value="<?= $form_data['phone'] ?>">
                </div>
                <div class="col-span-1">
                    <label for="address" class="block text-sm font-medium text-gray-700">العنوان:</label>
                    <textarea id="address" name="address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-600 focus:ring-pink-600"><?= $form_data['address'] ?></textarea>
                </div>
            </div>
            <button type="submit" class="bg-rose-500 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded">حفظ</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: 'PUT',
                    url: '../backend/العملاء.php',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/العملاء.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    echo 'Invalid request';
    exit;
}

// Get id
$id = $_GET['id'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get existing record details
$sql = "SELECT * FROM العملاء WHERE id = '$id'";
$result = $conn->query($sql);

// Check if record exists
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo 'Record not found';
}

// Close connection
$conn->close();
?>