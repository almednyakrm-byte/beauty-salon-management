**create_صالونات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../backend/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);

    // Check for empty fields
    if (empty($name) || empty($address) || empty($phone) || empty($email)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert data into database
        $query = "INSERT INTO salonat (name, address, phone, email) VALUES ('$name', '$address', '$phone', '$email')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Redirect back to list page
            header('Location: list_صالونات.php');
            exit;
        } else {
            $error = 'Error inserting data';
        }
    }
}

// Include header
require_once '../backend/header.php';

?>

<!-- Create salon form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-lavender-600 mb-4">Create Salon</h2>
    <form id="create-salon-form" method="POST">
        <div class="mb-4">
            <label for="name" class="block text-sm font-bold text-gray-700">Name:</label>
            <input type="text" id="name" name="name" class="w-full p-2 pl-10 text-sm text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:ring-rose-500 focus:border-rose-500" required>
        </div>
        <div class="mb-4">
            <label for="address" class="block text-sm font-bold text-gray-700">Address:</label>
            <input type="text" id="address" name="address" class="w-full p-2 pl-10 text-sm text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:ring-rose-500 focus:border-rose-500" required>
        </div>
        <div class="mb-4">
            <label for="phone" class="block text-sm font-bold text-gray-700">Phone:</label>
            <input type="tel" id="phone" name="phone" class="w-full p-2 pl-10 text-sm text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:ring-rose-500 focus:border-rose-500" required>
        </div>
        <div class="mb-4">
            <label for="email" class="block text-sm font-bold text-gray-700">Email:</label>
            <input type="email" id="email" name="email" class="w-full p-2 pl-10 text-sm text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:ring-rose-500 focus:border-rose-500" required>
        </div>
        <button type="submit" name="submit" class="bg-rose-500 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded-lg">Create Salon</button>
    </form>
    <?php if (isset($error)) : ?>
        <p class="text-red-500 mt-2"><?= $error ?></p>
    <?php endif; ?>
</div>

<!-- Include footer -->
<?php require_once '../backend/footer.php'; ?>


**create_صالونات.js**
javascript
// Get form element
const form = document.getElementById('create-salon-form');

// Add event listener to form submission
form.addEventListener('submit', (e) => {
    e.preventDefault();

    // Get form data
    const formData = new FormData(form);

    // Send AJAX request to backend
    fetch('../backend/صالونات.php', {
        method: 'POST',
        body: formData,
    })
    .then((response) => response.json())
    .then((data) => {
        if (data.success) {
            // Redirect back to list page
            window.location.href = 'list_صالونات.php';
        } else {
            // Display error message
            const errorElement = document.createElement('p');
            errorElement.textContent = data.error;
            errorElement.classList.add('text-red-500', 'mt-2');
            form.appendChild(errorElement);
        }
    })
    .catch((error) => console.error(error));
});


**../backend/صالونات.php**

<?php
// Include database connection
require_once '../backend/db.php';

// Check if form data has been sent
if (isset($_POST['name']) && isset($_POST['address']) && isset($_POST['phone']) && isset($_POST['email'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);

    // Insert data into database
    $query = "INSERT INTO salonat (name, address, phone, email) VALUES ('$name', '$address', '$phone', '$email')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Return success response
        echo json_encode(['success' => true]);
    } else {
        // Return error response
        echo json_encode(['success' => false, 'error' => 'Error inserting data']);
    }
}
?>