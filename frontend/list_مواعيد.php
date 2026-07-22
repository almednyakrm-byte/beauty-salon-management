**list_مواعيد.php**

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
    <title>مواعيد</title>
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
        .header .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #666;
        }
        .header .nav-links {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
        }
        .header .nav-links li {
            margin-right: 20px;
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
            padding: 10px;
            text-align: left;
        }
        .table th {
            background-color: #f0f0f0;
        }
        .actions {
            display: flex;
            justify-content: space-between;
        }
        .actions .btn {
            margin-right: 10px;
        }
        .search-bar {
            width: 300px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }
        .search-bar:focus {
            outline: none;
            border-color: #aaa;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo">مواعيد</div>
        <ul class="nav-links">
            <li><a href="index.php">الصفحة الرئيسية</a></li>
            <li><a href="#">حسناً <?= $_SESSION['username'] ?></a></li>
            <li><a href="logout.php">تسجيل الخروج</a></li>
        </ul>
    </header>
    <main>
        <div class="container mx-auto p-4">
            <h1 class="text-3xl font-bold mb-4">قائمة مواعيد</h1>
            <div class="actions">
                <button class="btn bg-rose-500 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_مواعيد.php'">إضافة جديد</button>
                <div class="search-bar">
                    <input type="search" id="search" placeholder="بحث...">
                    <button class="btn bg-pink-600 hover:bg-pink-800 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
                </div>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>العنوان</th>
                        <th>التاريخ</th>
                        <th>الوقت</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="records">
                    <?php
                    // Fetch records from backend
                    $records = json_decode(file_get_contents('../backend/مواعيد.php'), true);
                    foreach ($records as $record) {
                        ?>
                        <tr>
                            <td><?= $record['title'] ?></td>
                            <td><?= $record['date'] ?></td>
                            <td><?= $record['time'] ?></td>
                            <td class="actions">
                                <button class="btn bg-rose-500 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='edit_مواعيد.php?id=<?= $record['id'] ?>'">تعديل</button>
                                <button class="btn bg-pink-600 hover:bg-pink-800 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(<?= $record['id'] ?>)">حذف</button>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>
    <script>
        function searchRecords() {
            const search = document.getElementById('search').value;
            fetch('../backend/مواعيد.php?search=' + search)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.title}</td>
                            <td>${record.date}</td>
                            <td>${record.time}</td>
                            <td class="actions">
                                <button class="btn bg-rose-500 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='edit_مواعيد.php?id=${record.id}'">تعديل</button>
                                <button class="btn bg-pink-600 hover:bg-pink-800 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                });
        }

        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                fetch('../backend/مواعيد.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف السجل بنجاح');
                        location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                });
            }
        }
    </script>
</body>
</html>

This code includes a premium Tailwind UI design with a specific color palette matching the theme. It also includes session validation, a table showing the list of records with actions, an "Add New Item" button, a search bar, and AJAX JavaScript code for fetching and deleting records.