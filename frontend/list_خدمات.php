**list_خدمات.php**

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
    <title>خدمات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .header {
            background-color: #f7f7f7;
            padding: 1rem;
            border-bottom: 1px solid #ddd;
        }
        .header .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #666;
        }
        .header .nav {
            float: right;
            margin-top: 0.5rem;
        }
        .header .nav a {
            margin-left: 1rem;
            color: #666;
        }
        .header .nav a:hover {
            color: #333;
        }
        .table {
            border-collapse: collapse;
            width: 100%;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 0.5rem;
        }
        .table th {
            background-color: #f7f7f7;
        }
        .table td {
            text-align: center;
        }
        .table .edit {
            color: #666;
            cursor: pointer;
        }
        .table .delete {
            color: #666;
            cursor: pointer;
        }
        .search {
            width: 50%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 0.25rem;
        }
        .search input[type="search"] {
            width: 100%;
            padding: 0.5rem;
            border: none;
            border-radius: 0.25rem;
        }
        .search input[type="search"]:focus {
            outline: none;
            box-shadow: 0 0 0 0.25rem rgba(0, 0, 0, 0.25);
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">خدمات</div>
        <div class="nav">
            <a href="index.php">الرئيسية</a>
            <a href="profile.php"><?= $_SESSION['username']; ?></a>
            <a href="logout.php">تسجيل الخروج</a>
        </div>
    </div>
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">قائمة الخدمات</h2>
        <button class="bg-rose-500 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_خدمات.php'">إضافة خدمة جديدة</button>
        <div class="flex justify-center mb-4">
            <input type="search" class="search" placeholder="بحث...">
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم الخدمة</th>
                    <th>وصف الخدمة</th>
                    <th>حذف</th>
                    <th>تعديل</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.querySelector('.search input[type="search"]');
        const recordsTable = document.getElementById('records');

        searchInput.addEventListener('input', function() {
            const searchQuery = this.value.toLowerCase();
            const records = Array.from(recordsTable.children);
            records.forEach(record => {
                const serviceName = record.querySelector('td:first-child').textContent.toLowerCase();
                const serviceDescription = record.querySelector('td:nth-child(2)').textContent.toLowerCase();
                if (serviceName.includes(searchQuery) || serviceDescription.includes(searchQuery)) {
                    record.style.display = 'table-row';
                } else {
                    record.style.display = 'none';
                }
            });
        });

        async function loadRecords() {
            try {
                const response = await fetch('../backend/خدمات.php', { method: 'GET' });
                const data = await response.json();
                recordsTable.innerHTML = '';
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${record.service_name}</td>
                        <td>${record.service_description}</td>
                        <td class="delete" onclick="deleteRecord(${record.id})">حذف</td>
                        <td class="edit" onclick="location.href='edit_خدمات.php?id=${record.id}'">تعديل</td>
                    `;
                    recordsTable.appendChild(row);
                });
            } catch (error) {
                console.error(error);
            }
        }

        loadRecords();

        async function deleteRecord(id) {
            try {
                const response = await fetch('../backend/خدمات.php', { method: 'DELETE', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id }) });
                if (response.ok) {
                    loadRecords();
                } else {
                    console.error('Error deleting record');
                }
            } catch (error) {
                console.error(error);
            }
        }
    </script>
</body>
</html>

This code includes a premium Tailwind UI with a specific color palette matching the theme. It also includes session validation, a header navigation, a table showing list of records with actions, an 'Add New Item' button, a search bar filtering elements in real-time, and AJAX Javascript (Fetch API) fetching list records from '../backend/خدمات.php' (GET) and DELETE requests.