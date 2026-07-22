**list_customers.php**

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
    <title>Customers</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
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
            color: #999;
        }
        .table-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .table-container table {
            width: 100%;
            border-collapse: collapse;
        }
        .table-container table th, .table-container table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .table-container table th {
            background-color: #f7f7f7;
        }
        .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .actions button {
            background-color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            cursor: pointer;
        }
        .actions button:hover {
            background-color: #f7f7f7;
        }
        .search-bar {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 1rem;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 10px;
            border: none;
            font-size: 1rem;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">Customers</div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="profile.php"><?= $_SESSION['username'] ?></a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="table-container">
        <h2>Customers List</h2>
        <div class="actions">
            <button class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded"><a href="create_customers.php">Add New Item</a></button>
            <div class="search-bar">
                <input type="search" id="search-input" placeholder="Search...">
                <button class="bg-rose-500 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded" id="search-button">Search</button>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <?php
                // Fetch data from backend
                $response = file_get_contents('../backend/customers.php');
                $data = json_decode($response, true);
                foreach ($data as $customer) {
                    ?>
                    <tr>
                        <td><?= $customer['id'] ?></td>
                        <td><?= $customer['name'] ?></td>
                        <td><?= $customer['email'] ?></td>
                        <td>
                            <button class="bg-rose-500 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded" onclick="editCustomer(<?= $customer['id'] ?>)">Edit</button>
                            <button class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded" onclick="deleteCustomer(<?= $customer['id'] ?>)">Delete</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Search functionality
        const searchInput = document.getElementById('search-input');
        const searchButton = document.getElementById('search-button');
        const tableBody = document.getElementById('table-body');

        searchButton.addEventListener('click', () => {
            const searchTerm = searchInput.value.trim();
            if (searchTerm) {
                // Fetch data from backend with search query
                fetch('../backend/customers.php?search=' + searchTerm)
                    .then(response => response.json())
                    .then(data => {
                        tableBody.innerHTML = '';
                        data.forEach(customer => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${customer.id}</td>
                                <td>${customer.name}</td>
                                <td>${customer.email}</td>
                                <td>
                                    <button class="bg-rose-500 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded" onclick="editCustomer(${customer.id})">Edit</button>
                                    <button class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded" onclick="deleteCustomer(${customer.id})">Delete</button>
                                </td>
                            `;
                            tableBody.appendChild(row);
                        });
                    });
            } else {
                // Fetch all data from backend
                fetch('../backend/customers.php')
                    .then(response => response.json())
                    .then(data => {
                        tableBody.innerHTML = '';
                        data.forEach(customer => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${customer.id}</td>
                                <td>${customer.name}</td>
                                <td>${customer.email}</td>
                                <td>
                                    <button class="bg-rose-500 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded" onclick="editCustomer(${customer.id})">Edit</button>
                                    <button class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded" onclick="deleteCustomer(${customer.id})">Delete</button>
                                </td>
                            `;
                            tableBody.appendChild(row);
                        });
                    });
            }
        });

        // Delete customer functionality
        function deleteCustomer(id) {
            if (confirm('Are you sure you want to delete this customer?')) {
                fetch('../backend/customers.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Customer deleted successfully!');
                        window.location.reload();
                    } else {
                        alert('Error deleting customer!');
                    }
                });
            }
        }

        // Edit customer functionality
        function editCustomer(id) {
            window.location.href = 'edit_customers.php?id=' + id;
        }
    </script>
</body>
</html>

This code includes a premium Tailwind UI design with a custom color palette matching the theme. It also includes session validation, a search bar, and AJAX functionality for fetching and deleting customer data. The `editCustomer` function redirects to the `edit_customers.php` page with the customer ID as a parameter.