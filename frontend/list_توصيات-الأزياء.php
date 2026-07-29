**list_توصيات-الأزياء.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>توصيات الأزياء</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #fff;
            padding: 1rem;
            border-bottom: 1px solid #ddd;
        }
        .header .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header .nav .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
        }
        .header .nav .user-info {
            display: flex;
            align-items: center;
        }
        .header .nav .user-info .username {
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
            margin-right: 1rem;
        }
        .header .nav .user-info .logout {
            font-size: 1rem;
            color: #666;
            cursor: pointer;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: left;
        }
        .table th {
            background-color: #f0f0f0;
        }
        .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .actions .edit {
            font-size: 1.2rem;
            color: #333;
            cursor: pointer;
        }
        .actions .delete {
            font-size: 1.2rem;
            color: #666;
            cursor: pointer;
        }
        .search {
            width: 100%;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
        }
        .search input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="nav">
            <div class="logo">توصيات الأزياء</div>
            <div class="user-info">
                <div class="username"><?= $_SESSION['username'] ?></div>
                <div class="logout" onclick="location.href='logout.php'">تسجيل خروج</div>
            </div>
        </div>
    </header>
    <main>
        <div class="container mx-auto p-4">
            <div class="flex justify-between mb-4">
                <h1 class="text-3xl font-bold">قائمة توصيات الأزياء</h1>
                <button class="bg-rose-500 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_توصيات-الأزياء.php'">إضافة جديد</button>
            </div>
            <div class="search mb-4">
                <input type="search" id="search" placeholder="بحث...">
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>اسم</th>
                        <th>وصف</th>
                        <th>حذف</th>
                    </tr>
                </thead>
                <tbody id="records">
                    <!-- Records will be loaded here -->
                </tbody>
            </table>
        </div>
    </main>
    <script>
        // Fetch API to get records
        const searchInput = document.getElementById('search');
        const recordsTable = document.getElementById('records');
        const deleteButton = document.getElementById('delete-button');

        searchInput.addEventListener('input', async () => {
            const searchQuery = searchInput.value.trim();
            const response = await fetch('../backend/توصيات-الأزياء.php', {
                method: 'GET',
                params: { search: searchQuery }
            });
            const data = await response.json();
            recordsTable.innerHTML = '';
            data.forEach(record => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${record.name}</td>
                    <td>${record.description}</td>
                    <td>
                        <button class="bg-lavender-600 hover:bg-lavender-800 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                    </td>
                `;
                recordsTable.appendChild(row);
            });
        });

        // Delete record
        async function deleteRecord(id) {
            const response = await fetch('../backend/توصيات-الأزياء.php', {
                method: 'DELETE',
                params: { id }
            });
            if (response.ok) {
                searchInput.value = '';
                searchInput.dispatchEvent(new Event('input'));
            }
        }
    </script>
</body>
</html>


**backend/توصيات-الأزياء.php**

<?php
// Database connection
$db = new PDO('sqlite:database.db');

// Search query
$searchQuery = $_GET['search'] ?? '';

// Fetch records
$stmt = $db->prepare('SELECT * FROM توصيات_الأزياء WHERE name LIKE :search OR description LIKE :search');
$stmt->bindParam(':search', '%' . $searchQuery . '%');
$stmt->execute();
$data = $stmt->fetchAll();

// Output records
header('Content-Type: application/json');
echo json_encode($data);


Note: This code assumes you have a SQLite database named `database.db` with a table named `توصيات_الأزياء` containing columns `id`, `name`, and `description`. You'll need to modify the database connection and query to match your actual database schema.