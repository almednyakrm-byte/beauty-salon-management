**list_العملاء.php**

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
    <title>العملاء</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .header {
            background-color: #f7f7f7;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #666;
        }
        .header .nav-links {
            display: flex;
            gap: 1rem;
        }
        .header .nav-links a {
            color: #666;
            text-decoration: none;
        }
        .header .nav-links a:hover {
            color: #333;
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
            background-color: #f7f7f7;
        }
        .actions {
            display: flex;
            gap: 1rem;
        }
        .actions a {
            text-decoration: none;
            color: #666;
        }
        .actions a:hover {
            color: #333;
        }
        .search-bar {
            width: 100%;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
            background-color: #f7f7f7;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
            background-color: #f7f7f7;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            box-shadow: 0 0 0 0.25rem rgba(13, 30, 41, 0.25);
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">العملاء</div>
        <div class="nav-links">
            <a href="index.php">الرئيسية</a>
            <a href="profile.php">الملف الشخصي</a>
            <a href="logout.php">تسجيل الخروج</a>
            <span><?= $_SESSION['username'] ?></span>
        </div>
    </div>
    <div class="container mx-auto p-4">
        <div class="flex justify-between mb-4">
            <h1 class="text-2xl font-bold">قائمة العملاء</h1>
            <a href="create_العملاء.php" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">إضافة جديد</a>
        </div>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم العميل</th>
                    <th>تليفون العميل</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.getElementById('search');
        const recordsContainer = document.getElementById('records');

        searchInput.addEventListener('input', async () => {
            const searchQuery = searchInput.value.trim();
            const response = await fetch('../backend/العملاء.php', {
                method: 'GET',
                params: { search: searchQuery }
            });
            const data = await response.json();
            recordsContainer.innerHTML = '';
            data.forEach(record => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${record.اسم_العميل}</td>
                    <td>${record.تليفون_العميل}</td>
                    <td class="actions">
                        <a href="edit_العملاء.php?id=${record.id}" class="text-sm">تعديل</a>
                        <button class="text-sm" onclick="deleteRecord(${record.id})">حذف</button>
                    </td>
                `;
                recordsContainer.appendChild(row);
            });
        });

        async function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                const response = await fetch('../backend/العملاء.php', {
                    method: 'DELETE',
                    params: { id }
                });
                if (response.ok) {
                    alert('تم حذف السجل بنجاح');
                    window.location.reload();
                } else {
                    alert('حدث خطأ أثناء حذف السجل');
                }
            }
        }
    </script>
</body>
</html>


**backend/العملاء.php**

<?php
// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Search query
$searchQuery = $_GET['search'] ?? '';

// SQL query
$sql = "SELECT * FROM العملاء";
if ($searchQuery) {
    $sql .= " WHERE اسم_العميل LIKE '%$searchQuery%' OR تليفون_العميل LIKE '%$searchQuery%'";
}

// Execute query
$result = $conn->query($sql);

// Fetch data
$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// JSON encode data
echo json_encode($data);

// Close connection
$conn->close();
?>


Note: This is a basic example and you should adjust it according to your needs and database schema. Also, make sure to replace the placeholders with your actual database credentials and table name.