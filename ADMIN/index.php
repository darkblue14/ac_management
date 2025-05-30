<?php
session_start();
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/MaintenanceSchedule.php';

if (!isset($_SESSION['user_id'])) {
    // Belum login, redirect ke halaman login
    header("Location: SignIn.php");
    exit;
}

$isAdmin = $_SESSION['is_admin'] ?? true;

$db = new Database();
$pdo = $db->connect();

try {
    $stmt = $pdo->prepare("SELECT fullname FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $fullname = $user ? $user['fullname'] : "User";
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

$completedLogs = MaintenanceSchedule::getCompletedLogs($pdo);
$upcomingLogs = MaintenanceSchedule::getUpcomingSchedules($pdo);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoolCare - AC Maintenance Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            transition: all 0.3s ease;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .progress-ring__circle {
            transition: stroke-dashoffset 0.35s;
            transform: rotate(-90deg);
            transform-origin: 50% 50%;
        }
        #maintenanceChart, #statusChart {
            max-height: 300px;
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
                            <a href="index.php" class="flex items-center p-2 rounded bg-blue-700 text-white">
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
            <!-- Dashboard Content -->
            <main class="p-6">

                <!-- Recent Activities and Upcoming Maintenance -->
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Recent Activities -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-4 border-b">
                        <h2 class="text-lg font-semibold">Recent Activities</h2>
                    </div>
                    <div class="divide-y">
                        <?php if (!empty($completedLogs)): ?>
                            <?php foreach ($completedLogs as $log): ?>
                                <div class="flex items-start space-x-3 p-4">
                                    <div class="p-2 rounded-full bg-blue-100 text-blue-600">
                                        <i class="fas fa-tools"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-sm sm:text-base">
                                            Maintenance completed for <?= htmlspecialchars($log['unit_code']) ?>
                                        </p>
                                        <p class="text-xs sm:text-sm text-gray-500">
                                            By Technician: <?= htmlspecialchars($log['technician_name']) ?>
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            <?= date("F j, Y", strtotime($log['maintenance_date'])) ?>
                                        </p>
                                        <span class="inline-block mt-1 bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded-full">
                                            Status: Complete
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="p-4">
                                <p class="text-sm text-gray-500">No completed maintenance logs yet.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>


                <!-- Upcoming Maintenance -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-4 border-b flex justify-between items-center">
                        <h2 class="text-lg font-semibold">Upcoming Maintenance</h2>
                    </div>
                    <div class="divide-y">
                        <?php if (!empty($upcomingLogs)): ?>
                            <?php foreach ($upcomingLogs as $item): ?>
                                <div class="p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center">
                                    <div class="mb-2 sm:mb-0">
                                        <p class="font-medium"><?= htmlspecialchars($item['unit_code']) ?></p>
                                        <p class="text-sm text-gray-500"><?= htmlspecialchars($item['maintenance_type']) ?> - <?= htmlspecialchars($item['technician_name']) ?></p>
                                        <span class="inline-block mt-1 bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-1 rounded-full">
                                            Pending
                                        </span>
                                    </div>
                                    <div class="text-left sm:text-right">
                                        <p class="text-sm font-medium"><?= date('M d, Y', strtotime($item['maintenance_date'])) ?></p>
                                        <p class="text-xs text-gray-500"><?= htmlspecialchars($item['description']) ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="p-4">
                                <p class="text-sm text-gray-500">No upcoming maintenance scheduled.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('-translate-x-full');
        });
    </script>
</body>
</html>
