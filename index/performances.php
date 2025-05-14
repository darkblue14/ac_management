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
            <!-- Performance Content -->
            <main class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Performance Analytics</h1>
                    <div class="flex space-x-2">
                        <select class="border rounded px-3 py-1 text-sm bg-white">
                            <option>Last 7 Days</option>
                            <option>Last 30 Days</option>
                            <option selected>Last 90 Days</option>
                            <option>This Year</option>
                        </select>
                        <button class="bg-blue-600 text-white px-4 py-1 rounded text-sm hover:bg-blue-700">
                            <i class="fas fa-download mr-1"></i> Export
                        </button>
                    </div>
                </div>

                <!-- Performance KPIs -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="dashboard-card bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500 text-sm">Maintenance Completion Rate</p>
                                <h3 class="text-2xl font-bold mt-1">94%</h3>
                                <p class="text-sm mt-2 flex items-center">
                                    <span class="text-green-500 mr-1"><i class="fas fa-arrow-up"></i> 2.5%</span>
                                    <span class="text-gray-500">vs last period</span>
                                </p>
                            </div>
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <i class="fas fa-check-circle text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 relative">
                            <div class="performance-meter w-full"></div>
                            <div class="performance-dot bg-blue-600" style="left: 94%;"></div>
                        </div>
                    </div>
                    <div class="dashboard-card bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500 text-sm">Average Response Time</p>
                                <h3 class="text-2xl font-bold mt-1">6.2h</h3>
                                <p class="text-sm mt-2 flex items-center">
                                    <span class="text-green-500 mr-1"><i class="fas fa-arrow-down"></i> 1.3h</span>
                                    <span class="text-gray-500">vs last period</span>
                                </p>
                            </div>
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <i class="fas fa-clock text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 relative">
                            <div class="performance-meter w-full"></div>
                            <div class="performance-dot bg-green-600" style="left: 72%;"></div>
                        </div>
                    </div>
                    <div class="dashboard-card bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500 text-sm">Preventive Maintenance</p>
                                <h3 class="text-2xl font-bold mt-1">78%</h3>
                                <p class="text-sm mt-2 flex items-center">
                                    <span class="text-green-500 mr-1"><i class="fas fa-arrow-up"></i> 5.1%</span>
                                    <span class="text-gray-500">vs last period</span>
                                </p>
                            </div>
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                <i class="fas fa-shield-alt text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 relative">
                            <div class="performance-meter w-full"></div>
                            <div class="performance-dot bg-purple-600" style="left: 78%;"></div>
                        </div>
                    </div>
                    <div class="dashboard-card bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500 text-sm">Energy Efficiency</p>
                                <h3 class="text-2xl font-bold mt-1">8.7/10</h3>
                                <p class="text-sm mt-2 flex items-center">
                                    <span class="text-green-500 mr-1"><i class="fas fa-arrow-up"></i> 0.8</span>
                                    <span class="text-gray-500">vs last period</span>
                                </p>
                            </div>
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                <i class="fas fa-bolt text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 relative">
                            <div class="performance-meter w-full"></div>
                            <div class="performance-dot bg-yellow-600" style="left: 87%;"></div>
                        </div>
                    </div>
                </div>

                <!-- Main Charts -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Maintenance Trend -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold">Maintenance Trend</h2>
                            <div class="flex space-x-2">
                                <button class="px-2 py-1 text-xs bg-blue-100 text-blue-600 rounded">Preventive</button>
                                <button class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded">Corrective</button>
                                <button class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded">Emergency</button>
                            </div>
                        </div>
                        <div class="chart-container">
                            <canvas id="maintenanceTrendChart"></canvas>
                        </div>
                    </div>

                    <!-- Technician Performance -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold">Technician Performance</h2>
                            <select class="border rounded px-3 py-1 text-sm">
                                <option>By Completion Rate</option>
                                <option>By Response Time</option>
                                <option>By Customer Rating</option>
                            </select>
                        </div>
                        <div class="chart-container">
                            <canvas id="technicianChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Detailed Metrics -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <!-- AC Unit Performance -->
                    <div class="bg-white rounded-lg shadow p-6 lg:col-span-2">
                        <h2 class="text-lg font-semibold mb-4">AC Unit Performance</h2>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">AC Unit</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uptime</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Efficiency</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">AC-042</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">Executive Office</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">99.8%</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                            <div class="flex items-center">
                                                <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                                    <div class="bg-green-500 h-2 rounded-full" style="width: 92%"></div>
                                                </div>
                                                <span>9.2/10</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Optimal</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">AC-089</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">Server Room</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">99.5%</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                            <div class="flex items-center">
                                                <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                                    <div class="bg-green-500 h-2 rounded-full" style="width: 88%"></div>
                                                </div>
                                                <span>8.8/10</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Optimal</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">AC-107</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">Open Office</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">97.2%</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                            <div class="flex items-center">
                                                <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                                    <div class="bg-yellow-500 h-2 rounded-full" style="width: 76%"></div>
                                                </div>
                                                <span>7.6/10</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Needs Check</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">AC-015</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">Conference Room</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">95.8%</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                            <div class="flex items-center">
                                                <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                                    <div class="bg-red-500 h-2 rounded-full" style="width: 65%"></div>
                                                </div>
                                                <span>6.5/10</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Attention</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">AC-023</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">Floor 3 Office</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">93.4%</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                            <div class="flex items-center">
                                                <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                                    <div class="bg-red-500 h-2 rounded-full" style="width: 58%"></div>
                                                </div>
                                                <span>5.8/10</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Critical</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Performance Distribution -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold mb-4">Performance Distribution</h2>
                        <div class="chart-container mb-6">
                            <canvas id="performanceDistributionChart"></canvas>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">Preventive Maintenance</span>
                                    <span class="text-sm font-medium text-gray-700">78%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: 78%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">On-Time Completion</span>
                                    <span class="text-sm font-medium text-gray-700">89%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: 89%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">Energy Efficient Units</span>
                                    <span class="text-sm font-medium text-gray-700">64%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-yellow-500 h-2 rounded-full" style="width: 64%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">Customer Satisfaction</span>
                                    <span class="text-sm font-medium text-gray-700">91%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-purple-600 h-2 rounded-full" style="width: 91%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Insights -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-lg font-semibold mb-4">Performance Insights</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="border-l-4 border-blue-500 pl-4 py-1">
                            <p class="text-sm text-gray-500">Top Performing Unit</p>
                            <p class="font-medium">AC-042 (Executive Office)</p>
                            <p class="text-sm text-gray-500">99.8% uptime, 9.2 efficiency score</p>
                        </div>
                        <div class="border-l-4 border-green-500 pl-4 py-1">
                            <p class="text-sm text-gray-500">Most Improved</p>
                            <p class="font-medium">AC-056 (IT Department)</p>
                            <p class="text-sm text-gray-500">+15% efficiency in last quarter</p>
                        </div>
                        <div class="border-l-4 border-yellow-500 pl-4 py-1">
                            <p class="text-sm text-gray-500">Needs Attention</p>
                            <p class="font-medium">AC-023 (Floor 3 Office)</p>
                            <p class="text-sm text-gray-500">93.4% uptime, 5.8 efficiency score</p>
                        </div>
                        <div class="border-l-4 border-purple-500 pl-4 py-1">
                            <p class="text-sm text-gray-500">Best Technician</p>
                            <p class="font-medium">Michael Johnson</p>
                            <p class="text-sm text-gray-500">98% completion rate, 4.9/5 rating</p>
                        </div>
                        <div class="border-l-4 border-red-500 pl-4 py-1">
                            <p class="text-sm text-gray-500">Critical Issue</p>
                            <p class="font-medium">Server Room Cooling</p>
                            <p class="text-sm text-gray-500">Temperature fluctuations detected</p>
                        </div>
                        <div class="border-l-4 border-blue-500 pl-4 py-1">
                            <p class="text-sm text-gray-500">Energy Savings</p>
                            <p class="font-medium">12% reduction</p>
                            <p class="text-sm text-gray-500">Compared to same period last year</p>
                        </div>
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

        // Charts
        document.addEventListener('DOMContentLoaded', function() {
            // Maintenance Trend Chart
            const maintenanceTrendCtx = document.getElementById('maintenanceTrendChart').getContext('2d');
            const maintenanceTrendChart = new Chart(maintenanceTrendCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
                    datasets: [
                        {
                            label: 'Preventive',
                            data: [18, 22, 25, 28, 30, 32, 35, 38, 40],
                            borderColor: 'rgba(59, 130, 246, 0.8)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 2,
                            tension: 0.3,
                            fill: true
                        },
                        {
                            label: 'Corrective',
                            data: [12, 10, 8, 7, 6, 5, 4, 3, 2],
                            borderColor: 'rgba(234, 179, 8, 0.8)',
                            backgroundColor: 'rgba(234, 179, 8, 0.1)',
                            borderWidth: 2,
                            tension: 0.3,
                            fill: true
                        },
                        {
                            label: 'Emergency',
                            data: [5, 4, 3, 2, 2, 1, 1, 1, 0],
                            borderColor: 'rgba(239, 68, 68, 0.8)',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            borderWidth: 2,
                            tension: 0.3,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        }
                    },
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
                    }
                }
            });

            // Technician Performance Chart
            const technicianCtx = document.getElementById('technicianChart').getContext('2d');
            const technicianChart = new Chart(technicianCtx, {
                type: 'bar',
                data: {
                    labels: ['Michael J.', 'Sarah W.', 'Robert C.', 'David K.', 'Lisa M.'],
                    datasets: [
                        {
                            label: 'Completion Rate',
                            data: [98, 95, 92, 89, 85],
                            backgroundColor: 'rgba(16, 185, 129, 0.8)',
                            borderRadius: 4
                        },
                        {
                            label: 'Avg Response (hours)',
                            data: [4.2, 5.1, 6.8, 7.5, 8.2],
                            backgroundColor: 'rgba(59, 130, 246, 0.8)',
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

            // Performance Distribution Chart
            const performanceDistCtx = document.getElementById('performanceDistributionChart').getContext('2d');
            const performanceDistChart = new Chart(performanceDistCtx, {
                type: 'polarArea',
                data: {
                    labels: ['Preventive', 'On-Time', 'Efficiency', 'Satisfaction'],
                    datasets: [{
                        data: [78, 89, 64, 91],
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(234, 179, 8, 0.8)',
                            'rgba(124, 58, 237, 0.8)'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                        }
                    },
                    scales: {
                        r: {
                            pointLabels: {
                                display: false
                            },
                            angleLines: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>