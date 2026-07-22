<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-image: linear-gradient(to bottom, #ff69b4, #ffc5c5);
            background-size: 100% 300px;
            background-position: 0% 100%;
            transition: background-position 1s;
        }
        
        .glassmorphic {
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.1));
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .gradient {
            background: linear-gradient(to bottom, #ff69b4, #ffc5c5);
            background-size: 100% 300px;
            background-position: 0% 100%;
            transition: background-position 1s;
        }
    </style>
</head>
<body class="h-screen flex justify-center items-center bg-gray-100">
    <div class="glassmorphic w-96 p-10 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold text-center text-pink-600 mb-5">Login</h1>
        <form id="login-form" class="space-y-4">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" id="username" name="username" class="block w-full p-2 pl-10 text-sm text-gray-700 placeholder-gray-400 border border-gray-300 rounded-lg focus:ring-pink-600 focus:border-pink-600" placeholder="Username" pattern="[A-Za-z\u0600-\u06FF0-9\s]+">
                <p id="username-error" class="text-red-500 hidden"></p>
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" class="block w-full p-2 pl-10 text-sm text-gray-700 placeholder-gray-400 border border-gray-300 rounded-lg focus:ring-pink-600 focus:border-pink-600" placeholder="Password">
                <p id="password-error" class="text-red-500 hidden"></p>
            </div>
            <button type="submit" class="w-full p-2 bg-pink-600 hover:bg-pink-700 text-white rounded-lg">Login</button>
        </form>
        <p class="text-center text-gray-500 mt-5">Don't have an account? <a href="register.php" class="text-pink-600 hover:text-pink-700">Register</a></p>
    </div>

    <script>
        const form = document.getElementById('login-form');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const response = await fetch('../backend/auth.php?action=login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ username, password })
            });
            const data = await response.json();
            if (data.success) {
                window.location.href = 'dashboard.php';
            } else {
                if (data.username_error) {
                    document.getElementById('username-error').textContent = data.username_error;
                    document.getElementById('username-error').classList.remove('hidden');
                } else {
                    document.getElementById('username-error').classList.add('hidden');
                }
                if (data.password_error) {
                    document.getElementById('password-error').textContent = data.password_error;
                    document.getElementById('password-error').classList.remove('hidden');
                } else {
                    document.getElementById('password-error').classList.add('hidden');
                }
            }
        });
    </script>
</body>
</html>


This code creates a premium login page using Tailwind CSS with a glassmorphic layout, gradients, and a form for username and password input. It includes standard HTML input pattern validators to support Arabic and Latin characters. The form is submitted using AJAX with the Fetch API to the `../backend/auth.php?action=login` endpoint. The response is handled dynamically, and error messages are displayed below the corresponding input fields. The page also includes a direct link to the `register.php` page.