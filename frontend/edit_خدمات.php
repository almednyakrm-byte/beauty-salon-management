**edit_خدمات.php**

<?php
// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$data = json_decode(file_get_contents('../backend/خدمات.php?id=' . $id), true);

// Check if record exists
if (empty($data)) {
    echo 'Record not found';
    exit;
}

// Set form data
$form_data = [
    'id' => $id,
    'name' => $data['name'],
    'description' => $data['description'],
    'price' => $data['price'],
];

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
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-rose-500 mb-4">Edit Service</h2>
        <form id="edit-service-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-700 border border-gray-300 rounded-md" value="<?= $form_data['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-gray-700 border border-gray-300 rounded-md"><?= $form_data['description'] ?></textarea>
            </div>
            <div class="mb-4">
                <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                <input type="number" id="price" name="price" class="block w-full p-2 mt-1 text-sm text-gray-700 border border-gray-300 rounded-md" value="<?= $form_data['price'] ?>">
            </div>
            <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">Save Changes</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-service-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/خدمات.php',
                    data: formData,
                    success: function(data) {
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


**Note:** Make sure to replace `list_خدمات.php` with the actual URL of the list page for services. Also, ensure that the `../backend/خدمات.php` file is properly configured to handle PUT requests and update the service record accordingly.