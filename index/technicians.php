<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoolCare - Technicians Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            transition: all 0.3s ease;
        }
        .technician-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .avatar-ring {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .avatar-ring::after {
            content: '';
            position: absolute;
            top: -3px;
            left: -3px;
            right: -3px;
            bottom: -3px;
            border-radius: 50%;
            border: 2px solid;
            z-index: -1;
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
                            <a href="technicians.php" class="flex items-center p-2 rounded bg-blue-700 text-white">
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
            <!-- Technicians Content -->
            <main class="p-6">
                <!-- Header and Add Button -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Technicians</h1>
                        <p class="text-gray-600">Manage your AC maintenance technicians</p>
                    </div>
                    <button id="addTechnicianBtn" class="mt-4 md:mt-0 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 flex items-center">
                        <i class="fas fa-plus mr-2"></i> Add Technician
                    </button>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow p-6 flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Total Technicians</p>
                            <h3 class="text-2xl font-bold">24</h3>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6 flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                            <i class="fas fa-check-circle text-xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Available Today</p>
                            <h3 class="text-2xl font-bold">18</h3>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6 flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">On Leave</p>
                            <h3 class="text-2xl font-bold">4</h3>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6 flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                            <i class="fas fa-star text-xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Avg. Rating</p>
                            <h3 class="text-2xl font-bold">4.7</h3>
                        </div>
                    </div>
                </div>

                <!-- Search and Filter -->
                <div class="bg-white rounded-lg shadow p-4 mb-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" class="pl-10 pr-4 py-2 w-full border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search technicians...">
                        </div>
                        <div class="flex space-x-2">
                            <select class="border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option>All Specializations</option>
                                <option>Residential AC</option>
                                <option>Commercial AC</option>
                                <option>Industrial AC</option>
                                <option>HVAC Systems</option>
                            </select>
                            <select class="border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option>All Status</option>
                                <option>Available</option>
                                <option>On Leave</option>
                                <option>On Job</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Technicians Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <!-- Technician Card 1 -->
                    <div class="technician-card bg-white rounded-lg shadow overflow-hidden transition-all duration-300">
                        <div class="p-4 flex flex-col items-center text-center">
                            <div class="avatar-ring mb-3">
                                <div class="h-20 w-20 rounded-full bg-blue-100 flex items-center justify-center text-2xl font-bold text-blue-600">
                                    MJ
                                </div>
                            </div>
                            <h3 class="text-lg font-semibold">Michael Johnson</h3>
                            <p class="text-sm text-gray-500">Senior Technician</p>
                            <div class="flex mt-2">
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star-half-alt text-yellow-400"></i>
                                <span class="text-xs text-gray-500 ml-1">4.7</span>
                            </div>
                        </div>
                        <div class="border-t px-4 py-3 bg-gray-50">
                            <div class="flex justify-between items-center">
                                <span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded-full">Available</span>
                                <div class="flex space-x-2">
                                    <button class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Technician Card 2 -->
                    <div class="technician-card bg-white rounded-lg shadow overflow-hidden transition-all duration-300">
                        <div class="p-4 flex flex-col items-center text-center">
                            <div class="avatar-ring mb-3">
                                <div class="h-20 w-20 rounded-full bg-purple-100 flex items-center justify-center text-2xl font-bold text-purple-600">
                                    SW
                                </div>
                            </div>
                            <h3 class="text-lg font-semibold">Sarah Williams</h3>
                            <p class="text-sm text-gray-500">Commercial Specialist</p>
                            <div class="flex mt-2">
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <span class="text-xs text-gray-500 ml-1">5.0</span>
                            </div>
                        </div>
                        <div class="border-t px-4 py-3 bg-gray-50">
                            <div class="flex justify-between items-center">
                                <span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded-full">Available</span>
                                <div class="flex space-x-2">
                                    <button class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Technician Card 3 -->
                    <div class="technician-card bg-white rounded-lg shadow overflow-hidden transition-all duration-300">
                        <div class="p-4 flex flex-col items-center text-center">
                            <div class="avatar-ring mb-3">
                                <div class="h-20 w-20 rounded-full bg-red-100 flex items-center justify-center text-2xl font-bold text-red-600">
                                    RC
                                </div>
                            </div>
                            <h3 class="text-lg font-semibold">Robert Chen</h3>
                            <p class="text-sm text-gray-500">HVAC Engineer</p>
                            <div class="flex mt-2">
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="far fa-star text-yellow-400"></i>
                                <span class="text-xs text-gray-500 ml-1">4.2</span>
                            </div>
                        </div>
                        <div class="border-t px-4 py-3 bg-gray-50">
                            <div class="flex justify-between items-center">
                                <span class="text-xs px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full">On Job</span>
                                <div class="flex space-x-2">
                                    <button class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Technician Card 4 -->
                    <div class="technician-card bg-white rounded-lg shadow overflow-hidden transition-all duration-300">
                        <div class="p-4 flex flex-col items-center text-center">
                            <div class="avatar-ring mb-3">
                                <div class="h-20 w-20 rounded-full bg-green-100 flex items-center justify-center text-2xl font-bold text-green-600">
                                    DK
                                </div>
                            </div>
                            <h3 class="text-lg font-semibold">David Kim</h3>
                            <p class="text-sm text-gray-500">Residential Expert</p>
                            <div class="flex mt-2">
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <span class="text-xs text-gray-500 ml-1">5.0</span>
                            </div>
                        </div>
                        <div class="border-t px-4 py-3 bg-gray-50">
                            <div class="flex justify-between items-center">
                                <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-full">On Leave</span>
                                <div class="flex space-x-2">
                                    <button class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Technician Card 5 -->
                    <div class="technician-card bg-white rounded-lg shadow overflow-hidden transition-all duration-300">
                        <div class="p-4 flex flex-col items-center text-center">
                            <div class="avatar-ring mb-3">
                                <div class="h-20 w-20 rounded-full bg-yellow-100 flex items-center justify-center text-2xl font-bold text-yellow-600">
                                    AM
                                </div>
                            </div>
                            <h3 class="text-lg font-semibold">Anna Martinez</h3>
                            <p class="text-sm text-gray-500">Industrial Specialist</p>
                            <div class="flex mt-2">
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star-half-alt text-yellow-400"></i>
                                <span class="text-xs text-gray-500 ml-1">4.6</span>
                            </div>
                        </div>
                        <div class="border-t px-4 py-3 bg-gray-50">
                            <div class="flex justify-between items-center">
                                <span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded-full">Available</span>
                                <div class="flex space-x-2">
                                    <button class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Technician Card 6 -->
                    <div class="technician-card bg-white rounded-lg shadow overflow-hidden transition-all duration-300">
                        <div class="p-4 flex flex-col items-center text-center">
                            <div class="avatar-ring mb-3">
                                <div class="h-20 w-20 rounded-full bg-indigo-100 flex items-center justify-center text-2xl font-bold text-indigo-600">
                                    TJ
                                </div>
                            </div>
                            <h3 class="text-lg font-semibold">Thomas James</h3>
                            <p class="text-sm text-gray-500">Apprentice</p>
                            <div class="flex mt-2">
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="fas fa-star text-yellow-400"></i>
                                <i class="far fa-star text-yellow-400"></i>
                                <i class="far fa-star text-yellow-400"></i>
                                <span class="text-xs text-gray-500 ml-1">3.0</span>
                            </div>
                        </div>
                        <div class="border-t px-4 py-3 bg-gray-50">
                            <div class="flex justify-between items-center">
                                <span class="text-xs px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full">On Job</span>
                                <div class="flex space-x-2">
                                    <button class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add New Technician Card -->
                    <div class="technician-card bg-white rounded-lg shadow overflow-hidden transition-all duration-300 border-2 border-dashed border-gray-300 hover:border-blue-500 flex items-center justify-center cursor-pointer" id="addTechnicianCardBtn">
                        <div class="p-6 text-center">
                            <div class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-plus text-blue-500 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-700">Add New Technician</h3>
                            <p class="text-sm text-gray-500">Click to add a new technician</p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal for Add Technician -->
    <div id="addTechnicianModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="p-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-semibold">Add New Technician</h3>
                <button id="closeTechnicianModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-4">
                <form>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-1">Full Name</label>
                        <input type="text" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter full name">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-1">Email</label>
                        <input type="email" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter email">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-1">Phone</label>
                        <input type="tel" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter phone number">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-1">Specialization</label>
                        <select class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option>Select specialization</option>
                            <option>Residential AC</option>
                            <option>Commercial AC</option>
                            <option>Industrial AC</option>
                            <option>HVAC Systems</option>
                            <option>General Maintenance</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-1">Status</label>
                        <select class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option>Available</option>
                            <option>On Leave</option>
                            <option>On Job</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-1">Profile Photo</label>
                        <div class="mt-1 flex items-center">
                            <span class="inline-block h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                                <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </span>
                            <button type="button" class="ml-5 bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Change
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="p-4 border-t flex justify-end space-x-2">
                <button id="cancelTechnicianModal" class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">Cancel</button>
                <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Add Technician</button>
            </div>
        </div>
    </div>

    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('-translate-x-full');
        });

        // Modal handling for technicians
        const openModalButtons = [
            document.getElementById('addTechnicianBtn'),
            document.getElementById('addTechnicianCardBtn')
        ];
        
        const closeModalButtons = [
            document.getElementById('closeTechnicianModal'),
            document.getElementById('cancelTechnicianModal')
        ];

        const technicianModal = document.getElementById('addTechnicianModal');

        openModalButtons.forEach(button => {
            button.addEventListener('click', function() {
                technicianModal.classList.remove('hidden');
            });
        });

        closeModalButtons.forEach(button => {
            button.addEventListener('click', function() {
                technicianModal.classList.add('hidden');
            });
        });

        // Close modal when clicking outside
        technicianModal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    </script>
</body>
</html>