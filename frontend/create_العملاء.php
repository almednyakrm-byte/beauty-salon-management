**create_العملاء.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6">
    <div class="bg-white rounded-lg shadow-md p-4">
        <h2 class="text-lg font-bold text-rose-500 mb-4">إضافة عميل جديد</h2>
        <form id="create-client-form">
            <div class="mb-4">
                <label for="client_name" class="block text-gray-700 text-sm font-bold mb-2">اسم العميل</label>
                <input type="text" id="client_name" name="client_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="اسم العميل">
            </div>
            <div class="mb-4">
                <label for="client_email" class="block text-gray-700 text-sm font-bold mb-2">البريد الإلكتروني</label>
                <input type="email" id="client_email" name="client_email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="البريد الإلكتروني">
            </div>
            <div class="mb-4">
                <label for="client_phone" class="block text-gray-700 text-sm font-bold mb-2">رقم الهاتف</label>
                <input type="tel" id="client_phone" name="client_phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="رقم الهاتف">
            </div>
            <div class="mb-4">
                <label for="client_address" class="block text-gray-700 text-sm font-bold mb-2">عنوان العميل</label>
                <textarea id="client_address" name="client_address" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="عنوان العميل"></textarea>
            </div>
            <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">إضافة عميل</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-client-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/العملاء.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_العملاء.php';
                    } else {
                        alert('Error adding client');
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**Note:** Make sure to replace `header.php`, `navigation.php`, and `footer.php` with your actual header, navigation, and footer files. Also, ensure that the `../backend/العملاء.php` file exists and is properly configured to handle the form data.