<!-- register.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .title {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 10px;
        }
        .form-group input {
            width: 100%;
            height: 40px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group input:focus {
            border-color: #aaa;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .btn {
            width: 100%;
            height: 40px;
            padding: 10px;
            background-color: #ff69b4;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #ff99cc;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="title">
            <h2>Register</h2>
        </div>
        <form id="register-form">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required pattern="[A-Za-z\u0600-\u06FF0-9\s]+" placeholder="Enter your username">
                <small id="username-error" class="text-red-500"></small>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="Enter your email">
                <small id="email-error" class="text-red-500"></small>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">
                <small id="password-error" class="text-red-500"></small>
            </div>
            <button type="submit" class="btn">Register</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#register-form').submit(function(e) {
                e.preventDefault();
                var username = $('#username').val();
                var email = $('#email').val();
                var password = $('#password').val();
                var errors = [];

                if (!username.match(pattern)) {
                    errors.push('Invalid username');
                }
                if (!email.match(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/)) {
                    errors.push('Invalid email');
                }
                if (!password.match(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/)) {
                    errors.push('Invalid password');
                }

                if (errors.length > 0) {
                    $.each(errors, function(index, error) {
                        $('#' + error.split(' ')[0] + '-error').text(error);
                    });
                } else {
                    $.ajax({
                        type: 'POST',
                        url: '../backend/auth.php?action=register',
                        data: {
                            username: username,
                            email: email,
                            password: password
                        },
                        success: function(response) {
                            if (response === 'success') {
                                alert('Registration successful!');
                                window.location.href = 'login.php';
                            } else {
                                alert('Registration failed!');
                            }
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>


Note: The `pattern` variable is not defined in the code snippet. You need to define it before using it in the JavaScript code. The `pattern` variable should contain the regular expression for validating the input fields.


var pattern = /^[A-Za-z\u0600-\u06FF0-9\s]+$/;


This regular expression will match any string that contains only letters (both uppercase and lowercase), Arabic letters, digits, and spaces.

Also, you need to modify the `auth.php` file to handle the registration request and return a response to the JavaScript code. The response should be either 'success' or an error message.