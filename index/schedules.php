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

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow p-6 flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                            <i class="fas fa-calendar-check text-xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Today's Schedules</p>
                            <h3 class="text-2xl font-bold">8</h3>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6 flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                            <i class="fas fa-check-circle text-xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Completed</p>
                            <h3 class="text-2xl font-bold">42</h3>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6 flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Pending</p>
                            <h3 class="text-2xl font-bold">23</h3>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6 flex items-center">
                        <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4">
                            <i class="fas fa-exclamation-triangle text-xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Missed</p>
                            <h3 class="text-2xl font-bold">5</h3>
                        </div>
                    </div>
                </div>

                <!-- Schedule List -->
                <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                    <!-- Schedule List -->
                    <div class="bg-white rounded-lg shadow p-4 lg:col-span-2">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold">Today's Schedules</h2>
                            <div class="flex space-x-2">
                                <select class="border rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option>All Types</option>
                                    <option>Preventive</option>
                                    <option>Corrective</option>
                                    <option>Emergency</option>
                                    <option>Seasonal</option>
                                </select>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <!-- Schedule 1 -->
                            <div class="schedule-card border rounded-lg p-4 hover:shadow-md transition-all duration-200">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-medium">Grand Hotel - York YVAA 50-ton</h3>
                                        <p class="text-sm text-gray-500">Preventive Maintenance</p>
                                    </div>
                                    <span class="status-badge bg-blue-100 text-blue-800">Scheduled</span>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500">
                                    <i class="fas fa-clock mr-1"></i>
                                    <span>9:00 AM - 11:00 AM</span>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500">
                                    <i class="fas fa-user mr-1"></i>
                                    <span>Michael Johnson</span>
                                </div>
                                <div class="mt-3 flex justify-between items-center">
                                    <span class="status-badge bg-yellow-100 text-yellow-800">Medium Priority</span>
                                    <div class="flex space-x-2">
                                        <button class="text-blue-600 hover:text-blue-800 text-sm">
                                            <i class="fas fa-eye mr-1"></i> View
                                        </button>
                                        <button class="text-green-600 hover:text-green-800 text-sm">
                                            <i class="fas fa-check mr-1"></i> Complete
                                        </button>
                                        <button class="text-red-600 hover:text-red-800 text-sm">
                                            <i class="fas fa-times mr-1"></i> Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Schedule 2 -->
                            <div class="schedule-card border rounded-lg p-4 hover:shadow-md transition-all duration-200">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-medium">Tech Office Park - Carrier 30RB 10-ton</h3>
                                        <p class="text-sm text-gray-500">Corrective Maintenance</p>
                                    </div>
                                    <span class="status-badge bg-yellow-100 text-yellow-800">In Progress</span>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500">
                                    <i class="fas fa-clock mr-1"></i>
                                    <span>11:30 AM - 1:30 PM</span>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500">
                                    <i class="fas fa-user mr-1"></i>
                                    <span>Sarah Williams</span>
                                </div>
                                <div class="mt-3 flex justify-between items-center">
                                    <span class="status-badge bg-red-100 text-red-800">High Priority</span>
                                    <div class="flex space-x-2">
                                        <button class="text-blue-600 hover:text-blue-800 text-sm">
                                            <i class="fas fa-eye mr-1"></i> View
                                        </button>
                                        <button class="text-green-600 hover:text-green-800 text-sm">
                                            <i class="fas fa-check mr-1"></i> Complete
                                        </button>
                                        <button class="text-red-600 hover:text-red-800 text-sm">
                                            <i class="fas fa-times mr-1"></i> Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Schedule 3 -->
                            <div class="schedule-card border rounded-lg p-4 hover:shadow-md transition-all duration-200">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-medium">Sunrise Apartments - Daikin FTXS35K</h3>
                                        <p class="text-sm text-gray-500">Seasonal Maintenance</p>
                                    </div>
                                    <span class="status-badge bg-green-100 text-green-800">Completed</span>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500">
                                    <i class="fas fa-clock mr-1"></i>
                                    <span>2:00 PM - 3:30 PM</span>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500">
                                    <i class="fas fa-user mr-1"></i>
                                    <span>David Kim</span>
                                </div>
                                <div class="mt-3 flex justify-between items-center">
                                    <span class="status-badge bg-green-100 text-green-800">Low Priority</span>
                                    <div class="flex space-x-2">
                                        <button class="text-blue-600 hover:text-blue-800 text-sm">
                                            <i class="fas fa-eye mr-1"></i> View
                                        </button>
                                        <button class="text-gray-600 hover:text-gray-800 text-sm">
                                            <i class="fas fa-file-alt mr-1"></i> Report
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Schedule 4 -->
                            <div class="schedule-card border rounded-lg p-4 hover:shadow-md transition-all duration-200">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-medium">City Mall - Trane RTWD 100-ton</h3>
                                        <p class="text-sm text-gray-500">Emergency Repair</p>
                                    </div>
                                    <span class="status-badge bg-red-100 text-red-800">Overdue</span>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500">
                                    <i class="fas fa-clock mr-1"></i>
                                    <span>4:00 PM - 6:00 PM</span>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500">
                                    <i class="fas fa-user mr-1"></i>
                                    <span>Robert Chen</span>
                                </div>
                                <div class="mt-3 flex justify-between items-center">
                                    <span class="status-badge bg-red-100 text-red-800">High Priority</span>
                                    <div class="flex space-x-2">
                                        <button class="text-blue-600 hover:text-blue-800 text-sm">
                                            <i class="fas fa-eye mr-1"></i> View
                                        </button>
                                        <button class="text-green-600 hover:text-green-800 text-sm">
                                            <i class="fas fa-check mr-1"></i> Complete
                                        </button>
                                        <button class="text-red-600 hover:text-red-800 text-sm">
                                            <i class="fas fa-times mr-1"></i> Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Schedules Table -->
                <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                    <div class="p-4 border-b">
                        <h2 class="text-lg font-semibold">Upcoming Schedules</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Schedule ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">AC Unit</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Technician</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <!-- Schedule 1 -->
                                <tr class="schedule-card hover:bg-gray-50 transition-all duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#SC-001</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Grand Hotel</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">York YVAA 50-ton</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Preventive</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">15 Jun, 9:00 AM</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Michael Johnson</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="status-badge bg-blue-100 text-blue-800">Scheduled</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button class="text-blue-600 hover:text-blue-900 mr-3"><i class="fas fa-eye"></i></button>
                                        <button class="text-yellow-600 hover:text-yellow-900 mr-3"><i class="fas fa-edit"></i></button>
                                        <button class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>

                                <!-- Schedule 2 -->
                                <tr class="schedule-card hover:bg-gray-50 transition-all duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#SC-002</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Tech Office Park</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Carrier 30RB 10-ton</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Corrective</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">16 Jun, 11:30 AM</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Sarah Williams</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="status-badge bg-yellow-100 text-yellow-800">Pending</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button class="text-blue-600 hover:text-blue-900 mr-3"><i class="fas fa-eye"></i></button>
                                        <button class="text-yellow-600 hover:text-yellow-900 mr-3"><i class="fas fa-edit"></i></button>
                                        <button class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>

                                <!-- Schedule 3 -->
                                <tr class="schedule-card hover:bg-gray-50 transition-all duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#SC-003</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Sunrise Apartments</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Daikin FTXS35K</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Seasonal</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">17 Jun, 2:00 PM</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">David Kim</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="status-badge bg-green-100 text-green-800">Completed</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button class="text-blue-600 hover:text-blue-900 mr-3"><i class="fas fa-eye"></i></button>
                                        <button class="text-gray-600 hover:text-gray-900 mr-3"><i class="fas fa-file-alt"></i></button>
                                    </td>
                                </tr>

                                <!-- Schedule 4 -->
                                <tr class="schedule-card hover:bg-gray-50 transition-all duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#SC-004</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">City Mall</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Trane RTWD 100-ton</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Emergency</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">14 Jun, 4:00 PM</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Robert Chen</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="status-badge bg-red-100 text-red-800">Missed</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button class="text-blue-600 hover:text-blue-900 mr-3"><i class="fas fa-eye"></i></button>
                                        <button class="text-yellow-600 hover:text-yellow-900 mr-3"><i class="fas fa-edit"></i></button>
                                        <button class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>

                                <!-- Schedule 5 -->
                                <tr class="schedule-card hover:bg-gray-50 transition-all duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#SC-005</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">MediCare Hospital</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Mitsubishi PUMY-P100</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Preventive</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">18 Jun, 10:00 AM</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Anna Martinez</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="status-badge bg-blue-100 text-blue-800">Scheduled</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button class="text-blue-600 hover:text-blue-900 mr-3"><i class="fas fa-eye"></i></button>
                                        <button class="text-yellow-600 hover:text-yellow-900 mr-3"><i class="fas fa-edit"></i></button>
                                        <button class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
                <form>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-medium mb-1">Client</label>
                            <select class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option>Select client</option>
                                <option>Grand Hotel</option>
                                <option>Tech Office Park</option>
                                <option>Sunrise Apartments</option>
                                <option>City Mall</option>
                                <option>MediCare Hospital</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-medium mb-1">AC Unit</label>
                            <select class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option>Select AC unit</option>
                                <option>York YVAA 50-ton</option>
                                <option>Carrier 30RB 10-ton</option>
                                <option>Daikin FTXS35K</option>
                                <option>Trane RTWD 100-ton</option>
                                <option>Mitsubishi PUMY-P100</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-medium mb-1">Technician</label>
                            <select class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option>Assign technician</option>
                                <option>Michael Johnson</option>
                                <option>Sarah Williams</option>
                                <option>Robert Chen</option>
                                <option>David Kim</option>
                                <option>Anna Martinez</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-medium mb-1">Maintenance Date</label>
                            <input type="date" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-medium mb-1">Time Slot</label>
                            <select class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option>Select time slot</option>
                                <option>9:00 AM - 11:00 AM</option>
                                <option>11:30 AM - 1:30 PM</option>
                                <option>2:00 PM - 4:00 PM</option>
                                <option>4:30 PM - 6:30 PM</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-medium mb-1">Priority</label>
                            <select class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option>Select priority</option>
                                <option>High</option>
                                <option>Medium</option>
                                <option>Low</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-1">Description</label>
                        <textarea rows="3" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter maintenance details..."></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-1">Estimated Duration (hours)</label>
                        <input type="number" min="1" max="8" value="2" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </form>
            </div>
            <div class="p-4 border-t flex justify-end space-x-2">
                <button id="cancelScheduleModal" class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">Cancel</button>
                <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save Schedule</button>
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

        // Calendar day selection
        const calendarDays = document.querySelectorAll('.calendar-day');
        calendarDays.forEach(day => {
            day.addEventListener('click', function() {
                // Remove active class from all days
                calendarDays.forEach(d => d.classList.remove('active'));
                // Add active class to clicked day
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>