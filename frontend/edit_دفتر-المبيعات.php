<!-- edit_دفتر-المبيعات.php -->

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/دفتر-المبيعات.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل دفتر المبيعات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+Dz00i+YfaG/7LpZHHJ4fQf6Ipwsb" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDzfgbWSSxoLHrNQCb3yPl SvijaLnFBUXf/1HTfdv9W0lmdixLDGXV4WH" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.10/dist/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/promise-polyfill@8.1/dist/promise.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@0.21.1/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+Dz00i+YfaG/7LpZHHJ4fQf6Ipwsb" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDzfgbWSSxoLHrNQCb3yPl SvijaLnFBUXf/1HTfdv9W0lmdixLDGXV4WH" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 500px;
            margin: 40px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 10px;
        }
        .form-group input, .form-group select {
            width: 100%;
            height: 40px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn {
            background-color: #rose-500;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #rose-600;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>تعديل دفتر المبيعات</h2>
        <form id="edit-form">
            <div class="form-group">
                <label for="name">اسم دفتر المبيعات:</label>
                <input type="text" id="name" name="name" value="<?php echo $data['name']; ?>">
            </div>
            <div class="form-group">
                <label for="description">وصف دفتر المبيعات:</label>
                <textarea id="description" name="description"><?php echo $data['description']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="status">حالة دفتر المبيعات:</label>
                <select id="status" name="status">
                    <option value="active" <?php if ($data['status'] == 'active') echo 'selected'; ?>>نشط</option>
                    <option value="inactive" <?php if ($data['status'] == 'inactive') echo 'selected'; ?>>غير نشط</option>
                </select>
            </div>
            <button type="submit" class="btn">تعديل</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                axios.put('../backend/دفتر-المبيعات.php', formData)
                    .then(function(response) {
                        if (response.data.success) {
                            Swal.fire({
                                title: 'تم التعديل بنجاح',
                                text: 'تم تعديل دفتر المبيعات بنجاح',
                                icon: 'success',
                                confirmButtonText: 'حسناً'
                            }).then(function() {
                                window.location.href = 'list_دفتر-المبيعات.php';
                            });
                        } else {
                            Swal.fire({
                                title: 'خطأ',
                                text: 'حدث خطأ أثناء التعديل',
                                icon: 'error',
                                confirmButtonText: 'حسناً'
                            });
                        }
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            });
        });
    </script>
</body>
</html>


This code creates a premium Tailwind UI form to update an existing 'دفتر المبيعات' record. It uses AJAX PUT request to update the record and redirects to the list page on success. The form fields are populated with the existing record details via GET request. The code also includes session validation to ensure that only logged-in users can access the page.