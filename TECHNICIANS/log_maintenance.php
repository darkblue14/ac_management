<?php
session_start();
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/MaintenanceLog.php';
require_once __DIR__ . '/../classes/ACUnit.php';

$isAdmin = $_SESSION['is_admin'] ?? false;
$userId = $_SESSION['user_id'] ?? null;

$db = new Database();
$pdo = $db->connect();

$units = ACUnit::getAll($pdo);

$logs = MaintenanceLog::getMaintenanceLogsWithUnitCode($pdo);

$fullname = "Unknown";
if ($userId) {
    $stmt = $pdo->prepare("SELECT fullname FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $fullname = $user ? $user['fullname'] : "User";
}

$successMsg = "";
$errorMsg = "";

// Proses saat form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $unitId = $_POST['unit_id'] ?? '';
    $maintenanceDate = $_POST['maintenanceDate'] ?? '';
    $maintenanceType = $_POST['maintenanceType'] ?? '';
    $technicianName = $fullname;
    $workPerformed = $_POST['workPerformed'] ?? '';
    $photoPath = null;

    // Upload file
    if (isset($_FILES['maintenancePhoto']) && $_FILES['maintenancePhoto']['error'] == 0) {
        $uploadDir = __DIR__ . '/../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $fileName = uniqid() . '_' . basename($_FILES['maintenancePhoto']['name']);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['maintenancePhoto']['tmp_name'], $targetFile)) {
            $photoPath = 'uploads/' . $fileName;
        } else {
            $errorMsg = "Failed to upload photo.";
        }
    }

    $log = new MaintenanceLog($unitId, $maintenanceDate, $maintenanceType, $technicianName, $workPerformed, $photoPath);

    if ($log->saveToDatabase($pdo)) {
        $successMsg = "Maintenance log successfully saved!";
    } else {
        $errorMsg = "Failed to save maintenance log.";
    }
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
                            <a href="index.php" class="flex items-center p-2 rounded hover:bg-blue-700 text-blue-100 hover:text-white tab-link">
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
                            <a href="log_maintenance.php" class="flex items-center p-2 rounded bg-blue-700 text-blue-100 text-white tab-link">
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
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Maintenance Log Form</h1>
                </div>

                <!-- Maintenance Log Form -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">

                    <?php if (!empty($successMsg)): ?>
                        <div class="mb-4 p-3 bg-green-200 text-green-800 rounded"><?= htmlspecialchars($successMsg) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($errorMsg)): ?>
                        <div class="mb-4 p-3 bg-red-200 text-red-800 rounded"><?= htmlspecialchars($errorMsg) ?></div>
                    <?php endif; ?>

                    <form id="maintenanceForm" class="space-y-6" method="POST" action="log_maintenance.php" enctype="multipart/form-data">
                        <!-- Section 1: Basic Information -->
                        <div class="border-b border-gray-200 pb-4">
                            <h2 class="text-lg font-medium text-gray-900 mb-4">
                                <i class="fas fa-info-circle text-blue-500 mr-2"></i>Basic Information
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="unitId" class="block text-sm font-medium text-gray-700 mb-1">AC Unit</label>
                                    <select name="unit_id" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                        <option value="">Select Unit</option>
                                            <?php foreach ($units as $unit): ?>
                                                <option value="<?= htmlspecialchars($unit['id']) ?>">
                                                    <?= htmlspecialchars($unit['unit_code']) ?> (<?= htmlspecialchars($unit['location']) ?>)
                                                </option>
                                            <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label for="maintenanceDate" class="block text-sm font-medium text-gray-700 mb-1">Maintenance Date</label>
                                    <input type="date" id="maintenanceDate" name="maintenanceDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 border" required>
                                </div>
                                <div>
                                    <label for="maintenanceType" class="block text-sm font-medium text-gray-700 mb-1">Maintenance Type</label>
                                    <select id="maintenanceType" name="maintenanceType" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 border" required>
                                        <option value="">Select Type</option>
                                        <option value="Routine">Routine Maintenance</option>
                                        <option value="Corrective">Corrective Maintenance</option>
                                        <option value="Emergency">Emergency Repair</option>
                                        <option value="Installation">New Installation</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="technicianName" class="block text-sm font-medium text-gray-700 mb-1">Technician Name</label>
                                    <input type="text" id="technicianName" name="technicianName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 border" value="<?php echo htmlspecialchars($fullname); ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Maintenance Details -->
                        <div class="border-b border-gray-200 py-4">
                            <h2 class="text-lg font-medium text-gray-900 mb-4">
                                <i class="fas fa-tools text-blue-500 mr-2"></i>Maintenance Details
                            </h2>
                            <div class="grid grid-cols-1 gap-6">
                                    <textarea id="workPerformed" name="workPerformed" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 border" placeholder="Describe the maintenance work performed..." required></textarea>
                            </div>
                        </div>

                        <!-- Section 3: Documentation -->
                        <div class="py-4">
                            <h2 class="text-lg font-medium text-gray-900 mb-4">
                                <i class="fas fa-camera text-blue-500 mr-2"></i>Documentation
                            </h2>
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="maintenancePhoto" class="block text-sm font-medium text-gray-700 mb-1">Upload Photos</label>
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md w-full">
                                        <div class="space-y-1 text-center w-full">
                                            <div class="flex flex-col items-center text-sm text-gray-600 w-full">
                                                <label for="maintenancePhoto" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none w-full">
                                                    <img id="photoPreview" src="#" alt="Preview" class="mt-2 rounded-md max-h-40 mx-auto">
                                                    <span>Upload a file</span>
                                                    <input id="maintenancePhoto" name="maintenancePhoto" type="file" class="sr-only" accept="image/*">
                                                </label>
                                                <p class="text-xs text-gray-500 mt-1">PNG, JPG up to 5MB</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-center space-x-4 pt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-save mr-2"></i> Submit Maintenance Log
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
    </div>

    <script>
        // Preview gambar
        document.getElementById('maintenancePhoto').addEventListener('change', function(event) {
            const input = event.target;
            const preview = document.getElementById('photoPreview');

            if (input.files && input.files[0]) {
                const file = input.files[0];

                // Cek apakah file adalah gambar
                if (!file.type.startsWith('image/')) {
                    alert('File yang dipilih bukan gambar.');
                    preview.src = "#";
                    preview.style.display = 'none';
                    return;
                }

                // Baca file dan tampilkan preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                preview.src = "#";
                preview.style.display = 'none';
            }
        });

        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('-translate-x-full');
        });
    </script>
</body>
</html>