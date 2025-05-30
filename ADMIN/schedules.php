<?php
session_start();
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/MaintenanceSchedule.php';
require_once __DIR__ . '/../classes/ACUnit.php';
require_once __DIR__ . '/../classes/User.php';

// ✅ Cek jika user belum login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../SignIn.php");
    exit;
}

$successMsg = '';
$errorMsg = '';

// Daftar email admin
$allowedAdminEmails = [
    'admin@gmail.com',
    'fajri@gmail.com',
    'firja@gmail.com'
];

try {
    $db = new Database();
    $pdo = $db->connect();

    $userClass = new User($db);
    $technicians = $userClass->getAllTechniciansExcludeAdmins($allowedAdminEmails);
    $units = ACUnit::getAll($pdo);

    // ✅ Ambil nama user login
    $stmtUser = $pdo->prepare("SELECT fullname FROM users WHERE id = ?");
    $stmtUser->execute([$_SESSION['user_id']]);
    $currentUser = $stmtUser->fetch(PDO::FETCH_ASSOC);
    $fullname = $currentUser ? $currentUser['fullname'] : "User";

    // ✅ Proses form
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['complete_id'])) {
            if (MaintenanceSchedule::markAsComplete($pdo, $_POST['complete_id'])) {
                $successMsg = "Schedule marked as completed.";
            } else {
                $errorMsg = "Failed to update status.";
            }
        } elseif (isset($_POST['cancel_id'])) {
            if (MaintenanceSchedule::deleteSchedule($pdo, $_POST['cancel_id'])) {
                $successMsg = "Schedule successfully deleted.";
            } else {
                $errorMsg = "Failed to delete schedule.";
            }
        } else {
            // Tambah jadwal
            $unitId = $_POST['unit_id'] ?? '';
            $technicianName = $_POST['technician_name'] ?? '';
            $maintenanceDate = $_POST['maintenance_date'] ?? '';
            $maintenanceType = $_POST['maintenance_type'] ?? '';
            $description = $_POST['description'] ?? '';

            if (empty($unitId) || empty($technicianName) || empty($maintenanceDate) || empty($maintenanceType)) {
                $errorMsg = "Please complete all required fields.";
            } else {
                $schedule = new MaintenanceSchedule($unitId, $technicianName, $maintenanceDate, $maintenanceType, $description);
                if ($schedule->saveToDatabase($pdo)) {
                    $successMsg = "Maintenance schedule successfully saved!";
                } else {
                    $errorMsg = "Failed to save schedule. Please try again.";
                }
            }
        }
    }

    // ✅ Ambil semua jadwal maintenance
    $stmt = $pdo->query("
        SELECT ms.*, au.unit_code 
        FROM maintenance_schedules ms
        JOIN ac_units au ON ms.unit_id = au.id
        ORDER BY ms.maintenance_date DESC
    ");
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $errorMsg = "Error: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoolCare - Maintenance Schedules</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            transition: all 0.3s ease;
        }
        .schedule-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .priority-high {
            border-left: 4px solid #ef4444;
        }
        .priority-medium {
            border-left: 4px solid #f59e0b;
        }
        .priority-low {
            border-left: 4px solid #10b981;
        }
        .calendar-day {
            transition: all 0.2s ease;
        }
        .calendar-day:hover {
            background-color: #f3f4f6;
        }
        .calendar-day.active {
            background-color: #3b82f6;
            color: white;
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
                            <a href="schedules.php" class="flex items-center p-2 rounded bg-blue-700 text-white">
                                <i class="fas fa-calendar-alt mr-3"></i>
                                <span>Schedules</span>
                            </a>
                        </li>
                        <li class="mb-1">
                            <a href="technicians.php" class="flex items-center p-2 rounded hover:bg-blue-700 text-blue-100 hover:text-white">
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
            <!-- Schedules Content -->
            <main class="p-6">
                <!-- Header and Add Button -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Maintenance Schedules</h1>
                        <p class="text-gray-600">View and manage upcoming AC maintenance schedules</p>
                    </div>
                    <button id="addScheduleBtn" class="mt-4 md:mt-0 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 flex items-center">
                        <i class="fas fa-plus mr-2"></i> Add Schedule
                    </button>
                </div>

                <!-- Schedule List -->
                <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                    <div class="schedule-card border rounded-lg p-4 hover:shadow-md transition-all duration-200">
                        <div class="space-y-4">
                            <?php if (!empty($logs)): ?>
                                <h2 class="text-lg font-semibold mb-4">Scheduled Maintenance Logs</h2>
                                <div class="space-y-4">
                                    <?php foreach ($logs as $log): ?>
                                        <div class="border rounded p-4 bg-gray-50 shadow-sm">
                                            <div class="flex justify-between items-start">
                                                <!-- Left side: details -->
                                                <div>
                                                    <p class="font-bold"><?= htmlspecialchars($log['unit_code']) ?></p>
                                                    <p class="text-sm text-gray-700"><?= htmlspecialchars($log['maintenance_type']) ?> - <?= htmlspecialchars($log['technician_name']) ?></p>
                                                    <p class="text-sm text-gray-600"><?= htmlspecialchars($log['description']) ?></p>
                                                    <p class="text-xs text-gray-500">
                                                        <i class="fas fa-calendar-alt mr-1"></i><?= date('F j, Y', strtotime($log['maintenance_date'])) ?>
                                                    </p>
                                                    <p class="text-xs font-semibold mt-1
                                                        <?= ($log['status'] ?? 'pending') === 'complete' ? 'text-green-600' : 'text-yellow-500' ?>">
                                                        Status: <?= htmlspecialchars($log['status'] ?? 'pending') ?>
                                                    </p>
                                                </div>

                                                <!-- Right side: buttons -->
                                                <div class="flex space-x-2">
                                                    <form method="POST">
                                                        <input type="hidden" name="complete_id" value="<?= $log['id'] ?>">
                                                        <button type="submit" class="text-green-600 hover:text-green-800 text-sm">
                                                            <i class="fas fa-check mr-1"></i> Complete
                                                        </button>
                                                    </form>

                                                    <form method="POST">
                                                        <input type="hidden" name="cancel_id" value="<?= $log['id'] ?>">
                                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                                            <i class="fas fa-times mr-1"></i> Cancel
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-gray-500 text-sm mt-4">No maintenance logs found.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <!-- Modal for Add Schedule -->
    <div id="addScheduleModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl">
            <div class="p-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-semibold">Add New Maintenance Schedule</h3>
                <button id="closeScheduleModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-4">

                <?php if (!empty($successMsg)): ?>
                        <div class="mb-4 p-3 bg-green-200 text-green-800 rounded"><?= htmlspecialchars($successMsg) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($errorMsg)): ?>
                        <div class="mb-4 p-3 bg-red-200 text-red-800 rounded"><?= htmlspecialchars($errorMsg) ?></div>
                    <?php endif; ?>

                <form method="POST">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-medium mb-1">AC Unit</label>
                            <select name="unit_id" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Select Unit</option>
                                <?php foreach ($units as $unit): ?>
                                    <option value="<?= htmlspecialchars($unit['id']) ?>">
                                        <?= htmlspecialchars($unit['unit_code']) ?> (<?= htmlspecialchars($unit['location']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-medium mb-1">Technician</label>
                            <select name="technician_name" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Assign technician</option>
                                <?php foreach ($technicians as $tech): ?>
                                    <option value="<?= htmlspecialchars($tech['fullname']) ?>">
                                        <?= htmlspecialchars($tech['fullname']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-medium mb-1">Maintenance Date</label>
                            <input type="date" name="maintenance_date" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-medium mb-1">Maintenance Type</label>
                            <select name="maintenance_type" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Type</option>
                                    <option value="Routine">Routine Maintenance</option>
                                    <option value="Corrective">Corrective Maintenance</option>
                                    <option value="Emergency">Emergency Repair</option>
                                    <option value="Installation">New Installation</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-1">Description</label>
                        <textarea rows="3" name="description" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter maintenance details..."></textarea>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-center space-x-4 pt-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i> Submit Schedule
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('-translate-x-full');
        });

        // Modal handling for schedules
        const addScheduleBtn = document.getElementById('addScheduleBtn');
        const closeScheduleModalButtons = [
            document.getElementById('closeScheduleModal'),
            document.getElementById('cancelScheduleModal')
        ];

        const scheduleModal = document.getElementById('addScheduleModal');

        addScheduleBtn.addEventListener('click', function() {
            scheduleModal.classList.remove('hidden');
        });

        closeScheduleModalButtons.forEach(button => {
            button.addEventListener('click', function() {
                scheduleModal.classList.add('hidden');
            });
        });

        // Close modal when clicking outside
        scheduleModal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    </script>
</body>
</html>