**list_دفاتر.php**

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
    <title>دفاتر</title>
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
        .header .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #666;
        }
        .header .nav-links {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header .nav-links a {
            margin: 0 1rem;
            color: #666;
        }
        .header .nav-links a:hover {
            color: #333;
        }
        .header .user-info {
            display: flex;
            align-items: center;
        }
        .header .user-info img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 1rem;
        }
        .header .user-info span {
            font-size: 1rem;
            font-weight: bold;
            color: #666;
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
        .table td {
            cursor: pointer;
        }
        .table .edit {
            color: #666;
        }
        .table .delete {
            color: #666;
            cursor: pointer;
        }
        .search-bar {
            width: 100%;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 0.25rem;
        }
        .search-bar input {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.25rem;
        }
        .search-bar input:focus {
            outline: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">دفاتر</div>
        <div class="nav-links">
            <a href="index.php">الصفحة الرئيسية</a>
            <a href="logout.php">تسجيل الخروج</a>
            <div class="user-info">
                <img src="profile-picture.jpg" alt="User Picture">
                <span><?= $_SESSION['username'] ?></span>
            </div>
        </div>
    </div>
    <div class="container mx-auto p-4">
        <div class="flex justify-between mb-4">
            <h1 class="text-2xl font-bold">قائمة دفاتر</h1>
            <a href="create_دفاتر.php" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">اضافة جديد</a>
        </div>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="بحث...">
            <button class="bg-rose-500 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded" id="search-btn">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم الدفتر</th>
                    <th>تاريخ الإنشاء</th>
                    <th>تاريخ التعديل</th>
                    <th>حذف</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Table records will be populated here -->
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.getElementById('search-input');
        const searchBtn = document.getElementById('search-btn');
        const tableBody = document.getElementById('table-body');

        searchBtn.addEventListener('click', () => {
            const searchTerm = searchInput.value.trim();
            if (searchTerm) {
                fetch('../backend/دفاتر.php?search=' + searchTerm)
                    .then(response => response.json())
                    .then(data => {
                        tableBody.innerHTML = '';
                        data.forEach(item => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${item.name}</td>
                                <td>${item.created_at}</td>
                                <td>${item.updated_at}</td>
                                <td class="delete" data-id="${item.id}">حذف</td>
                            `;
                            tableBody.appendChild(row);
                        });
                    })
                    .catch(error => console.error(error));
            } else {
                fetch('../backend/دفاتر.php')
                    .then(response => response.json())
                    .then(data => {
                        tableBody.innerHTML = '';
                        data.forEach(item => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${item.name}</td>
                                <td>${item.created_at}</td>
                                <td>${item.updated_at}</td>
                                <td class="edit" data-id="${item.id}">تعديل</td>
                                <td class="delete" data-id="${item.id}">حذف</td>
                            `;
                            tableBody.appendChild(row);
                        });
                    })
                    .catch(error => console.error(error));
            }
        });

        tableBody.addEventListener('click', event => {
            if (event.target.classList.contains('delete')) {
                const id = event.target.dataset.id;
                if (confirm('هل تريد حذف هذا الدفتر؟')) {
                    fetch('../backend/دفاتر.php', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ id })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('تم حذف الدفتر بنجاح');
                            window.location.reload();
                        } else {
                            alert('حدث خطأ أثناء حذف الدفتر');
                        }
                    })
                    .catch(error => console.error(error));
                }
            } else if (event.target.classList.contains('edit')) {
                const id = event.target.dataset.id;
                window.location.href = 'edit_دفاتر.php?id=' + id;
            }
        });
    </script>
</body>
</html>

**backend/دفاتر.php**

<?php
// Database connection
$conn = mysqli_connect('localhost', 'username', 'password', 'database');

if (!$conn) {
    die('Could not connect: ' . mysqli_error($conn));
}

// Search query
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $query = "SELECT * FROM دفاتر WHERE name LIKE '%$searchTerm%'";
} else {
    $query = "SELECT * FROM دفاتر";
}

$result = mysqli_query($conn, $query);

$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);

mysqli_close($conn);
?>

Note: This is a basic implementation and you should adapt it to your specific needs. Also, make sure to replace the placeholders with your actual database credentials and table name.