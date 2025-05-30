<?php
session_start();

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/MaintenanceLog.php';
require_once __DIR__ . '/../classes/ACUnit.php';
require_once __DIR__ . '/../classes/User.php';

// Selalu koneksi di awal
$db = new Database();
$conn = $db->connect();

try {
    $stmt = $conn->prepare("SELECT fullname FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $fullname = $user ? $user['fullname'] : "User";
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Daftar email admin
$allowedAdminEmails = [
    'admin@gmail.com',
    'fajri@gmail.com',
    'firja@gmail.com'
];

$user = new User($db); // <- Inisialisasi objek User
$technicians = $user->getAllTechniciansExcludeAdmins($allowedAdminEmails); 


$units = ACUnit::getAll($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_log'])) {
    $unitId = $_POST['unit_id'] ?? '';
    $date = $_POST['maintenance_date'] ?? '';
    $type = $_POST['maintenance_type'] ?? '';
    $technician = $_POST['technician_name'] ?? '';
    $description = $_POST['description'] ?? '';

    $log = new MaintenanceLog($unitId, $date, $type, $technician, $description);
    $log->saveToDatabase($conn);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_log_id'])) {
    $deleteId = $_POST['delete_log_id'];
    $stmt = $conn->prepare("DELETE FROM maintenance_logs WHERE id = ?");
    $stmt->execute([$deleteId]);
}

// Ambil semua log
$stmt = $conn->query("
        SELECT ms.*, au.unit_code 
        FROM maintenance_logs ms
        JOIN ac_units au ON ms.unit_id = au.id
        ORDER BY ms.maintenance_date DESC
    ");
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoolCare - Maintenance Logs</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            transition: all 0.3s ease;
        }
        .log-card:hover {
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
                            <a href="maintenance_logs.php" class="flex items-center p-2 rounded bg-blue-700 text-white">
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
            <!-- Maintenance Logs Content -->
            <main class="p-6">
                <!-- Header and Add Button -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Maintenance Logs</h1>
                        <p class="text-gray-600">Track and manage all AC maintenance activities</p>
                    </div>
                    <button id="addLogBtn" class="mt-4 md:mt-0 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 flex items-center">
                        <i class="fas fa-plus mr-2"></i> Add Log
                    </button>
                </div>

                <!-- Maintenance Logs Table -->
                <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">AC Unit</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Technician</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Work Performed</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($logs as $log): ?>
                                    <tr class="log-card hover:bg-gray-50 transition-all duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($log['unit_code']) ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($log['maintenance_type']) ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($log['technician_name']) ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('d M Y', strtotime($log['maintenance_date'])) ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php
                                            $status = htmlspecialchars($log['status']);
                                            $isComplete = strtolower($status) === 'complete';
                                            ?>
                                            <span class="status-badge inline-block px-2 py-1 rounded-full text-xs font-semibold
                                                <?= $isComplete ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                                <?= ucfirst($status) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($log['work_performed']) ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button class="text-blue-600 hover:text-blue-900 mr-3"><i class="fas fa-eye"></i></button>
                                            <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                                                <input type="hidden" name="delete_log_id" value="<?= $log['id'] ?>">
                                                <button type="submit" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Single Log Card Display -->
                    <div id="logCardDetail" class="hidden bg-white rounded-lg shadow p-6 mt-4 border border-gray-200">

                    </div>

                </div>
            </main>
        </div>
    </div>

    <!-- Modal for Add Log -->
    <div id="addLogModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl">
        <form method="POST" class="flex flex-col h-full">
            <div class="p-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-semibold">Add New Maintenance Log</h3>
                <button type="button" id="closeLogModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-4 flex-1 overflow-y-auto">
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
                        <input type="date" name="maintenance_date" required class="w-full border rounded px-3 py-2">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-1">Maintenance Type</label>
                        <select name="maintenance_type" required class="w-full border rounded px-3 py-2">
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
                    <textarea name="description" rows="3" class="w-full border rounded px-3 py-2" placeholder="Enter maintenance details..."></textarea>
                </div>
            </div>
            <div class="p-4 border-t flex justify-end space-x-2">
                <button type="button" id="cancelLogModal" class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">Cancel</button>
                <button type="submit" name="save_log" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save Log</button>
            </div>
        </form>
    </div>
    </div>

    <!-- Modal Detail Log -->
    <div id="viewLogModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl">
            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-semibold">Maintenance Log Details</h3>
                <button id="closeViewLog" class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>
            <div class="px-6 py-4 space-y-3">
                <div><strong>AC Unit:</strong> <span id="logUnit"></span></div>
                <div><strong>Technician:</strong> <span id="logTechnician"></span></div>
                <div><strong>Maintenance Date:</strong> <span id="logDate"></span></div>
                <div><strong>Maintenance Type:</strong> <span id="logType"></span></div>
                <div><strong>Status:</strong> <span id="logStatus" class="inline-block px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800"></span></div>
                <div><strong>Description:</strong><br><p id="logDescription" class="mt-1 text-gray-700 whitespace-pre-line"></p></div>
            </div>
            <div class="px-6 py-4 border-t flex justify-end">
                <button id="closeViewLogFooter" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Close</button>
            </div>
        </div>
    </div>

    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('-translate-x-full');
        });

        // Modal handling for logs
        const addLogBtn = document.getElementById('addLogBtn');
        const closeLogModalButtons = [
            document.getElementById('closeLogModal'),
            document.getElementById('cancelLogModal')
        ];

        const logModal = document.getElementById('addLogModal');

        addLogBtn.addEventListener('click', function() {
            logModal.classList.remove('hidden');
        });

        closeLogModalButtons.forEach(button => {
            button.addEventListener('click', function() {
                logModal.classList.add('hidden');
            });
        });

        // Close modal when clicking outside
        logModal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });

        // Card view
        const maintenanceLogs = <?= json_encode($logs) ?>;

        document.addEventListener("DOMContentLoaded", () => {
        const buttons = document.querySelectorAll(".fa-eye");
        const modal = document.getElementById("viewLogModal");

        const logUnit = document.getElementById("logUnit");
        const logTechnician = document.getElementById("logTechnician");
        const logDate = document.getElementById("logDate");
        const logType = document.getElementById("logType");
        const logStatus = document.getElementById("logStatus");
        const logDescription = document.getElementById("logDescription");

            buttons.forEach((btn, index) => {
                btn.addEventListener("click", () => {
                    const log = maintenanceLogs[index];

                    logUnit.textContent = log.unit_id;
                    logTechnician.textContent = log.technician_name;
                    logDate.textContent = new Date(log.maintenance_date).toLocaleDateString();
                    logType.textContent = log.maintenance_type;
                    logStatus.textContent = log.status;
                    logDescription.textContent = log.work_performed || "-";

                    modal.classList.remove("hidden");
                });
            });

        // Close modal
        document.getElementById("closeViewLog").addEventListener("click", () => modal.classList.add("hidden"));
        document.getElementById("closeViewLogFooter").addEventListener("click", () => modal.classList.add("hidden"));
        
        });
    </script>
</body>
</html>