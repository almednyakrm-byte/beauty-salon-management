**edit_مواعيد.php**

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
$existingRecord = json_decode(file_get_contents('../backend/مواعيد.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل مواعيد</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-lg font-bold text-rose-500 mb-4">تعديل مواعيد</h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">العنوان</label>
                <input type="text" id="title" name="title" class="block w-full p-2 pl-10 text-sm text-gray-700 bg-gray-50 rounded-md" value="<?= $existingRecord['title'] ?>">
            </div>
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700">التاريخ</label>
                <input type="date" id="date" name="date" class="block w-full p-2 pl-10 text-sm text-gray-700 bg-gray-50 rounded-md" value="<?= $existingRecord['date'] ?>">
            </div>
            <div>
                <label for="time" class="block text-sm font-medium text-gray-700">الوقت</label>
                <input type="time" id="time" name="time" class="block w-full p-2 pl-10 text-sm text-gray-700 bg-gray-50 rounded-md" value="<?= $existingRecord['time'] ?>">
            </div>
            <button type="submit" class="bg-rose-500 hover:bg-rose-600 text-white font-bold py-2 px-4 rounded-md">تعديل</button>
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
                    url: '../backend/مواعيد.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_مواعيد.php';
                        } else {
                            alert(response.message);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/مواعيد.php**

<?php
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$existingRecord = array(
    'title' => 'عنوان المواعيد',
    'date' => '2022-01-01',
    'time' => '10:00'
);

// Update record
if (isset($_POST['title']) && isset($_POST['date']) && isset($_POST['time'])) {
    $title = $_POST['title'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    // Update record in database (replace with your actual database code)
    // ...

    echo json_encode(array('success' => true, 'message' => 'تم التعديل بنجاح'));
} else {
    echo json_encode(array('success' => false, 'message' => 'حدث خطأ'));
}