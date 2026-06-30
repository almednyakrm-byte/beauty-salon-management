**edit_مواعيد.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details
$url = '../backend/مواعيد.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit مواعيد</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-lg font-bold text-rose-500 mb-4">Edit مواعيد</h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" name="title" value="<?= $data['title'] ?>" class="block w-full p-2 pl-10 text-sm text-gray-700 bg-gray-50 rounded-lg border border-gray-300 focus:ring-rose-500 focus:border-rose-500">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-gray-700 bg-gray-50 rounded-lg border border-gray-300 focus:ring-rose-500 focus:border-rose-500" rows="4"><?= $data['description'] ?></textarea>
            </div>
            <button type="submit" class="bg-rose-500 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded">Save Changes</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
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
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error: ' + error);
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/مواعيد.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(array('success' => false, 'message' => 'Invalid id'));
    exit;
}

// Get id
$id = $_GET['id'];

// Fetch existing record details
$query = "SELECT * FROM مواعيد WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

// Prepare data for JSON response
$data = array(
    'title' => $row['title'],
    'description' => $row['description']
);

// Output JSON response
echo json_encode(array('success' => true, 'data' => $data));
?>


**backend/edit_مواعيد.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(array('success' => false, 'message' => 'Invalid id'));
    exit;
}

// Get id
$id = $_GET['id'];

// Get data from request
$title = $_POST['title'];
$description = $_POST['description'];

// Update record
$query = "UPDATE مواعيد SET title = '$title', description = '$description' WHERE id = '$id'";
mysqli_query($conn, $query);

// Output JSON response
echo json_encode(array('success' => true));
?>