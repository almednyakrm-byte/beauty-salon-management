**create_العملاء.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/database.php';

// Get module slug
$mod_slug = 'العملاء';

// Get form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Validate form data
    if (empty($name) || empty($email) || empty($phone) || empty($address)) {
        $error = 'Please fill all fields';
    } else {
        // Insert data into database
        $sql = "INSERT INTO العملاء (name, email, phone, address) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $email, $phone, $address]);

        // Redirect to list page
        header('Location: list_' . $mod_slug . '.php');
        exit;
    }
}

// Include header
require_once '../includes/header.php';

?>

<!-- Create new customer form -->
<div class="max-w-md mx-auto p-8 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-pink-600 mb-4">Create New Customer</h2>
    <form id="create-customer-form" method="post">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
            <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-gray-700 border-gray-300 rounded-lg focus:ring-pink-600 focus:border-pink-600" placeholder="John Doe">
        </div>
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
            <input type="email" id="email" name="email" class="block w-full p-2 pl-10 text-sm text-gray-700 border-gray-300 rounded-lg focus:ring-pink-600 focus:border-pink-600" placeholder="john.doe@example.com">
        </div>
        <div class="mb-4">
            <label for="phone" class="block text-sm font-medium text-gray-700">Phone:</label>
            <input type="tel" id="phone" name="phone" class="block w-full p-2 pl-10 text-sm text-gray-700 border-gray-300 rounded-lg focus:ring-pink-600 focus:border-pink-600" placeholder="+1234567890">
        </div>
        <div class="mb-4">
            <label for="address" class="block text-sm font-medium text-gray-700">Address:</label>
            <textarea id="address" name="address" class="block w-full p-2 pl-10 text-sm text-gray-700 border-gray-300 rounded-lg focus:ring-pink-600 focus:border-pink-600" placeholder="123 Main St, Anytown, USA"></textarea>
        </div>
        <button type="submit" class="bg-rose-500 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded">Create Customer</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>

<script>
    $(document).ready(function() {
        $('#create-customer-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/العملاء.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_' + '<?php echo $mod_slug; ?>.php';
                    } else {
                        alert('Error creating customer');
                    }
                }
            });
        });
    });
</script>


**Note:** This code assumes you have a database connection established in `../config/database.php` and a backend script `../backend/العملاء.php` to handle the form submission. You'll need to modify the code to fit your specific database schema and backend logic.