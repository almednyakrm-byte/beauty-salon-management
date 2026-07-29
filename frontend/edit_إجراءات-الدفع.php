**edit_إجراءات-الدفع.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get record ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/إجراءات-الدفع.php?id=' . $id;
$response = json_decode(file_get_contents($url), true);

// Check if record exists
if (empty($response)) {
    echo 'Error: Record not found.';
    exit;
}

// Set form fields
$title = $response['title'];
$description = $response['description'];
$amount = $response['amount'];

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل إجراءات الدفع</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .rose-500 {
            background-color: #ff69b4;
        }
        .lavender-600 {
            background-color: #c7b8ea;
        }
    </style>
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">تعديل إجراءات الدفع</h1>
        <form id="edit-form" class="bg-white p-4 rounded shadow-md">
            <div class="mb-4">
                <label for="title" class="block text-lg font-bold mb-2">عنوان</label>
                <input type="text" id="title" name="title" class="w-full p-2 border border-gray-400 rounded" value="<?= $title ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-lg font-bold mb-2">وصف</label>
                <textarea id="description" name="description" class="w-full p-2 border border-gray-400 rounded" rows="4"><?= $description ?></textarea>
            </div>
            <div class="mb-4">
                <label for="amount" class="block text-lg font-bold mb-2">مبلغ</label>
                <input type="number" id="amount" name="amount" class="w-full p-2 border border-gray-400 rounded" value="<?= $amount ?>">
            </div>
            <button type="submit" class="bg-rose-500 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded">حفظ</button>
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
                    url: '../backend/إجراءات-الدفع.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_إجراءات-الدفع.php';
                        } else {
                            alert('Error: ' + response.message);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/إجراءات-الدفع.php**

<?php
// Check if record ID is set
if (!isset($_GET['id'])) {
    echo json_encode(array('success' => false, 'message' => 'Error: Record ID not set.'));
    exit;
}

// Get record ID
$id = $_GET['id'];

// Fetch existing record details from database
// Replace with your actual database query
$record = array(
    'title' => 'عنوان',
    'description' => 'وصف',
    'amount' => 100
);

echo json_encode($record);


Note: This code assumes you have a database setup and a `list_إجراءات-الدفع.php` page to redirect to after successful update. You'll need to modify the backend code to match your actual database schema and query.