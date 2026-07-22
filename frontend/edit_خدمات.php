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
$existingRecord = json_decode(file_get_contents('../backend/خدمات.php?id=' . $id), true);

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
                <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500" value="<?= $existingRecord['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:ring-rose-500 focus:border-rose-500"><?= $existingRecord['description'] ?></textarea>
            </div>
            <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">Update Service</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-service-form').submit(function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                $.ajax({
                    type: 'PUT',
                    url: '../backend/خدمات.php',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_خدمات.php';
                        } else {
                            alert('Error updating service');
                        }
                    }
                });
            });

            // Fetch existing record details via GET
            $.ajax({
                type: 'GET',
                url: '../backend/خدمات.php?id=' + <?= $id ?>,
                success: function(response) {
                    var existingRecord = JSON.parse(response);
                    $('#name').val(existingRecord.name);
                    $('#description').val(existingRecord.description);
                }
            });
        });
    </script>
</body>
</html>


**backend/خدمات.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Check if ID is valid
if (!is_numeric($id)) {
    header('Location: index.php');
    exit;
}

// Fetch existing record details
$existingRecord = array(
    'id' => $id,
    'name' => 'Service Name', // Replace with actual data
    'description' => 'Service Description' // Replace with actual data
);

// Return existing record details as JSON
echo json_encode($existingRecord);