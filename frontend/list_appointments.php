**list_appointments.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .rose-500 {
            color: #e83e8c;
        }
        .pink-600 {
            color: #ff69b4;
        }
    </style>
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow-md p-4">
        <nav class="flex justify-between items-center">
            <a href="index.php" class="text-lg font-bold">Home</a>
            <div class="flex items-center">
                <p class="mr-2">Welcome, <?php echo $_SESSION['username']; ?></p>
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">Logout</button>
            </div>
        </nav>
    </header>
    <main class="max-w-7xl mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Appointments</h1>
        <div class="flex justify-between items-center mb-4">
            <button class="bg-rose-500 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_appointments.php'">Add New Item</button>
            <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700 focus:outline-none focus:ring-rose-500 focus:border-rose-500" placeholder="Search...">
        </div>
        <table class="w-full border-collapse border border-gray-400">
            <thead>
                <tr>
                    <th class="border border-gray-400 p-2">ID</th>
                    <th class="border border-gray-400 p-2">Name</th>
                    <th class="border border-gray-400 p-2">Date</th>
                    <th class="border border-gray-400 p-2">Actions</th>
                </tr>
            </thead>
            <tbody id="appointments-list">
                <?php
                // Fetch appointments list from backend
                $response = file_get_contents('../backend/appointments.php');
                $appointments = json_decode($response, true);
                foreach ($appointments as $appointment) {
                    ?>
                    <tr>
                        <td class="border border-gray-400 p-2"><?php echo $appointment['id']; ?></td>
                        <td class="border border-gray-400 p-2"><?php echo $appointment['name']; ?></td>
                        <td class="border border-gray-400 p-2"><?php echo $appointment['date']; ?></td>
                        <td class="border border-gray-400 p-2">
                            <a href="edit_appointments.php?id=<?php echo $appointment['id']; ?>" class="text-pink-600 hover:text-pink-800">Edit</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteAppointment(<?php echo $appointment['id']; ?>)">Delete</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </main>

    <script>
        // Search bar filtering
        const searchInput = document.getElementById('search');
        const appointmentsList = document.getElementById('appointments-list');
        searchInput.addEventListener('input', () => {
            const searchQuery = searchInput.value.toLowerCase();
            const appointments = appointmentsList.children;
            for (let i = 0; i < appointments.length; i++) {
                const appointment = appointments[i];
                const name = appointment.children[1].textContent.toLowerCase();
                const date = appointment.children[2].textContent.toLowerCase();
                if (name.includes(searchQuery) || date.includes(searchQuery)) {
                    appointment.style.display = 'table-row';
                } else {
                    appointment.style.display = 'none';
                }
            }
        });

        // Delete appointment via AJAX
        function deleteAppointment(id) {
            fetch('../backend/appointments.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Appointment deleted successfully!');
                    window.location.reload();
                } else {
                    alert('Error deleting appointment!');
                }
            })
            .catch(error => console.error(error));
        }
    </script>
</body>
</html>

**Note:** This code assumes that you have a backend PHP script (`appointments.php`) that returns a JSON array of appointments. The `deleteAppointment` function sends a DELETE request to the backend to delete the appointment with the specified ID.