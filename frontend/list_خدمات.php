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
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #fff;
            padding: 1rem;
            border-bottom: 1px solid #ddd;
        }
        .header .nav-link {
            color: #666;
            text-decoration: none;
        }
        .header .nav-link:hover {
            color: #333;
        }
        .table {
            border-collapse: collapse;
            width: 100%;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
        }
        .table th {
            background-color: #f0f0f0;
        }
        .search-bar {
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            box-shadow: 0 0 0 0.25rem rgba(0, 0, 0, 0.25);
        }
    </style>
</head>
<body>
    <div class="header">
        <nav class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="nav-link">الرئيسية</a>
            <div class="flex items-center">
                <span class="text-lg font-bold text-rose-500"><?= $_SESSION['username'] ?></span>
                <button class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل الخروج</button>
            </div>
        </nav>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-rose-500">خدمات</h1>
        <button class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_خدمات.php'">إضافة خدمة جديدة</button>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="بحث...">
            <button class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
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
            <tbody id="records-table">
                <?php
                // Fetch records from backend
                $records = json_decode(file_get_contents('../backend/خدمات.php'), true);
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td><?= $record['name'] ?></td>
                        <td><?= $record['description'] ?></td>
                        <td>
                            <button class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(<?= $record['id'] ?>)">حذف</button>
                        </td>
                        <td>
                            <button class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='edit_خدمات.php?id=<?= $record['id'] ?>'">تعديل</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchRecords() {
            const searchInput = document.getElementById('search-input');
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/خدمات.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(data => {
                        const recordsTable = document.getElementById('records-table');
                        recordsTable.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.name}</td>
                                <td>${record.description}</td>
                                <td>
                                    <button class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                                <td>
                                    <button class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='edit_خدمات.php?id=${record.id}'">تعديل</button>
                                </td>
                            `;
                            recordsTable.appendChild(row);
                        });
                    });
            } else {
                fetch('../backend/خدمات.php')
                    .then(response => response.json())
                    .then(data => {
                        const recordsTable = document.getElementById('records-table');
                        recordsTable.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.name}</td>
                                <td>${record.description}</td>
                                <td>
                                    <button class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                                <td>
                                    <button class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='edit_خدمات.php?id=${record.id}'">تعديل</button>
                                </td>
                            `;
                            recordsTable.appendChild(row);
                        });
                    });
            }
        }

        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذه الخدمة؟')) {
                fetch('../backend/خدمات.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف الخدمة بنجاح');
                        searchRecords();
                    } else {
                        alert('حدث خطأ أثناء حذف الخدمة');
                    }
                });
            }
        }
    </script>
</body>
</html>

This code includes a premium Tailwind UI design, session validation, and a list of services with actions to edit and delete. The search bar filters the list of services in real-time, and the delete action uses an AJAX call to the backend to delete the service.