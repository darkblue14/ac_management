<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AC Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #6b73ff 0%, #000dff 100%);
        }
        .gradient-text {
            background: linear-gradient(135deg, #6b73ff 0%, #000dff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full space-y-8 text-center">
        <!-- Logo dan Judul -->
        <div>
            <div class="mx-auto w-24 h-24 bg-white rounded-full shadow-lg flex items-center justify-center mb-4">
                <i class="fas fa-snowflake text-4xl gradient-text"></i>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900">Welcome to AC Management</h2>
            <p class="mt-2 text-sm text-gray-600">
                Manage your air conditioning systems efficiently
            </p>
        </div>

        <!-- Tombol Navigasi -->
        <div class="space-y-4">
            <a href="SignIn.php" class="block w-full py-3 px-4 rounded-md text-white text-lg font-medium gradient-bg hover:opacity-90 transition duration-200">
                <i class="fas fa-sign-in-alt mr-2"></i>Sign In
            </a>

            <a href="SignUp.php" class="block w-full py-3 px-4 rounded-md text-white text-lg font-medium bg-blue-600 hover:bg-blue-700 transition duration-200">
                <i class="fas fa-user-plus mr-2"></i>Sign Up
            </a>
        </div>

        <!-- Footer -->
        <div class="text-center text-sm text-gray-500 mt-6">
            <p>© 2023 AC Management System. All rights reserved.</p>
        </div>
    </div>

</body>
</html>
