<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
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
</head>
<body>
    <header class="bg-rose-500 text-white p-4">
        <nav class="container mx-auto flex justify-between">
            <a href="index.php" class="text-lg font-bold">الرئيسية</a>
            <div class="flex items-center">
                <span class="mr-4"><?= $_SESSION['username'] ?></span>
                <a href="logout.php" class="bg-pink-600 hover:bg-pink-700 px-4 py-2 rounded">تسجيل الخروج</a>
            </div>
        </nav>
    </header>
    <main class="container mx-auto p-4 mt-4">
        <h1 class="text-2xl font-bold mb-4">العملاء</h1>
        <div class="flex justify-between mb-4">
            <a href="create_العملاء.php" class="bg-pink-600 hover:bg-pink-700 px-4 py-2 rounded">إضافة جديد</a>
            <input type="search" id="search" class="px-4 py-2 rounded" placeholder="بحث...">
        </div>
        <table id="records" class="w-full table-auto border-collapse border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border border-gray-300">الاسم</th>
                    <th class="px-4 py-2 border border-gray-300">البريد الإلكتروني</th>
                    <th class="px-4 py-2 border border-gray-300">العمليات</th>
                </tr>
            </thead>
            <tbody id="records-body">
                <!-- Records will be populated here -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch records from backend
        fetch('../backend/العملاء.php')
            .then(response => response.json())
            .then(data => {
                const recordsBody = document.getElementById('records-body');
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2 border border-gray-300">${record.name}</td>
                        <td class="px-4 py-2 border border-gray-300">${record.email}</td>
                        <td class="px-4 py-2 border border-gray-300">
                            <a href="edit_العملاء.php?id=${record.id}" class="text-blue-600 hover:text-blue-700">تعديل</a>
                            <button class="text-red-600 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                    `;
                    recordsBody.appendChild(row);
                });
            });

        // Delete record
        function deleteRecord(id) {
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
                    // Remove the deleted record from the table
                    const rows = document.getElementById('records-body').children;
                    for (let i = 0; i < rows.length; i++) {
                        if (rows[i].querySelector('td:last-child').querySelector('button').onclick.toString().includes(`deleteRecord(${id})`)) {
                            rows[i].remove();
                            break;
                        }
                    }
                }
            });
        }

        // Search functionality
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const rows = document.getElementById('records-body').children;
            for (let i = 0; i < rows.length; i++) {
                const rowText = rows[i].textContent.toLowerCase();
                if (rowText.includes(searchValue)) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>