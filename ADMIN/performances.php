<?php
session_start();
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/ACUnit.php';

// ✅ Cegah akses tanpa login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../SignIn.php");
    exit;
}

$db = new Database();
$conn = $db->connect();

$successMsg = '';
$errorMsg = '';

try {
    $stmt = $conn->prepare("SELECT fullname FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $fullname = $user ? $user['fullname'] : "User";
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_unit'])) {
        $unitCode = $_POST['unit_code'];
        $location = $_POST['location'];
        $status = $_POST['status'];

        if (ACUnit::exists($conn, $unitCode)) {
            $errorMsg = "AC Unit with code <strong>$unitCode</strong> already available.";
        } else {
            $unit = new ACUnit($unitCode, $location, $status);
            if ($unit->save($conn)) {
                $successMsg = "AC Unit berhasil ditambahkan.";
            } else {
                $errorMsg = "Gagal menambahkan AC Unit.";
            }
        }
    }

    if (isset($_POST['delete_id'])) {
        ACUnit::delete($conn, $_POST['delete_id']);
    }
}

$units = ACUnit::getAll($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Performance | CoolCare - AC Maintenance Management</title>
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
        .chart-container {
            max-height: 300px;
        }
        .performance-meter {
            height: 8px;
            border-radius: 4px;
            background: linear-gradient(90deg, #ef4444 0%, #f59e0b 50%, #10b981 100%);
        }
        .performance-dot {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            position: absolute;
            top: -4px;
            transform: translateX(-50%);
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
                            <a href="performances.php" class="flex items-center p-2 rounded bg-blue-700 text-white">
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
            <!-- Performance Content -->
            <main class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Performance Analytics</h1>
                </div>

                <!-- Detailed Metrics -->
                <!-- FORM -->
                <div class="mb-6 bg-white p-6 rounded-lg shadow transition duration-300 hover:shadow-lg hover:scale-[1.02]">

                    <?php if (!empty($successMsg)): ?>
                        <div class="mb-4 px-4 py-3 rounded bg-green-100 text-green-800 border border-green-300">
                            <?= $successMsg ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($errorMsg)): ?>
                        <div class="mb-4 px-4 py-3 rounded bg-red-100 text-red-800 border border-red-300">
                            <?= $errorMsg ?>
                        </div>
                    <?php endif; ?>


                    <form method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <input type="text" name="unit_code" placeholder="AC Unit Code" required class="border rounded px-3 py-2" />
                        <input type="text" name="location" placeholder="Location" required class="border rounded px-3 py-2" />
                        <select name="status" class="border rounded px-3 py-2">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                        <button type="submit" name="add_unit" class="bg-blue-600 text-white rounded px-4 py-2 md:col-span-3">Add Unit</button>
                    </form>
                </div>

                <!-- TABLE -->
                <div class="bg-white rounded-lg shadow p-4 transition duration-300 hover:shadow-lg hover:scale-[1.02]">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Unit Code</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($units as $unit): ?>
                                <tr class="border-b">
                                    <td class="px-4 py-2"><?= htmlspecialchars($unit['unit_code']) ?></td>
                                    <td class="px-4 py-2"><?= htmlspecialchars($unit['location']) ?></td>
                                    <td class="px-4 py-2 text-green-600"><?= htmlspecialchars($unit['status']) ?></td>
                                    <td class="px-4 py-2 text-right">
                                        <form method="POST" onsubmit="return confirm('Delete this unit?');" style="display:inline;">
                                            <input type="hidden" name="delete_id" value="<?= $unit['id'] ?>">
                                            <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
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