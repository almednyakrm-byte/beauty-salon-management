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
            padding: 0.5rem;
            text-align: center;
        }
        .table th {
            background-color: #f0f0f0;
        }
        .table .edit {
            cursor: pointer;
            color: #666;
        }
        .table .edit:hover {
            color: #333;
        }
        .table .delete {
            cursor: pointer;
            color: #666;
        }
        .table .delete:hover {
            color: #333;
        }
        .search-bar {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 0.25rem;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 0.5rem;
            border: none;
            border-radius: 0.25rem;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            box-shadow: 0 0 0 0.25rem rgba(0, 0, 0, 0.25);
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">مواعيد</div>
        <div class="nav-links">
            <a href="index.php">الرئيسية</a>
            <a href="profile.php"><?= $_SESSION['username']; ?></a>
            <a href="logout.php">تسجيل الخروج</a>
        </div>
    </div>
    <div class="container mx-auto p-4">
        <div class="flex justify-between mb-4">
            <h2 class="text-2xl font-bold">قائمة المواعيد</h2>
            <a href="create_مواعيد.php" class="bg-rose-500 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded">إضافة جديد</a>
        </div>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="بحث...">
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>تاريخ</th>
                    <th>وقت</th>
                    <th>حالة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <?php
                // Fetch data from backend
                $url = '../backend/مواعيد.php';
                $response = file_get_contents($url);
                $data = json_decode($response, true);
                foreach ($data as $item) {
                    echo '<tr>';
                    echo '<td>' . $item['date'] . '</td>';
                    echo '<td>' . $item['time'] . '</td>';
                    echo '<td>' . $item['status'] . '</td>';
                    echo '<td>';
                    echo '<a href="edit_مواعيد.php?id=' . $item['id'] . '" class="edit">تعديل</a>';
                    echo '<button class="delete" data-id="' . $item['id'] . '">حذف</button>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Search bar functionality
        const searchInput = document.getElementById('search-input');
        searchInput.addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            const tableBody = document.getElementById('table-body');
            const rows = tableBody.getElementsByTagName('tr');
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.getElementsByTagName('td');
                let match = false;
                for (let j = 0; j < cells.length; j++) {
                    const cell = cells[j];
                    if (cell.textContent.toLowerCase().includes(searchValue)) {
                        match = true;
                        break;
                    }
                }
                if (match) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });

        // Delete functionality
        const deleteButtons = document.querySelectorAll('.delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
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
                        alert('تم حذف المواعيد بنجاح');
                        window.location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف المواعيد');
                    }
                })
                .catch(error => console.error(error));
            });
        });
    </script>
</body>
</html>

Note: This code assumes that you have a backend PHP file (`مواعيد.php`) that handles the GET and DELETE requests. The backend file should return the list of records in JSON format, and handle the DELETE request by deleting the corresponding record from the database.