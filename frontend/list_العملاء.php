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
            font-family: Arial, sans-serif;
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
            color: #333;
        }
        .header .nav-links {
            float: right;
        }
        .header .nav-links a {
            margin-left: 1rem;
            color: #666;
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
        }
        .table th {
            background-color: #f7f7f7;
        }
        .table td {
            text-align: center;
        }
        .table .actions {
            text-align: center;
        }
        .table .actions a {
            margin: 0 0.5rem;
        }
        .search-bar {
            width: 100%;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 0.5rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar button[type="submit"] {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
        }
        .search-bar button[type="submit"]:hover {
            background-color: #444;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">العملاء</div>
        <div class="nav-links">
            <a href="index.php">الرئيسية</a>
            <a href="profile.php"><?= $_SESSION['username']; ?></a>
            <a href="logout.php">تسجيل الخروج</a>
        </div>
    </div>
    <div class="container mx-auto p-4">
        <div class="flex justify-between mb-4">
            <h1 class="text-2xl font-bold">قائمة العملاء</h1>
            <a href="create_العملاء.php" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">إضافة عميل جديد</a>
        </div>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="بحث...">
            <button type="submit" id="search-button">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم العميل</th>
                    <th>تليفون العميل</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <?php
                // Fetch data from backend
                $url = '../backend/العملاء.php';
                $response = file_get_contents($url);
                $data = json_decode($response, true);
                foreach ($data as $row) {
                    ?>
                    <tr>
                        <td><?= $row['name']; ?></td>
                        <td><?= $row['phone']; ?></td>
                        <td class="actions">
                            <a href="edit_العملاء.php?id=<?= $row['id']; ?>" class="bg-rose-500 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                            <button class="bg-rose-500 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded" onclick="deleteItem(<?= $row['id']; ?>)">حذف</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Search bar functionality
        const searchInput = document.getElementById('search-input');
        const searchButton = document.getElementById('search-button');
        const tableBody = document.getElementById('table-body');

        searchButton.addEventListener('click', () => {
            const searchTerm = searchInput.value.trim();
            if (searchTerm) {
                // Fetch data from backend with search query
                fetch('../backend/العملاء.php?search=' + searchTerm)
                    .then(response => response.json())
                    .then(data => {
                        tableBody.innerHTML = '';
                        data.forEach(row => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td>${row.name}</td>
                                <td>${row.phone}</td>
                                <td class="actions">
                                    <a href="edit_العملاء.php?id=${row.id}" class="bg-rose-500 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                                    <button class="bg-rose-500 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded" onclick="deleteItem(${row.id})">حذف</button>
                                </td>
                            `;
                            tableBody.appendChild(tr);
                        });
                    });
            } else {
                // Fetch all data from backend
                fetch('../backend/العملاء.php')
                    .then(response => response.json())
                    .then(data => {
                        tableBody.innerHTML = '';
                        data.forEach(row => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td>${row.name}</td>
                                <td>${row.phone}</td>
                                <td class="actions">
                                    <a href="edit_العملاء.php?id=${row.id}" class="bg-rose-500 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                                    <button class="bg-rose-500 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded" onclick="deleteItem(${row.id})">حذف</button>
                                </td>
                            `;
                            tableBody.appendChild(tr);
                        });
                    });
            }
        });

        // Delete item functionality
        function deleteItem(id) {
            if (confirm('هل أنت متأكد من حذف هذا العميل؟')) {
                fetch('../backend/العملاء.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف العميل بنجاح');
                        window.location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف العميل');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
</body>
</html>

Note: This code assumes that you have a backend script (`../backend/العملاء.php`) that handles the GET and DELETE requests for fetching and deleting customer data. The backend script should return the data in JSON format.