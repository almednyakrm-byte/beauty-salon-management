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
    <title>إجراءات الدفع</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-rose-500 text-white p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-lg font-bold">الرئيسية</a>
            <div class="flex items-center">
                <span class="mr-2"><?= $_SESSION['username'] ?></span>
                <a href="logout.php" class="bg-lavender-600 hover:bg-lavender-700 px-4 py-2 rounded">تسجيل الخروج</a>
            </div>
        </nav>
    </header>
    <main class="p-4">
        <h1 class="text-2xl font-bold mb-4">إجراءات الدفع</h1>
        <div class="flex justify-between mb-4">
            <a href="create_إجراءات-الدفع.php" class="bg-rose-500 hover:bg-rose-600 px-4 py-2 rounded text-white">إضافة جديد</a>
            <input type="search" id="search" class="px-4 py-2 rounded" placeholder="بحث...">
        </div>
        <table id="records" class="w-full table-auto border-collapse border border-gray-300">
            <thead class="bg-lavender-600 text-white">
                <tr>
                    <th class="border border-gray-300 p-2">العمود 1</th>
                    <th class="border border-gray-300 p-2">العمود 2</th>
                    <th class="border border-gray-300 p-2">العمود 3</th>
                    <th class="border border-gray-300 p-2">إجراءات</th>
                </tr>
            </thead>
            <tbody id="records-body">
                <!-- Records will be populated here -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch records from backend
        fetch('../backend/إجراءات-الدفع.php')
            .then(response => response.json())
            .then(data => {
                const recordsBody = document.getElementById('records-body');
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="border border-gray-300 p-2">${record.column1}</td>
                        <td class="border border-gray-300 p-2">${record.column2}</td>
                        <td class="border border-gray-300 p-2">${record.column3}</td>
                        <td class="border border-gray-300 p-2">
                            <a href="edit_إجراءات-الدفع.php?id=${record.id}" class="text-rose-500 hover:text-rose-600">تعديل</a>
                            <button class="text-lavender-600 hover:text-lavender-700" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                    `;
                    recordsBody.appendChild(row);
                });
            });

        // Delete record
        function deleteRecord(id) {
            fetch('../backend/إجراءات-الدفع.php', {
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
                const row = rows[i];
                const rowText = row.textContent.toLowerCase();
                if (rowText.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>