**edit_appointments.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get appointment ID from URL
$id = $_GET['id'];

// Fetch appointment details via AJAX
$appointment = json_decode(file_get_contents('../backend/appointments.php?id=' . $id), true);

// Check if appointment exists
if (empty($appointment)) {
    echo 'Appointment not found.';
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-rose-500 mb-4">Edit Appointment</h1>
        <form id="edit-appointment-form">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" name="title" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-md" value="<?= $appointment['title'] ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-md"><?= $appointment['description'] ?></textarea>
            </div>
            <div class="mb-4">
                <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                <input type="date" id="date" name="date" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-md" value="<?= $appointment['date'] ?>">
            </div>
            <div class="mb-4">
                <label for="time" class="block text-sm font-medium text-gray-700">Time</label>
                <input type="time" id="time" name="time" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-md" value="<?= $appointment['time'] ?>">
            </div>
            <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">Save Changes</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-appointment-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/appointments.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_appointments.php';
                        } else {
                            alert('Error updating appointment.');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/appointments.php**

<?php
// Check if appointment ID is set
if (!isset($_GET['id'])) {
    echo json_encode(array('error' => 'Appointment ID not set.'));
    exit;
}

// Get appointment ID
$id = $_GET['id'];

// Connect to database
$conn = new mysqli('your_host', 'your_username', 'your_password', 'your_database');

// Check connection
if ($conn->connect_error) {
    echo json_encode(array('error' => 'Connection failed.'));
    exit;
}

// Get appointment details
$stmt = $conn->prepare("SELECT * FROM appointments WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch appointment details
$appointment = $result->fetch_assoc();

// Close connection
$conn->close();

// Output appointment details
echo json_encode($appointment);
?>


**backend/appointments_update.php**

<?php
// Check if appointment ID is set
if (!isset($_GET['id'])) {
    echo json_encode(array('error' => 'Appointment ID not set.'));
    exit;
}

// Get appointment ID
$id = $_GET['id'];

// Get appointment data
$title = $_POST['title'];
$description = $_POST['description'];
$date = $_POST['date'];
$time = $_POST['time'];

// Connect to database
$conn = new mysqli('your_host', 'your_username', 'your_password', 'your_database');

// Check connection
if ($conn->connect_error) {
    echo json_encode(array('error' => 'Connection failed.'));
    exit;
}

// Update appointment
$stmt = $conn->prepare("UPDATE appointments SET title = ?, description = ?, date = ?, time = ? WHERE id = ?");
$stmt->bind_param("sssss", $title, $description, $date, $time, $id);
$stmt->execute();

// Check if update was successful
if ($stmt->affected_rows == 1) {
    echo json_encode(array('success' => 'Appointment updated successfully.'));
} else {
    echo json_encode(array('error' => 'Error updating appointment.'));
}

// Close connection
$conn->close();
?>