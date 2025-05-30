<?php
session_start();
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/User.php';

// ✅ Cek jika belum login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../SignIn.php");
    exit;
}

$successMsg = '';
$errorMsg = '';
$fullname = 'User';

try {
    $db = new Database();
    $conn = $db->connect();
    $user = new User($db);

    // ✅ Ambil nama user yang sedang login
    $stmt = $conn->prepare("SELECT fullname FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($userData) {
        $fullname = $userData['fullname'];
    }

    // ✅ Proses tambah teknisi
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_technician'])) {
        $fullnameInput = $_POST['fullname'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($fullnameInput) || empty($email) || empty($password)) {
            $errorMsg = "All fields are required.";
        } elseif ($user->isEmailRegistered($email)) {
            $errorMsg = "Email is already registered.";
        } else {
            $user->setFullname($fullnameInput);
            $user->setEmail($email);
            $user->setPassword($password); // Harusnya hashing terjadi di method setPassword()

            if ($user->save()) {
                $successMsg = "Technician successfully added.";
            } else {
                $errorMsg = "Failed to add technician.";
            }
        }
    }

    // ✅ Proses hapus teknisi
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
        $deleteId = $_POST['delete_id'];

        if ($user->deleteById($deleteId)) {
            $successMsg = "Technician successfully deleted.";
        } else {
            $errorMsg = "Failed to delete technician.";
        }
    }

    // ✅ Ambil daftar teknisi
    $stmt = $conn->query("SELECT id, fullname, email FROM users ORDER BY fullname ASC");
    $technicians = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $errorMsg = "Error: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoolCare - Technicians Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            transition: all 0.3s ease;
        }
        .technician-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .avatar-ring {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .avatar-ring::after {
            content: '';
            position: absolute;
            top: -3px;
            left: -3px;
            right: -3px;
            bottom: -3px;
            border-radius: 50%;
            border: 2px solid;
            z-index: -1;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="sidebar bg-blue-800 text-white w-64 flex-shrink-0">
            <div class="p-4 flex items-center justify-between border-b border-blue-700">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-snowflake text-2xl text-blue-300"></i>
                    <span class="text-xl font-bold">CoolCare</span>
                </div>
                <button id="sidebarToggle" class="text-blue-200 hover:text-white md:hidden">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <nav class="p-4">
                <div class="mb-6">
                    <p class="text-blue-300 uppercase text-xs font-semibold mb-2">Main Menu</p>
                    <ul>
                        <li class="mb-1">
                            <a href="index.php" class="flex items-center p-2 rounded hover:bg-blue-700 text-blue-100 hover:text-white">
                                <i class="fas fa-tachometer-alt mr-3"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="mb-1">
                            <a href="maintenance_logs.php" class="flex items-center p-2 rounded hover:bg-blue-700 text-blue-100 hover:text-white">
                                <i class="fas fa-list mr-3"></i>
                                <span>Maintenance Logs</span>
                            </a>
                        </li>
                        <li class="mb-1">
                            <a href="schedules.php" class="flex items-center p-2 rounded hover:bg-blue-700 text-blue-100 hover:text-white">
                                <i class="fas fa-calendar-alt mr-3"></i>
                                <span>Schedules</span>
                            </a>
                        </li>
                        <li class="mb-1">
                            <a href="technicians.php" class="flex items-center p-2 rounded bg-blue-700 text-white">
                                <i class="fas fa-users mr-3"></i>
                                <span>Technicians</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div>
                    <p class="text-blue-300 uppercase text-xs font-semibold mb-2">Reports</p>
                    <ul>
                        <li class="mb-1">
                            <a href="performances.php" class="flex items-center p-2 rounded hover:bg-blue-700 text-blue-100 hover:text-white">
                                <i class="fas fa-chart-bar mr-3"></i>
                                <span>Performance</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="absolute bottom-0 p-4">
                <div class="flex items-center">
                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-user text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium"><?php echo htmlspecialchars($fullname); ?></p>
                        <p class="text-xs text-blue-300">Admin</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <!-- Technicians Content -->
            <main class="p-6">
                <!-- Header and Add Button -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Technicians</h1>
                        <p class="text-gray-600">Manage your AC maintenance technicians</p>
                    </div>
                    <button id="addTechnicianBtn" class="mt-4 md:mt-0 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 flex items-center">
                        <i class="fas fa-plus mr-2"></i> Add Technician
                    </button>
                </div>

                <!-- Technicians Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mt-6">
                    <?php foreach ($technicians as $tech): ?>
                        <div class="technician-card bg-white rounded-lg shadow overflow-hidden transition-all duration-300">
                            <div class="p-4 flex flex-col items-center text-center">
                                <div class="mb-3">
                                    <div class="h-20 w-20 rounded-full bg-blue-100 flex items-center justify-center text-2xl font-bold text-blue-600">
                                        <?= strtoupper(substr($tech['fullname'], 0, 2)) ?>
                                    </div>
                                </div>
                                <h3 class="text-lg font-semibold"><?= htmlspecialchars($tech['fullname']) ?></h3>
                                <p class="text-sm text-gray-500"><?= htmlspecialchars($tech['email']) ?></p>
                            </div>
                            <div class="border-t px-4 py-3 bg-gray-50">
                                <div class="flex flex-col items-center">
                                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this technician?');">
                                        <input type="hidden" name="delete_id" value="<?= $tech['id'] ?>">
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>         
            </main>
        </div>
    </div>

    <!-- Modal for Add Technician -->
    <div id="addTechnicianModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="p-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-semibold">Add New Technician</h3>
                <button id="closeTechnicianModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-4">
                <form id="technicianForm" method="POST">
                    <input type="hidden" name="register_technician" value="1">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-1">Full Name</label>
                        <input name="fullname" type="text" class="w-full border rounded px-3 py-2" placeholder="Enter full name" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-1">Email</label>
                        <input name="email" type="email" class="w-full border rounded px-3 py-2" placeholder="Enter email" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-1">Password</label>
                        <input name="password" type="password" class="w-full border rounded px-3 py-2" placeholder="Enter password" required>
                    </div>
                </form>

            </div>
            <div class="p-4 border-t flex justify-end space-x-2">
                <button id="cancelTechnicianModal" type="button" class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">Cancel</button>
                <button type="submit" form="technicianForm" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Add Technician</button>
            </div>
        </div>
    </div>

    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('-translate-x-full');
        });

        // Modal handling for technicians
        const technicianModal = document.getElementById('addTechnicianModal');
        
        // Function to open modal
        function openTechnicianModal() {
            technicianModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
        }
        
        // Function to close modal
        function closeTechnicianModal() {
            technicianModal.classList.add('hidden');
            document.body.style.overflow = ''; // Restore scrolling
        }

        // Open modal buttons
        document.getElementById('addTechnicianBtn')?.addEventListener('click', openTechnicianModal);
        document.getElementById('addTechnicianCardBtn')?.addEventListener('click', openTechnicianModal);

        // Close modal buttons
        document.getElementById('closeTechnicianModal')?.addEventListener('click', closeTechnicianModal);
        document.getElementById('cancelTechnicianModal')?.addEventListener('click', closeTechnicianModal);

        // Close modal when clicking outside
        technicianModal.addEventListener('click', function(e) {
            if (e.target === technicianModal) {
                closeTechnicianModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !technicianModal.classList.contains('hidden')) {
                closeTechnicianModal();
            }
        });
    </script>
</body>
</html>