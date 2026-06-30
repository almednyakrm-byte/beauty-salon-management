**list_دفتر-المبيعات.php**

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
    <title>دفتر المبيعات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            direction: rtl;
        }
        .rose-500 {
            color: #e83e8c;
        }
        .teal-500 {
            color: #0bc3f8;
        }
    </style>
</head>
<body>
    <header class="bg-gray-100 py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <a href="index.php" class="text-lg font-bold">الرئيسية</a>
            <div class="flex items-center">
                <p class="mr-2">مرحباً, <?php echo $_SESSION['username']; ?></p>
                <a href="logout.php" class="text-lg font-bold text-red-500">تسجيل الخروج</a>
            </div>
        </div>
    </header>
    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-4">دفتر المبيعات</h1>
        <div class="flex justify-between items-center mb-4">
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_دفتر-المبيعات.php'">اضافة جديد</button>
            <input type="search" class="w-full py-2 pl-10 text-lg border border-gray-300 rounded-lg focus:outline-none focus:border-teal-500" placeholder="بحث" id="search" oninput="filterList()">
        </div>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr>
                    <th class="border border-gray-300 px-4 py-2">العنوان</th>
                    <th class="border border-gray-300 px-4 py-2">التاريخ</th>
                    <th class="border border-gray-300 px-4 py-2">الإجراءات</th>
                </tr>
            </thead>
            <tbody id="list">
                <!-- List records will be rendered here -->
            </tbody>
        </table>
    </main>
    <script>
        // Fetch list records from backend
        fetch('../backend/دفتر-المبيعات.php')
            .then(response => response.json())
            .then(data => {
                const list = document.getElementById('list');
                data.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="border border-gray-300 px-4 py-2">${item.عنوان}</td>
                        <td class="border border-gray-300 px-4 py-2">${item.تاريخ}</td>
                        <td class="border border-gray-300 px-4 py-2">
                            <a href="edit_دفتر-المبيعات.php?id=${item.id}" class="text-lg font-bold text-teal-500">تعديل</a>
                            <button class="text-lg font-bold text-red-500" onclick="deleteItem(${item.id})">حذف</button>
                        </td>
                    `;
                    list.appendChild(row);
                });
            })
            .catch(error => console.error(error));

        // Delete item
        function deleteItem(id) {
            fetch('../backend/دفتر-المبيعات.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('تم حذف العنصر بنجاح');
                    location.reload();
                } else {
                    alert('حدث خطأ أثناء حذف العنصر');
                }
            })
            .catch(error => console.error(error));
        }

        // Filter list
        function filterList() {
            const search = document.getElementById('search').value;
            const list = document.getElementById('list');
            const rows = list.getElementsByTagName('tr');
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.getElementsByTagName('td');
                let match = false;
                for (let j = 0; j < cells.length; j++) {
                    if (cells[j].textContent.includes(search)) {
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
        }
    </script>
</body>
</html>

This code creates a premium Tailwind UI layout with a header navigation, a table showing list of records, and a search bar filtering elements in real-time. The list records are fetched from the backend using Fetch API and displayed in the table. The delete button sends a DELETE request to the backend to delete the corresponding record.