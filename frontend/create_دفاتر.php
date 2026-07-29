**create_دفاتر.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
require_once 'header.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8">
        <h2 class="text-lg font-bold text-rose-500 mb-4">إضافة دفاتر جديدة</h2>
        <form id="create-form" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">اسم الدفتر</label>
                <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-gray-300 border border-gray-300 rounded-md focus:ring-pink-600 focus:border-pink-600" placeholder="اسم الدفتر">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">وصف الدفتر</label>
                <textarea id="description" name="description" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-gray-300 border border-gray-300 rounded-md focus:ring-pink-600 focus:border-pink-600" placeholder="وصف الدفتر"></textarea>
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">حالة الدفتر</label>
                <select id="status" name="status" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-gray-300 border border-gray-300 rounded-md focus:ring-pink-600 focus:border-pink-600">
                    <option value="">اختر حالة الدفتر</option>
                    <option value="active">نشط</option>
                    <option value="inactive">مغلق</option>
                </select>
            </div>
            <button type="submit" class="bg-rose-500 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded">إضافة دفاتر جديدة</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/دفاتر.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_دفاتر.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
require_once 'footer.php';
?>


**Note:** This code assumes that you have jQuery and a backend PHP script (`دفاتر.php`) to handle the form submission. You'll need to modify the `دفاتر.php` script to process the form data and update the database accordingly.