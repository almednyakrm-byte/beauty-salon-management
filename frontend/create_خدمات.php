**create_خدمات.php**

<?php
// Session validation
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header and footer
include 'header.php';
include 'footer.php';

// Get module slug
$mod_slug = 'خدمات';

// Get form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process form data
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Validate form data
    if (empty($name) || empty($description) || empty($price)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert data into database
        $sql = "INSERT INTO services (name, description, price) VALUES ('$name', '$description', '$price')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            // Redirect back to list page
            header('Location: list_' . $mod_slug . '.php');
            exit;
        } else {
            $error = 'Failed to create service';
        }
    }
}

// Display form
?>

<div class="container mx-auto p-4 pt-6">
    <h1 class="text-3xl font-bold text-rose-500 mb-4">Create New Service</h1>

    <form action="" method="post" class="bg-white p-4 rounded shadow-md">
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Service Name</label>
            <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Service Name">
        </div>

        <div class="mb-4">
            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Service Description</label>
            <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Service Description"></textarea>
        </div>

        <div class="mb-4">
            <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Service Price</label>
            <input type="number" id="price" name="price" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Service Price">
        </div>

        <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">Create Service</button>
    </form>

    <?php if (isset($error)) : ?>
        <p class="text-red-500"><?= $error ?></p>
    <?php endif; ?>
</div>

<script>
    $(document).ready(function() {
        $('#create-service-form').submit(function(event) {
            event.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/خدمات.php',
                data: formData,
                success: function(response) {
                    window.location.href = 'list_' + 'خدمات' + '.php';
                }
            });
        });
    });
</script>

Note: This code assumes you have a `header.php` and `footer.php` file that includes the necessary HTML structure for the page. You'll need to modify the code to fit your specific database schema and backend logic. Additionally, this code uses the `mysqli` extension for database interactions, which is deprecated in PHP 7.0 and later. You may want to consider using a more modern extension like `PDO` or `mysqli` with prepared statements.