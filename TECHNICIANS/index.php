<?php
session_start();
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/MaintenanceLog.php';
require_once __DIR__ . '/../classes/MaintenanceSchedule.php';

if (!isset($_SESSION['user_id'])) {
    // Belum login, redirect ke halaman login
    header("Location: SignIn.php");
    exit;
}

$isAdmin = $_SESSION['is_admin'] ?? false;

$db = new Database();
$pdo = $db->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_id'])) {
    $scheduleId = $_POST['complete_id'];
    MaintenanceSchedule::markAsComplete($pdo, $scheduleId);
    header("Location: index.php"); // Refresh halaman agar perubahan muncul
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_log_id'])) {
    $logId = $_POST['complete_log_id'];
    $stmt = $pdo->prepare("UPDATE maintenance_logs SET status = 'complete' WHERE id = ?");
    $stmt->execute([$logId]);
    header("Location: index.php"); // Refresh agar tombol hilang setelah update
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT fullname FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $fullname = $user ? $user['fullname'] : "User";
    $_SESSION['fullname'] = $fullname;
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Ambil 5 jadwal terbaru untuk teknisi yang sedang login
$stmt = $pdo->prepare("
    SELECT s.*, u.unit_code, u.location 
    FROM maintenance_schedules s 
    JOIN ac_units u ON s.unit_id = u.id
    WHERE s.technician_name = ?
    ORDER BY s.created_at DESC 
    LIMIT 5
");
$stmt->execute([$fullname]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SESSION['is_admin']) {
    // Admin bisa melihat semua log
    $logs = MaintenanceLog::getMaintenanceLogsWithUnitCode($pdo);
} else {
    // Teknisi hanya bisa melihat log yang sesuai dengan namanya
    $technicianName = $_SESSION['fullname'] ?? $fullname;
    $logs = MaintenanceLog::getLogsByTechnician($pdo, $technicianName);
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
                    <p class="text-blue-300 uppercase text-xs font-semibold mb-2">Technician Menu</p>
                    <ul>
                        <li class="mb-1">
                            <a href="index.php" class="flex items-center p-2 rounded bg-blue-700 text-blue-100 text-white tab-link">
                                <i class="fas fa-tachometer-alt mr-3"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="mb-1">
                            <a href="schedule_view.php" class="flex items-center p-2 rounded hover:bg-blue-700 text-blue-100 hover:text-white tab-link">
                                <i class="fas fa-calendar-alt mr-3"></i>
                                <span>Schedule View</span>
                            </a>
                        </li>
                        <li class="mb-1">
                            <a href="log_maintenance.php" class="flex items-center p-2 rounded hover:bg-blue-700 text-blue-100 hover:text-white tab-link">
                                <i class="fas fa-clipboard-check mr-3"></i>
                                <span>Log Maintenance</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="absolute bottom-0 p-4 w-64">
                <div class="flex items-center">
                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-user text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium"><?php echo htmlspecialchars($fullname); ?></p>
                        <p class="text-xs text-blue-300">Technician</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <!-- Dashboard Content -->
            <main class="p-6">
                <!-- Dashboard Tab -->
                <div id="dashboard" class="tab-content active">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold text-gray-800">Technician Dashboard</h1>
                    </div>
                    <!-- Recent Activity and Notifications -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

                        <!-- Maintenance Logs -->
                        <div class="lg:col-span-2">
                            <div class="card-hover bg-white rounded-lg shadow p-5">
                                <div class="flex justify-between items-center mb-4">
                                    <h2 class="text-lg font-semibold">Maintenance Log</h2>
                                </div>
                                <div class="space-y-4">
                                    <?php if (empty($logs)): ?>
                                        <p class="text-gray-500 text-sm">No maintenance logs found.</p>
                                    <?php else: ?>
                                        <?php foreach ($logs as $log): ?>
                                            <div class="flex items-start border-b pb-3">
                                                <div class="p-2 rounded-full bg-blue-100 text-blue-600 mr-3">
                                                    <i class="fas fa-snowflake"></i>
                                                </div>
                                                <div class="w-full">
                                                    <p class="font-medium"><?= htmlspecialchars($log['unit_code']) ?></p>
                                                    <p class="text-sm text-gray-600"><?= htmlspecialchars($log['maintenance_type']) ?> - <?= htmlspecialchars($log['technician_name']) ?></p>
                                                    <p class="text-sm text-gray-600"><?= htmlspecialchars($log['work_performed']) ?></p>
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        <i class="fas fa-calendar-alt mr-1"></i>
                                                        <?= date("F j, Y", strtotime($log['maintenance_date'])) ?>
                                                    </p>

                                                    <!-- Status Badge -->
                                                    <p class="mt-1">
                                                        <span class="inline-block text-xs font-semibold px-2 py-1 rounded-full
                                                            <?= $log['status'] === 'complete' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                                            <?= ucfirst(htmlspecialchars($log['status'])) ?>
                                                        </span>
                                                    </p>

                                                    <!-- Complete Button if status is pending -->
                                                    <?php if ($log['status'] !== 'complete'): ?>
                                                        <form method="POST" class="mt-2">
                                                            <input type="hidden" name="complete_log_id" value="<?= $log['id'] ?>">
                                                            <button type="submit" class="text-green-600 hover:text-green-800 text-sm mt-1">
                                                                <i class="fas fa-check mr-1"></i> Mark as Complete
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>

                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Notifications -->
                        <div class="card-hover bg-white rounded-lg shadow p-5">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-lg font-semibold">Notifications</h2>
                            </div>
                            <div class="space-y-3">
                                <?php if (empty($notifications)): ?>
                                    <p class="text-gray-500 text-sm">No new schedule.</p>
                                <?php else: ?>
                                    <?php foreach ($notifications as $note): ?>
                                        <div class="p-3 bg-blue-50 rounded-lg">
                                            <p class="text-sm font-medium">New schedule added</p>
                                            <p class="text-xs text-gray-600"><?= htmlspecialchars($note['unit_code']) ?> - <?= htmlspecialchars($note['location']) ?></p>
                                            <p class="text-xs text-gray-600">
                                                <i class="fas fa-calendar-alt mr-1"></i><?= htmlspecialchars($note['maintenance_date']) ?>
                                            </p>
                                            <p class="text-xs text-gray-500 italic"><?= htmlspecialchars($note['maintenance_type']) ?> - <?= htmlspecialchars($note['description']) ?></p>
                                            <?php
                                            $status = $note['status'] ?? 'pending';
                                            $statusColor = $status === 'complete' ? 'text-green-600' : 'text-yellow-500';
                                            ?>
                                            <p class="text-xs font-semibold mt-1 <?= $statusColor ?>">
                                                Status: <?= htmlspecialchars($status) ?>
                                            </p>

                                            <!-- Right side: buttons -->
                                            <?php if (($note['status'] ?? 'pending') !== 'complete'): ?>
                                                <div class="flex space-x-2 mt-2">
                                                    <form method="POST">
                                                        <input type="hidden" name="complete_id" value="<?= $note['id'] ?>">
                                                        <button type="submit" class="text-green-600 hover:text-green-800 text-sm">
                                                            <i class="fas fa-check mr-1"></i> Complete
                                                        </button>
                                                    </form>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
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