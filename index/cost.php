<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cost Analysis | CoolCare - AC Maintenance Management</title>
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
        .cost-meter {
            height: 8px;
            border-radius: 4px;
            background: linear-gradient(90deg, #10b981 0%, #f59e0b 50%, #ef4444 100%);
        }
        .cost-dot {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            position: absolute;
            top: -4px;
            transform: translateX(-50%);
        }
        .cost-trend-up {
            color: #ef4444;
        }
        .cost-trend-down {
            color: #10b981;
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
                            <a href="performances.php" class="flex items-center p-2 rounded hover:bg-blue-700 text-blue-100 hover:text-white">
                                <i class="fas fa-chart-bar mr-3"></i>
                                <span>Performance</span>
                            </a>
                        </li>
                        <li class="mb-1">
                            <a href="cost.php" class="flex items-center p-2 rounded bg-blue-700 text-white">
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
            <!-- Cost Analysis Content -->
            <main class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Cost Analysis</h1>
                    <div class="flex space-x-2">
                        <select class="border rounded px-3 py-1 text-sm bg-white">
                            <option>Last Quarter</option>
                            <option selected>Last 6 Months</option>
                            <option>Last Year</option>
                            <option>Year to Date</option>
                        </select>
                        <button class="bg-blue-600 text-white px-4 py-1 rounded text-sm hover:bg-blue-700">
                            <i class="fas fa-download mr-1"></i> Export
                        </button>
                    </div>
                </div>

                <!-- Cost KPIs -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="dashboard-card bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500 text-sm">Total Maintenance Cost</p>
                                <h3 class="text-2xl font-bold mt-1">$24,580</h3>
                                <p class="text-sm mt-2 flex items-center">
                                    <span class="cost-trend-up mr-1"><i class="fas fa-arrow-up"></i> 8.2%</span>
                                    <span class="text-gray-500">vs last period</span>
                                </p>
                            </div>
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <i class="fas fa-dollar-sign text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 relative">
                            <div class="cost-meter w-full"></div>
                            <div class="cost-dot bg-blue-600" style="left: 68%;"></div>
                        </div>
                    </div>
                    <div class="dashboard-card bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500 text-sm">Preventive vs Corrective</p>
                                <h3 class="text-2xl font-bold mt-1">3.2:1</h3>
                                <p class="text-sm mt-2 flex items-center">
                                    <span class="cost-trend-down mr-1"><i class="fas fa-arrow-down"></i> 12%</span>
                                    <span class="text-gray-500">cost ratio</span>
                                </p>
                            </div>
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <i class="fas fa-balance-scale text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 relative">
                            <div class="cost-meter w-full"></div>
                            <div class="cost-dot bg-green-600" style="left: 76%;"></div>
                        </div>
                    </div>
                    <div class="dashboard-card bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500 text-sm">Energy Cost Savings</p>
                                <h3 class="text-2xl font-bold mt-1">$3,210</h3>
                                <p class="text-sm mt-2 flex items-center">
                                    <span class="cost-trend-down mr-1"><i class="fas fa-arrow-down"></i> 15%</span>
                                    <span class="text-gray-500">vs last year</span>
                                </p>
                            </div>
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                <i class="fas fa-bolt text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 relative">
                            <div class="cost-meter w-full"></div>
                            <div class="cost-dot bg-purple-600" style="left: 85%;"></div>
                        </div>
                    </div>
                    <div class="dashboard-card bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500 text-sm">Cost per Unit</p>
                                <h3 class="text-2xl font-bold mt-1">$320</h3>
                                <p class="text-sm mt-2 flex items-center">
                                    <span class="cost-trend-up mr-1"><i class="fas fa-arrow-up"></i> 4.5%</span>
                                    <span class="text-gray-500">vs last period</span>
                                </p>
                            </div>
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                <i class="fas fa-calculator text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 relative">
                            <div class="cost-meter w-full"></div>
                            <div class="cost-dot bg-yellow-600" style="left: 62%;"></div>
                        </div>
                    </div>
                </div>

                <!-- Main Charts -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Cost Breakdown -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold">Cost Breakdown</h2>
                            <div class="flex space-x-2">
                                <button class="px-2 py-1 text-xs bg-blue-100 text-blue-600 rounded">By Category</button>
                                <button class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded">By Location</button>
                                <button class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded">By Unit Type</button>
                            </div>
                        </div>
                        <div class="chart-container">
                            <canvas id="costBreakdownChart"></canvas>
                        </div>
                    </div>

                    <!-- Cost Trend -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold">Cost Trend</h2>
                            <select class="border rounded px-3 py-1 text-sm">
                                <option>All Costs</option>
                                <option>Preventive Only</option>
                                <option>Corrective Only</option>
                                <option>Emergency Only</option>
                            </select>
                        </div>
                        <div class="chart-container">
                            <canvas id="costTrendChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Detailed Metrics -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <!-- High Cost Units -->
                    <div class="bg-white rounded-lg shadow p-6 lg:col-span-2">
                        <h2 class="text-lg font-semibold mb-4">High Cost Units</h2>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">AC Unit</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Maintenance Cost</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Energy Cost</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Cost</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">AC-023</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">Floor 3 Office</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">$1,250</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">$480</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-gray-900">$1,730</td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Critical</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">AC-107</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">Open Office</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">$980</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">$420</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-gray-900">$1,400</td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Needs Check</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">AC-015</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">Conference Room</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">$750</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">$380</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-gray-900">$1,130</td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Needs Check</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">AC-089</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">Server Room</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">$620</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">$350</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-gray-900">$970</td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Monitor</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">AC-042</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">Executive Office</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">$450</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">$290</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-gray-900">$740</td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Optimal</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Cost Savings Opportunities -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold mb-4">Cost Savings Opportunities</h2>
                        <div class="space-y-4">
                            <div class="p-4 border border-blue-200 rounded-lg bg-blue-50">
                                <div class="flex items-start">
                                    <div class="p-2 rounded-full bg-blue-100 text-blue-600 mr-3">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-medium">Preventive Maintenance</h3>
                                        <p class="text-sm text-gray-600">Increase preventive maintenance by 15% could save ~$2,800 annually</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 border border-green-200 rounded-lg bg-green-50">
                                <div class="flex items-start">
                                    <div class="p-2 rounded-full bg-green-100 text-green-600 mr-3">
                                        <i class="fas fa-bolt"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-medium">Energy Efficiency</h3>
                                        <p class="text-sm text-gray-600">Upgrade 5 oldest units could reduce energy costs by ~$1,500/year</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 border border-purple-200 rounded-lg bg-purple-50">
                                <div class="flex items-start">
                                    <div class="p-2 rounded-full bg-purple-100 text-purple-600 mr-3">
                                        <i class="fas fa-tools"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-medium">Parts Inventory</h3>
                                        <p class="text-sm text-gray-600">Optimize parts inventory could reduce emergency costs by ~$1,200</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 border border-yellow-200 rounded-lg bg-yellow-50">
                                <div class="flex items-start">
                                    <div class="p-2 rounded-full bg-yellow-100 text-yellow-600 mr-3">
                                        <i class="fas fa-user-clock"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-medium">Technician Training</h3>
                                        <p class="text-sm text-gray-600">Advanced training could reduce service time by 20%, saving ~$900</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cost Insights -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-lg font-semibold mb-4">Cost Insights</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="border-l-4 border-blue-500 pl-4 py-1">
                            <p class="text-sm text-gray-500">Highest Cost Category</p>
                            <p class="font-medium">Corrective Maintenance</p>
                            <p class="text-sm text-gray-500">$14,200 (58% of total)</p>
                        </div>
                        <div class="border-l-4 border-green-500 pl-4 py-1">
                            <p class="text-sm text-gray-500">Best Cost Performer</p>
                            <p class="font-medium">Floor 2 West Wing</p>
                            <p class="text-sm text-gray-500">$320/unit (below avg)</p>
                        </div>
                        <div class="border-l-4 border-red-500 pl-4 py-1">
                            <p class="text-sm text-gray-500">Highest Cost Unit</p>
                            <p class="font-medium">AC-023 (Floor 3)</p>
                            <p class="text-sm text-gray-500">$1,730 total cost</p>
                        </div>
                        <div class="border-l-4 border-purple-500 pl-4 py-1">
                            <p class="text-sm text-gray-500">Emergency Cost Trend</p>
                            <p class="font-medium">Decreasing 18%</p>
                            <p class="text-sm text-gray-500">Due to better PM schedule</p>
                        </div>
                        <div class="border-l-4 border-yellow-500 pl-4 py-1">
                            <p class="text-sm text-gray-500">Parts Cost Increase</p>
                            <p class="font-medium">Up 12% YoY</p>
                            <p class="text-sm text-gray-500">Supply chain issues</p>
                        </div>
                        <div class="border-l-4 border-blue-500 pl-4 py-1">
                            <p class="text-sm text-gray-500">Potential Savings</p>
                            <p class="font-medium">$6,400 annually</p>
                            <p class="text-sm text-gray-500">With recommended changes</p>
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
            // Cost Breakdown Chart
            const costBreakdownCtx = document.getElementById('costBreakdownChart').getContext('2d');
            const costBreakdownChart = new Chart(costBreakdownCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Preventive', 'Corrective', 'Emergency', 'Energy', 'Parts', 'Labor'],
                    datasets: [{
                        data: [6800, 14200, 3600, 5200, 3800, 7500],
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(234, 179, 8, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(124, 58, 237, 0.8)',
                            'rgba(249, 115, 22, 0.8)'
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
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: $${value.toLocaleString()} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    cutout: '70%'
                }
            });

            // Cost Trend Chart
            const costTrendCtx = document.getElementById('costTrendChart').getContext('2d');
            const costTrendChart = new Chart(costTrendCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
                    datasets: [
                        {
                            label: 'Total Cost',
                            data: [2800, 3100, 2950, 3200, 3400, 3600, 3800, 4000, 4200],
                            borderColor: 'rgba(59, 130, 246, 0.8)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 2,
                            tension: 0.3,
                            fill: true
                        },
                        {
                            label: 'Preventive',
                            data: [1200, 1250, 1300, 1350, 1400, 1450, 1500, 1550, 1600],
                            borderColor: 'rgba(16, 185, 129, 0.8)',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 2,
                            tension: 0.3,
                            fill: true
                        },
                        {
                            label: 'Corrective',
                            data: [1400, 1600, 1400, 1600, 1700, 1800, 1900, 2000, 2100],
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
                            callbacks: {
                                label: function(context) {
                                    const label = context.dataset.label || '';
                                    const value = context.raw || 0;
                                    return `${label}: $${value.toLocaleString()}`;
                                }
                            }
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
                            },
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>