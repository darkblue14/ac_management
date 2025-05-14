
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
                        <li class="mb-1">
                            <a href="cost.php" class="flex items-center p-2 rounded hover:bg-blue-700 text-blue-100 hover:text-white">
                                <i class="fas fa-file-invoice-dollar mr-3"></i>
                                <span>Cost Analysis</span>
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
                        <p class="text-sm font-medium">John Doe</p>
                        <p class="text-xs text-blue-300">Admin</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <!-- Dashboard Content -->
            <main class="p-6">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="dashboard-card bg-white rounded-lg shadow p-6 flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                            <i class="fas fa-snowflake text-xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Total AC Units</p>
                            <h3 class="text-2xl font-bold">142</h3>
                        </div>
                    </div>
                    <div class="dashboard-card bg-white rounded-lg shadow p-6 flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                            <i class="fas fa-check-circle text-xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Maintained This Month</p>
                            <h3 class="text-2xl font-bold">87</h3>
                        </div>
                    </div>
                    <div class="dashboard-card bg-white rounded-lg shadow p-6 flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                            <i class="fas fa-exclamation-triangle text-xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Pending Maintenance</p>
                            <h3 class="text-2xl font-bold">15</h3>
                        </div>
                    </div>
                    <div class="dashboard-card bg-white rounded-lg shadow p-6 flex items-center">
                        <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4">
                            <i class="fas fa-times-circle text-xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Overdue Maintenance</p>
                            <h3 class="text-2xl font-bold">8</h3>
                        </div>
                    </div>
                </div>

                <!-- Charts and Main Content -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <!-- Maintenance Status Chart -->
                    <div class="bg-white rounded-lg shadow p-6 lg:col-span-2">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold">Maintenance Status</h2>
                            <select class="border rounded px-3 py-1 text-sm">
                                <option>Last 7 Days</option>
                                <option>Last 30 Days</option>
                                <option selected>Last 90 Days</option>
                            </select>
                        </div>
                        <canvas id="maintenanceChart" height="300"></canvas>
                    </div>

                    <!-- AC Status Overview -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold mb-4">AC Status Overview</h2>
                        <canvas id="statusChart" height="300"></canvas>
                        <div class="mt-4 grid grid-cols-2 gap-2">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                <span class="text-sm">Operational (78%)</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                                <span class="text-sm">Needs Check (12%)</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                                <span class="text-sm">Not Working (6%)</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-gray-500 rounded-full mr-2"></div>
                                <span class="text-sm">Under Repair (4%)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activities and Upcoming Maintenance -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Recent Activities -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-4 border-b">
                            <h2 class="text-lg font-semibold">Recent Activities</h2>
                        </div>
                        <div class="divide-y">
                            <div class="p-4 flex items-start">
                                <div class="p-2 rounded-full bg-blue-100 text-blue-600 mr-3">
                                    <i class="fas fa-tools"></i>
                                </div>
                                <div>
                                    <p class="font-medium">Maintenance completed for AC-042</p>
                                    <p class="text-sm text-gray-500">By Technician: Michael Johnson</p>
                                    <p class="text-xs text-gray-400">2 hours ago</p>
                                </div>
                            </div>
                            <div class="p-4 flex items-start">
                                <div class="p-2 rounded-full bg-green-100 text-green-600 mr-3">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div>
                                    <p class="font-medium">Scheduled maintenance for AC-107</p>
                                    <p class="text-sm text-gray-500">Scheduled for: June 15, 2023</p>
                                    <p class="text-xs text-gray-400">Today, 10:45 AM</p>
                                </div>
                            </div>
                            <div class="p-4 flex items-start">
                                <div class="p-2 rounded-full bg-yellow-100 text-yellow-600 mr-3">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div>
                                    <p class="font-medium">Issue reported for AC-023</p>
                                    <p class="text-sm text-gray-500">Reported by: Floor 3 Reception</p>
                                    <p class="text-xs text-gray-400">Yesterday, 4:30 PM</p>
                                </div>
                            </div>
                            <div class="p-4 flex items-start">
                                <div class="p-2 rounded-full bg-purple-100 text-purple-600 mr-3">
                                    <i class="fas fa-sync-alt"></i>
                                </div>
                                <div>
                                    <p class="font-medium">Filter replacement for AC-056</p>
                                    <p class="text-sm text-gray-500">Parts ordered: New filter</p>
                                    <p class="text-xs text-gray-400">Yesterday, 11:20 AM</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upcoming Maintenance -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-4 border-b flex justify-between items-center">
                            <h2 class="text-lg font-semibold">Upcoming Maintenance</h2>
                            <button class="text-blue-600 text-sm font-medium">View All</button>
                        </div>
                        <div class="divide-y">
                            <div class="p-4 flex justify-between items-center">
                                <div>
                                    <p class="font-medium">AC-015 - Conference Room</p>
                                    <p class="text-sm text-gray-500">Routine Checkup</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium">Tomorrow</p>
                                    <p class="text-xs text-gray-500">9:00 AM</p>
                                </div>
                            </div>
                            <div class="p-4 flex justify-between items-center">
                                <div>
                                    <p class="font-medium">AC-089 - Server Room</p>
                                    <p class="text-sm text-gray-500">Critical Maintenance</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium">Jun 12, 2023</p>
                                    <p class="text-xs text-gray-500">10:30 AM</p>
                                </div>
                            </div>
                            <div class="p-4 flex justify-between items-center">
                                <div>
                                    <p class="font-medium">AC-042 - Executive Office</p>
                                    <p class="text-sm text-gray-500">Filter Replacement</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium">Jun 14, 2023</p>
                                    <p class="text-xs text-gray-500">2:00 PM</p>
                                </div>
                            </div>
                            <div class="p-4 flex justify-between items-center">
                                <div>
                                    <p class="font-medium">AC-107 - Open Office</p>
                                    <p class="text-sm text-gray-500">Quarterly Maintenance</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium">Jun 15, 2023</p>
                                    <p class="text-xs text-gray-500">9:30 AM</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal for Add Maintenance -->
    <div id="addMaintenanceModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="p-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-semibold">Schedule New Maintenance</h3>
                <button id="closeModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-4">
                <form>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-1">AC Unit</label>
                        <select class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option>Select AC Unit</option>
                            <option>AC-001 - Reception</option>
                            <option>AC-015 - Conference Room</option>
                            <option>AC-023 - Floor 3 Office</option>
                            <option>AC-042 - Executive Office</option>
                            <option>AC-056 - IT Department</option>
                            <option>AC-089 - Server Room</option>
                            <option>AC-107 - Open Office</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-1">Maintenance Type</label>
                        <select class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option>Routine Checkup</option>
                            <option>Filter Replacement</option>
                            <option>Coolant Refill</option>
                            <option>Electrical Check</option>
                            <option>Full Service</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-1">Scheduled Date</label>
                        <input type="date" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-1">Assigned Technician</label>
                        <select class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option>Michael Johnson</option>
                            <option>Sarah Williams</option>
                            <option>Robert Chen</option>
                            <option>David Kim</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-1">Notes</label>
                        <textarea class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="p-4 border-t flex justify-end space-x-2">
                <button id="cancelModal" class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">Cancel</button>
                <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Schedule Maintenance</button>
            </div>
        </div>
    </div>

    <!-- Floating Action Button -->
    <div class="fixed bottom-6 right-6">
        <button id="addMaintenanceBtn" class="w-14 h-14 bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700 flex items-center justify-center">
            <i class="fas fa-plus text-xl"></i>
        </button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('-translate-x-full');
        });

        // Modal handling
        document.getElementById('addMaintenanceBtn').addEventListener('click', function() {
            document.getElementById('addMaintenanceModal').classList.remove('hidden');
        });

        document.getElementById('closeModal').addEventListener('click', function() {
            document.getElementById('addMaintenanceModal').classList.add('hidden');
        });

        document.getElementById('cancelModal').addEventListener('click', function() {
            document.getElementById('addMaintenanceModal').classList.add('hidden');
        });

        // Close modal when clicking outside
        document.getElementById('addMaintenanceModal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });

        // Charts
        document.addEventListener('DOMContentLoaded', function() {
            // Maintenance Status Chart
            const maintenanceCtx = document.getElementById('maintenanceChart').getContext('2d');
            const maintenanceChart = new Chart(maintenanceCtx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
                    datasets: [
                        {
                            label: 'Completed',
                            data: [12, 19, 15, 21, 18, 25, 22, 30, 28],
                            backgroundColor: 'rgba(59, 130, 246, 0.7)',
                            borderRadius: 4
                        },
                        {
                            label: 'Pending',
                            data: [5, 8, 6, 9, 7, 10, 8, 5, 3],
                            backgroundColor: 'rgba(234, 179, 8, 0.7)',
                            borderRadius: 4
                        },
                        {
                            label: 'Overdue',
                            data: [2, 3, 1, 4, 2, 3, 1, 2, 1],
                            backgroundColor: 'rgba(239, 68, 68, 0.7)',
                            borderRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [5, 5]
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });

            // AC Status Chart
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            const statusChart = new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Operational', 'Needs Check', 'Not Working', 'Under Repair'],
                    datasets: [{
                        data: [78, 12, 6, 4],
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(234, 179, 8, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(107, 114, 128, 0.8)'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
