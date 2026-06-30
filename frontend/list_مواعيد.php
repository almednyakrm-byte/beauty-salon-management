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
            text-decoration: none;
        }
        .header .nav a:hover {
            color: #999;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 0.5rem;
            text-align: left;
        }
        .table th {
            background-color: #f7f7f7;
        }
        .table td {
            cursor: pointer;
        }
        .table .edit {
            color: #666;
            text-decoration: none;
        }
        .table .edit:hover {
            color: #999;
        }
        .table .delete {
            color: #666;
            text-decoration: none;
        }
        .table .delete:hover {
            color: #999;
        }
        .search {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 0.25rem;
        }
        .search input[type="text"] {
            width: 100%;
            padding: 0.5rem;
            border: none;
            border-radius: 0.25rem;
        }
        .search button[type="submit"] {
            background-color: #f7f7f7;
            border: none;
            padding: 0.5rem;
            border-radius: 0.25rem;
            cursor: pointer;
        }
        .search button[type="submit"]:hover {
            background-color: #f7f7f7;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">مواعيد</div>
        <div class="nav">
            <a href="index.php">الرئيسية</a>
            <a href="profile.php">الملف الشخصي</a>
            <a href="logout.php">تسجيل الخروج</a>
        </div>
    </div>
    <div class="container mx-auto p-4">
        <div class="flex justify-between mb-4">
            <h2 class="text-lg font-bold">قائمة المواعيد</h2>
            <button class="bg-rose-500 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_مواعيد.php'">إضافة جديد</button>
        </div>
        <div class="search mb-4">
            <input type="text" id="search" placeholder="بحث...">
            <button type="submit" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم المواعيد</th>
                    <th>تاريخ المواعيد</th>
                    <th>حالة المواعيد</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $records = fetchRecords();
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td><?php echo $record['name']; ?></td>
                        <td><?php echo $record['date']; ?></td>
                        <td><?php echo $record['status']; ?></td>
                        <td>
                            <a href="edit_مواعيد.php?id=<?php echo $record['id']; ?>" class="edit">تعديل</a>
                            <button class="delete" onclick="deleteRecord(<?php echo $record['id']; ?>)">حذف</button>
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
            const search = document.getElementById('search').value;
            fetch('../backend/مواعيد.php?search=' + search)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.name}</td>
                            <td>${record.date}</td>
                            <td>${record.status}</td>
                            <td>
                                <a href="edit_مواعيد.php?id=${record.id}" class="edit">تعديل</a>
                                <button class="delete" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                })
                .catch(error => console.error(error));
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا المواعيد؟')) {
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
            }
        }

        function fetchRecords() {
            return fetch('../backend/مواعيد.php')
                .then(response => response.json())
                .then(data => data.records)
                .catch(error => console.error(error));
        }
    </script>
</body>
</html>


**backend/مواعيد.php**

<?php
// Fetch records from database
$records = array();
$records[] = array(
    'id' => 1,
    'name' => 'مواعيد 1',
    'date' => '2022-01-01',
    'status' => 'مفعل'
);
$records[] = array(
    'id' => 2,
    'name' => 'مواعيد 2',
    'date' => '2022-01-02',
    'status' => 'مفعل'
);
$records[] = array(
    'id' => 3,
    'name' => 'مواعيد 3',
    'date' => '2022-01-03',
    'status' => 'مفعل'
);

// Search records
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $records = array_filter($records, function($record) use ($search) {
        return strpos($record['name'], $search) !== false || strpos($record['date'], $search) !== false || strpos($record['status'], $search) !== false;
    });
}

// Delete record
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_POST['id'];
    // Delete record from database
    // ...
    echo json_encode(array('success' => true));
}

// Output records
echo json_encode(array('records' => $records));
?>