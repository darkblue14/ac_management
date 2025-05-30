<?php
require_once 'classes/User.php';
require_once 'config/Database.php';

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db = new Database();           // Buat koneksi
    $user = new User($db);          // Inject koneksi ke User

    $user->setFullname($_POST['fullname']);
    $user->setEmail($_POST['email']);
    $user->setPassword($_POST['password']);

    if ($user->isEmailRegistered($_POST['email'])) {
        $error = "Email is already registered.";
    } else {
        if ($user->save()) {
            $success = "Account created successfully!";
        } else {
            $error = "Failed to register. Please try again.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AC Management System - Sign Up</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #6b73ff 0%, #000dff 100%);
        }
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(107, 115, 255, 0.3);
        }
        .shake {
            animation: shake 0.5s;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-5px); }
            40%, 80% { transform: translateX(5px); }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full space-y-8">
        <!-- Logo and Header -->
        <div class="text-center">
            <div class="mx-auto w-24 h-24 bg-white rounded-full shadow-lg flex items-center justify-center mb-4">
                <i class="fas fa-snowflake text-4xl gradient-text" style="background: linear-gradient(135deg, #6b73ff 0%, #000dff 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900">AC Management</h2>
            <p class="mt-2 text-sm text-gray-600">
                Create an account to control and monitor your air conditioning systems
            </p>
        </div>

        <div class="bg-white py-8 px-6 shadow-lg rounded-lg w-full max-w-md">
            <?php if ($success): ?>
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?= $success ?></div>
            <?php elseif ($error): ?>
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= $error ?></div>
            <?php endif; ?>

            <form id="signupForm" action="" method="POST" class="space-y-6">
                <div class="rounded-md shadow-sm space-y-4">
                    <!-- Full Name -->
                    <div>
                        <label for="fullname" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input id="fullname" name="fullname" type="text" required 
                                value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>"
                                class="block w-full px-10 py-3 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                placeholder="Enter your full name">
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input id="email" name="email" type="email" required 
                                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                                class="block w-full px-10 py-3 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                placeholder="Enter your email">
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input id="password" name="password" type="password" required 
                                class="block w-full px-10 py-3 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                placeholder="Create a password">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button" id="togglePassword" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div>
                        <button type="submit" id="submitBtn" class="w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <i class="fas fa-user-plus"></i>
                            </span>
                            Create Account
                        </button>
                    </div>
                </div>
            </form>

            <!-- Footer Links -->
            <div class="text-center text-sm text-gray-500 mt-6">
                <p class="mb-8">Already have an account? <a href="SignIn.php" class="font-medium text-blue-600 hover:text-blue-500">Sign In</a></p>
                <p class="mt-2">© 2023 AC Management System. All rights reserved.</p>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        
        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });
    </script>
</body>
</html>