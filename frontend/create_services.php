**create_services.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
require_once 'header.php';
require_once 'nav.php';

// Include form
require_once 'create_services_form.php';
?>

<?php
// Include footer
require_once 'footer.php';
?>


**create_services_form.php**

<!-- Create Services Form -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-12">
        <h2 class="text-lg font-bold text-rose-500 mb-4">Create New Service</h2>
        <form id="create-service-form" class="space-y-4">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="service_name">Service Name</label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="service_name" type="text" placeholder="Service Name">
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="service_description">Service Description</label>
                    <textarea class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="service_description" rows="4" placeholder="Service Description"></textarea>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="service_price">Service Price</label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="service_price" type="number" placeholder="Service Price">
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="service_status">Service Status</label>
                    <select class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="service_status">
                        <option value="">Select Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">Create Service</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-service-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/services.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_services.php';
                    } else {
                        alert('Error creating service');
                    }
                }
            });
        });
    });
</script>


**header.php, nav.php, and footer.php** are assumed to be existing files that include the necessary HTML for the header, navigation, and footer of the page.

**services.php** is assumed to be a backend PHP file that handles the creation of a new service record. It should be modified to accept the form data and insert it into the database.

**list_services.php** is assumed to be a page that lists all existing service records. It should be modified to handle the redirect after creating a new service record.