<?php
session_start();
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/MaintenanceSchedule.php';
require_once __DIR__ . '/../classes/ACUnit.php';

$db = new Database();
$conn = $db->connect();

$fullname = "Technician"; // Default
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT fullname FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $fullname = $user['fullname'];
    }
}

// Ambil semua logs + nama AC unit
$stmt = $conn->query("
    SELECT ms.*, au.unit_code 
    FROM maintenance_schedules ms
    JOIN ac_units au ON ms.unit_id = au.id
    ORDER BY ms.maintenance_date DESC
");
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Inisialisasi array teknisi dari logs (setelah $logs diisi)
$technicians = [];

foreach ($logs as $log) {
    $technicians[$log['technician_name']][] = $log;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technician Dashboard | CoolCare - AC Maintenance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            transition: all 0.3s ease;
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .status-badge {
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        #photoPreview {
            max-height: 200px;
            display: none;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="sidebar bg-blue-800 text-white w-64 flex-shrink-0 flex flex-col">
            <div class="p-4 flex items-center justify-between border-b border-blue-700">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-snowflake text-2xl text-blue-300"></i>
                    <span class="text-xl font-bold">CoolCare</span>
                </div>
                <button id="sidebarToggle" class="text-blue-200 hover:text-white md:hidden">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <nav class="p-4 flex-grow">
                <div class="mb-6">
                    <p class="text-blue-300 uppercase text-xs font-semibold mb-2">Technician Menu</p>
                    <ul>
                        <li class="mb-1">
                            <a href="index.php" class="flex items-center p-2 rounded hover:bg-blue-700 text-blue-100 hover:text-white">
                                <i class="fas fa-tachometer-alt mr-3"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="mb-1">
                            <a href="schedule_view.php" class="flex items-center p-2 rounded bg-blue-700 text-white">
                                <i class="fas fa-calendar-alt mr-3"></i>
                                <span>Schedule View</span>
                            </a>
                        </li>
                        <li class="mb-1">
                            <a href="log_maintenance.php" class="flex items-center p-2 rounded hover:bg-blue-700 text-blue-100 hover:text-white">
                                <i class="fas fa-clipboard-check mr-3"></i>
                                <span>Log Maintenance</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="p-4 border-t border-blue-700">
                <div class="flex items-center">
                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-user text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium"><?= htmlspecialchars($fullname) ?></p>
                        <p class="text-xs text-blue-300">Technician</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Technician Schedules</h1>

            <?php foreach ($technicians as $techName => $techLogs): ?>
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-xl font-bold text-blue-700 mb-4">
                        <i class="fas fa-user-cog mr-2"></i><?= htmlspecialchars($techName) ?>
                    </h2>
                    <div class="space-y-4">
                        <?php foreach ($techLogs as $log): ?>
                            <div class="border rounded p-4 bg-gray-50 shadow-sm">
                                <div class="flex justify-between items-center mb-1">
                                    <p class="font-semibold text-blue-900"><?= htmlspecialchars($log['unit_code']) ?></p>
                                    <span class="text-xs <?= ($log['status'] ?? 'pending') === 'complete' ? 'text-green-600' : 'text-yellow-500' ?>">
                                        <?= ucfirst($log['status'] ?? 'pending') ?>
                                    </span>
                                </div>
                                <p class="text-sm text-gray-700">
                                    <?= htmlspecialchars($log['maintenance_type']) ?> - <?= htmlspecialchars($log['description']) ?>
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-calendar-alt mr-1"></i><?= date('F j, Y', strtotime($log['maintenance_date'])) ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('-translate-x-full');
        });
    </script>
</body>
</html>