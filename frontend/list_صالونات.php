**list_صالونات.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صالونات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .bg-rose-500 {
            background-color: #ff69b4;
        }
        .text-lavender-600 {
            color: #c7b8ea;
        }
    </style>
</head>
<body class="bg-gray-100">
    <header class="bg-rose-500 text-white py-4">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center">
                <a href="index.php" class="text-lg font-bold">Home</a>
                <div class="flex items-center">
                    <span class="text-lg font-bold"><?= $_SESSION['username'] ?></span>
                    <button class="bg-lavender-600 hover:bg-lavender-700 text-white font-bold py-2 px-4 rounded ml-4" onclick="location.href='logout.php'">Logout</button>
                </div>
            </div>
        </div>
    </header>
    <main class="container mx-auto px-4 py-12">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold">List of صالونات</h1>
            <button class="bg-lavender-600 hover:bg-lavender-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_صالونات.php'">Add New Item</button>
        </div>
        <div class="bg-white p-4 rounded shadow-md">
            <form class="flex items-center mb-4">
                <input type="search" class="w-full py-2 pl-10 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-600" placeholder="Search..." id="search" onkeyup="filterList()">
                <button class="bg-lavender-600 hover:bg-lavender-700 text-white font-bold py-2 px-4 rounded ml-2" onclick="filterList()">Search</button>
            </form>
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody id="list">
                    <?php
                    // Fetch list records from backend
                    $response = file_get_contents('../backend/صالونات.php');
                    $data = json_decode($response, true);
                    foreach ($data as $item) {
                        ?>
                        <tr>
                            <td class="px-4 py-2"><?= $item['id'] ?></td>
                            <td class="px-4 py-2"><?= $item['name'] ?></td>
                            <td class="px-4 py-2">
                                <a href="edit_صالونات.php?id=<?= $item['id'] ?>" class="bg-lavender-600 hover:bg-lavender-700 text-white font-bold py-2 px-4 rounded mr-2">Edit</a>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteItem(<?= $item['id'] ?>)">Delete</button>
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
        function filterList() {
            const search = document.getElementById('search').value;
            const list = document.getElementById('list');
            const rows = list.getElementsByTagName('tr');
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.getElementsByTagName('td');
                let match = false;
                for (let j = 0; j < cells.length; j++) {
                    const cell = cells[j];
                    if (cell.textContent.toLowerCase().includes(search.toLowerCase())) {
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

        function deleteItem(id) {
            if (confirm('Are you sure you want to delete this item?')) {
                fetch('../backend/صالونات.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error deleting item');
                    }
                })
                .catch(error => console.error(error));
            }
        }
    </script>
</body>
</html>

Note: This code assumes that the backend API is already implemented and returns a JSON response with the list of records. The `deleteItem` function sends a DELETE request to the backend API to delete the item with the specified ID.