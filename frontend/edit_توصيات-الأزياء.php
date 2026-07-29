**edit_توصيات-الأزياء.php**

<?php
// Session validation
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$existingRecord = json_decode(file_get_contents('../backend/توصيات-الأزياء.php?id=' . $id), true);

// Check if record exists
if (empty($existingRecord)) {
    echo 'Record not found';
    exit;
}

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل توصيات الأزياء</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .bg-rose-500 {
            background-color: #ff69b4;
        }
        .text-lavender-600 {
            color: #c7b8ea;
        }
    </style>
</head>
<body>
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-lg font-bold text-gray-800 mb-4">تعديل توصيات الأزياء</h2>
        <form id="edit-form">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">العنوان</label>
                <input type="text" id="title" name="title" class="block w-full p-2 pl-10 text-sm text-gray-700 bg-gray-50 rounded-lg border border-gray-300 focus:ring-rose-500 focus:border-rose-500" value="<?= $existingRecord['title'] ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">الوصف</label>
                <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-gray-700 bg-gray-50 rounded-lg border border-gray-300 focus:ring-rose-500 focus:border-rose-500" rows="4"><?= $existingRecord['description'] ?></textarea>
            </div>
            <button type="submit" class="bg-rose-500 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded">حفظ التغييرات</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/توصيات-الأزياء.php',
                    data: formData,
                    success: function(response) {
                        window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/توصيات-الأزياء.php**

<?php
// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details from database
// Replace this with your actual database query
$existingRecord = array(
    'title' => 'توصية الأزياء',
    'description' => 'وصف التوصية'
);

// Return JSON response
header('Content-Type: application/json');
echo json_encode($existingRecord);