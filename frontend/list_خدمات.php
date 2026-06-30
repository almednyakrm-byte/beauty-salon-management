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
        .rose-500 {
            background-color: #e83e8c;
        }
        .pink-600 {
            background-color: #ff69b4;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <header class="bg-rose-500 p-4 mb-4">
            <nav class="flex justify-between">
                <a href="index.php" class="text-white">الرئيسية</a>
                <div class="flex items-center">
                    <span class="text-white mr-2">مرحباً, <?php echo $_SESSION['username']; ?></span>
                    <a href="logout.php" class="text-white hover:text-pink-600">تسجيل الخروج</a>
                </div>
            </nav>
        </header>
        <main class="bg-white p-4 rounded shadow-md">
            <h2 class="text-2xl font-bold mb-4">قائمة خدمات</h2>
            <button class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_خدمات.php'">إضافة خدمة جديدة</button>
            <div class="flex justify-between mb-4">
                <input type="search" class="w-full p-2 rounded" placeholder="بحث" id="search-input">
                <button class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
            </div>
            <table class="w-full border-collapse border border-gray-400">
                <thead>
                    <tr>
                        <th class="border border-gray-400 p-2">اسم الخدمة</th>
                        <th class="border border-gray-400 p-2">حذف</th>
                        <th class="border border-gray-400 p-2">تعديل</th>
                    </tr>
                </thead>
                <tbody id="records-table">
                    <!-- Records will be loaded here -->
                </tbody>
            </table>
        </main>
    </div>

    <script>
        // Fetch API to load records
        async function loadRecords() {
            try {
                const response = await fetch('../backend/خدمات.php');
                const data = await response.json();
                const recordsTable = document.getElementById('records-table');
                recordsTable.innerHTML = '';
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="border border-gray-400 p-2">${record.name}</td>
                        <td class="border border-gray-400 p-2">
                            <button class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                        <td class="border border-gray-400 p-2">
                            <a href="edit_خدمات.php?id=${record.id}" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                        </td>
                    `;
                    recordsTable.appendChild(row);
                });
            } catch (error) {
                console.error(error);
            }
        }

        // Search records
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
                                <td class="border border-gray-400 p-2">${record.name}</td>
                                <td class="border border-gray-400 p-2">
                                    <button class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                                <td class="border border-gray-400 p-2">
                                    <a href="edit_خدمات.php?id=${record.id}" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                                </td>
                            `;
                            recordsTable.appendChild(row);
                        });
                    })
                    .catch(error => console.error(error));
            } else {
                loadRecords();
            }
        }

        // Delete record
        async function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف الخدمة؟')) {
                try {
                    const response = await fetch('../backend/خدمات.php', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ id })
                    });
                    if (response.ok) {
                        loadRecords();
                    } else {
                        alert('حدث خطأ أثناء حذف الخدمة');
                    }
                } catch (error) {
                    console.error(error);
                }
            }
        }

        // Load records on page load
        loadRecords();
    </script>
</body>
</html>

This code uses the Fetch API to load records from the backend and delete records using AJAX requests. It also includes a search bar that filters records in real-time. The UI is built using Tailwind CSS.