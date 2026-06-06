<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetCare | Client Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* --- RESET & RESPONSIVE VARIABLES --- */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }

        /* Interactive styling enhancements for icons */
        .notification-bell:hover { color: #6366f1 !important; transform: scale(1.1); transition: all 0.2s ease; }
        .action-icon:hover { color: #ef4444 !important; transform: scale(1.1); transition: all 0.2s ease; }
        .pet-item { cursor: pointer; transition: background 0.2s; }
        .pet-item:hover { background-color: #f1f5f9; color: #0f172a !important; }

        /* --- GLOBAL APP CONTAINER --- */
        .app-container {
            display: grid;
            grid-template-columns: 260px 1fr;
            min-height: 100vh;
        }

        /* --- RESPONSIVE SIDEBAR --- */
        .sidebar {
            background-color: #ffffff;
            border-right: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            padding: 24px 0;
            z-index: 100;
            transition: transform 0.3s ease;
        }
        .sidebar .sidebar-brand {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 24px;
            margin-bottom: 30px;
        }
        .sidebar .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: #6366f1;
        }
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.25rem;
            color: #475569;
            cursor: pointer;
        }
        .sidebar-content {
            display: flex;
            flex-direction: column;
            flex: 1;
        }
        .nav-links {
            list-style: none;
            flex: 1;
        }
        .nav-links li.active {
            background-color: #f1f5f9;
            color: #6366f1;
            font-weight: 600;
        }
        .nav-links li {
            padding: 12px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #475569;
            transition: all 0.2s ease;
        }
        .nav-links li:hover:not(.section-header):not(.pet-container-li) {
            background-color: #f8fafc;
            color: #0f172a;
            cursor: pointer;
        }
        #sidebarPetList {
            list-style: none;
            padding-left: 0;
        }
        
        /* Sidebar Dynamic Row Interactive Anchors */
        .sidebar-pet-link {
            display: flex;
            align-items: center;
            padding: 8px 24px;
            color: #475569;
            text-decoration: none;
            font-size: 0.9rem;
            transition: background 0.2s, color 0.2s;
            cursor: pointer;
        }
        .sidebar-pet-link:hover {
            background-color: #f1f5f9;
            color: #0f172a;
        }
        .sidebar-pet-link .pet-icon {
            margin-right: 10px;
            font-size: 1.1rem;
        }
        .sidebar-pet-link .pet-breed-badge {
            margin-left: auto;
            font-size: 0.75rem;
            color: #94a3b8;
            background: #f8fafc;
            padding: 2px 6px;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
        }

        .btn-logout {
            margin: auto 24px 0;
            padding: 12px;
            background: #fee2e2;
            color: #dc2626;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.2s;
            width: calc(100% - 48px);
        }
        .btn-logout:hover { background: #fecaca; }

        /* --- MAIN INNER CONTENT --- */
        .main-content {
            padding: 40px;
            max-width: 1400px;
            width: 100%;
            margin: 0 auto;
        }

        /* --- TOP HEADER RESPONSIVENESS --- */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
            gap: 20px;
            flex-wrap: wrap;
        }
        .top-bar-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .top-bar h1 {
            font-size: 1.75rem;
            color: #0f172a;
        }
        .top-bar p.welcome-tag {
            color: #64748b;
            font-size: 0.95rem;
            margin-top: 4px;
        }
        .action-group {
            display: flex;
            align-items: center;
            gap: 24px;
        }
        .book-btn {
            background-color: #6366f1;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .book-btn:hover { background-color: #4f46e5; }

        /* --- NOTIFICATION POP PANEL CONFIGS --- */
        .notif-container {
            position: relative;
        }
        .notif-panel {
            position: absolute;
            top: 45px;
            right: 0;
            width: 340px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            display: none;
            z-index: 1000;
            overflow: hidden;
        }
        .notif-header {
            padding: 12px 16px;
            background: #f8fafc;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .notif-header h4 { margin: 0; color: #1e293b; font-size: 0.95rem; }
        .clear-notif-btn { background: none; border: none; color: #6366f1; font-size: 0.8rem; font-weight: 600; cursor: pointer; }
        .clear-notif-btn:hover { text-decoration: underline; }
        .notif-list { max-height: 280px; overflow-y: auto; list-style: none; }
        .notif-item { padding: 12px 16px; border-bottom: 1px solid #f1f5f9; display: flex; gap: 12px; align-items: start; font-size: 0.85rem; color: #4b5563; }
        .notif-item.read { opacity: 0.6; background: #fdfdfd; }
        .notif-empty { padding: 20px; text-align: center; color: #9ca3af; font-size: 0.85rem; }

        /* --- TABLES & CONTENT CARDS --- */
        .appointments-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .appointments-card h3 {
            margin-bottom: 20px;
            color: #0f172a;
            font-size: 1.2rem;
        }
        
        .table-responsive-wrapper {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            min-width: 650px;
        }
        th, td {
            padding: 14px 16px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.95rem;
        }
        th {
            background-color: #f8fafc;
            color: #64748b;
            font-weight: 600;
        }

        /* Status Badge CSS styling utilities */
        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
            text-transform: capitalize;
        }
        .status-pending { background-color: #fef3c7; color: #d97706; }
        .status-approved { background-color: #d1fae5; color: #059669; }
        .status-requested { background-color: #e2e8f0; color: #475569; }
        .status-cancelled { background-color: #fee2e2; color: #dc2626; }

        /* --- FLOATING MODAL INTERFACES --- */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 16px;
            transition: opacity 0.25s ease;
        }
        .modal-overlay.hidden {
            opacity: 0;
            pointer-events: none;
        }
        .modal-box {
            background: #ffffff;
            width: 100%;
            max-width: 480px;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
            padding: 24px;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        /* Two column structural layout modification for the scheduling UI */
        .modal-box.wide-layout {
            max-width: 760px;
        }
        .modal-split-container {
            display: flex;
            gap: 24px;
        }
        .modal-main-form {
            flex: 1;
        }
        .modal-side-panel {
            width: 240px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
        }
        .modal-side-panel h4 {
            font-size: 0.95rem;
            color: #334155;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .schedule-list {
            list-style: none;
            font-size: 0.85rem;
            line-height: 1.7;
            color: #475569;
        }
        .schedule-list li {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            border-bottom: 1px dashed #e2e8f0;
        }
        .schedule-list li:last-child {
            border-bottom: none;
        }
        .schedule-list li.closed-day {
            color: #ef4444;
            font-weight: 600;
        }
        .schedule-alert-box {
            margin-top: 12px;
            padding: 10px;
            background: #fff1f2;
            border: 1px solid #fecdd3;
            border-radius: 8px;
            color: #be123c;
            font-size: 0.8rem;
            display: none;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 12px;
        }
        .modal-header h3 { margin: 0; color: #1e293b; font-size: 1.25rem; }
        .close-x-btn { background: none; border: none; font-size: 1.5rem; color: #64748b; cursor: pointer; }
        
        .modal-form .form-group { display: flex; flex-direction: column; margin-bottom: 16px; }
        .modal-form label { font-size: 0.875rem; font-weight: 500; color: #475569; margin-bottom: 6px; }
        .modal-form input, .modal-form select { padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; outline: none; font-family: inherit; }
        .modal-form input:focus, .modal-form select:focus { border-color: #6366f1; }
        
        .form-row { display: flex; gap: 16px; }
        .form-row .form-group.half { flex: 1; }
        
        .modal-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; border-top: 1px solid #f1f5f9; padding-top: 16px; }
        .btn-modal-primary { background-color: #6366f1; color: white; border: none; padding: 10px 18px; border-radius: 8px; font-weight: 600; cursor: pointer; }
        .btn-modal-primary:hover { background-color: #4f46e5; }
        .btn-modal-primary:disabled { background-color: #94a3b8; cursor: not-allowed; }
        .btn-modal-secondary { background-color: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; padding: 10px 18px; border-radius: 8px; font-weight: 600; cursor: pointer; }
        .btn-modal-danger { background-color: #fee2e2; color: #dc2626; border: 1px solid #fecaca; padding: 10px 18px; border-radius: 8px; font-weight: 600; cursor: pointer; margin-right: auto;}
        .btn-modal-danger:hover { background-color: #fecaca; }

        /* --- RESPONSIVE MEDIA QUERIES --- */
        @media (max-width: 768px) {
            .app-container { grid-template-columns: 1fr; }
            .sidebar { 
                position: fixed;
                left: 0;
                top: 0;
                bottom: 0;
                width: 260px;
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .mobile-menu-toggle { 
                display: block; 
            }
            .main-content {
                padding: 20px;
            }
            .top-bar h1 { font-size: 1.5rem; }
            .notif-panel {
                right: -60px;
                width: 300px;
            }
            .modal-split-container {
                flex-direction: column;
            }
            .modal-side-panel {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="app-container">
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <div class="logo">PetCare</div>
                <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <div class="sidebar-content">
                <ul class="nav-links">
                    <li class="active"><i class="fa-solid fa-house"></i> My Dashboard</li>
                    <li class="section-header" style="color:#888; font-size:0.75rem; padding:20px 20px 5px; display:flex; justify-content:space-between; align-items:center;">
                        PETS <button id="openAddPetModalBtn" style="cursor:pointer; border:none; background:none; color:#6366f1; font-weight:bold; font-size:1.1rem;">+</button>
                    </li>
                    <li class="pet-container-li" style="padding: 0; display: block; background: transparent;">
                        <ul id="sidebarPetList"></ul>
                    </li>
                </ul>

                <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <button class="btn-logout" onclick="handleLogout()">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </button>
            </div>
        </nav>

        <main class="main-content">
            <header class="top-bar">
                <div class="top-bar-left">
                    <button class="mobile-menu-toggle" onclick="toggleMobileMenu()" style="margin-right: 4px;">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <div>
                        <h1>My Pet Profiles</h1>
                        <p class="welcome-tag">Welcome back, {{ Auth::user()->full_name ?? 'Pet Owner' }}</p>
                    </div>
                </div>
                <div class="action-group">
                    <div class="notif-container">
                        <div id="bellBtn" class="notification-bell" onclick="toggleNotifPanel(event)" style="position: relative; cursor: pointer; color: #4b5563; font-size: 1.3rem;">
                            <i class="fa-solid fa-bell"></i>
                            <span id="notifBadge" style="position: absolute; top: -8px; right: -8px; background: #ef4444; color: white; border-radius: 50%; padding: 2px 7px; font-size: 0.7rem; font-weight: bold; display: none;">0</span>
                        </div>

                        <div id="notifPanel" class="notif-panel">
                            <div class="notif-header">
                                <h4>Notifications</h4>
                                <button class="clear-notif-btn" onclick="clearNotifications()">Mark all as read</button>
                            </div>
                            <ul id="notifList" class="notif-list"></ul>
                        </div>
                    </div>
                    
                    <button id="openBookModalBtn" class="book-btn">+ Book Appointment</button>
                </div>
            </header>

            <section class="appointments-card">
                <h3>Upcoming Appointments</h3>
                <div class="table-responsive-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Pet</th>
                                <th>Date & Time</th>
                                <th>Service</th>
                                <th>Status</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="appBody">
                            <tr><td colspan="5" style="text-align: center; color: #94a3b8; padding: 30px;">Loading data engine schedules...</td></tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <div id="addPetModal" class="modal-overlay hidden">
        <div class="modal-box">
            <div class="modal-header">
                <h3>Add New Pet Profile</h3>
                <button id="closePetXBtn" class="close-x-btn">&times;</button>
            </div>
            <form id="addPetForm" class="modal-form">
                <div class="form-group">
                    <label for="pet_name">Pet Name</label>
                    <input type="text" id="pet_name" name="pet_name" required placeholder="e.g., Buddy">
                </div>
                <div class="form-group">
                    <label for="species">Species</label>
                    <select id="species" name="type" required>
                        <option value="" disabled selected>Select species</option>
                        <option value="Dog">Dog</option>
                        <option value="Cat">Cat</option>
                        <option value="Bird">Bird</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="breed">Breed</label>
                    <input type="text" id="breed" name="breed" required placeholder="e.g., Golden Retriever">
                </div>
                <div class="form-row">
                    <div class="form-group half">
                        <label for="birthday">Birthday</label>
                        <input type="date" id="birthday" name="birthdate" required>
                    </div>
                    <div class="form-group half">
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender" required>
                            <option value="" disabled selected>Select</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" id="cancelPetBtn" class="btn-modal-secondary">Cancel</button>
                    <button type="submit" id="submitPetBtn" class="btn-modal-primary">Save Pet</button>
                </div>
            </form>
        </div>
    </div>

    <div id="managePetModal" class="modal-overlay hidden">
        <div class="modal-box">
            <div class="modal-header">
                <h3>Manage Pet Profile</h3>
                <button id="closeManagePetXBtn" class="close-x-btn">&times;</button>
            </div>
            <form id="managePetForm" class="modal-form">
                <input type="hidden" id="edit_pet_id">
                <div class="form-group">
                    <label for="edit_pet_name">Pet Name</label>
                    <input type="text" id="edit_pet_name" required>
                </div>
                <div class="form-group">
                    <label for="edit_species">Species</label>
                    <select id="edit_species" required>
                        <option value="Dog">Dog</option>
                        <option value="Cat">Cat</option>
                        <option value="Bird">Bird</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit_breed">Breed</label>
                    <input type="text" id="edit_breed" required>
                </div>
                <div class="form-row">
                    <div class="form-group half">
                        <label for="edit_birthday">Birthday</label>
                        <input type="date" id="edit_birthday" required>
                    </div>
                    <div class="form-group half">
                        <label for="edit_gender">Gender</label>
                        <select id="edit_gender" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" id="deletePetBtn" class="btn-modal-danger" onclick="handleDeletePet()">Delete Profile</button>
                    <button type="button" id="cancelManagePetBtn" class="btn-modal-secondary">Cancel</button>
                    <button type="submit" id="updatePetBtn" class="btn-modal-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <div id="bookModal" class="modal-overlay hidden">
        <div class="modal-box wide-layout">
            <div class="modal-header">
                <h3>Schedule New Appointment</h3>
                <button id="closeBookXBtn" class="close-x-btn">&times;</button>
            </div>
            
            <div class="modal-split-container">
                <form id="bookAppointmentForm" class="modal-form modal-main-form">
                    <div class="form-group">
                        <label for="book_pet_id">Select Pet Profile</label>
                        <select id="book_pet_id" name="pet_id" required>
                            <option value="" disabled selected>Choose registered pet</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="service_type">Service Requested</label>
                        <select id="service_type" name="service_type" required>
                            <option value="" disabled selected>Select treatment profile</option>
                            <option value="General Checkup">General Checkup</option>
                            <option value="Vaccination">Vaccination</option>
                            <option value="Pet Grooming">Pet Grooming</option>
                            <option value="Surgery / Treatment">Surgery / Treatment</option>
                        </select>
                    </div>
                    <div class="form-row">
                        <div class="form-group half">
                            <label for="appointment_date">Preferred Date</label>
                            <input type="date" id="appointment_date" name="appointment_date" required oninput="validateClinicHours()">
                        </div>
                        <div class="form-group half">
                            <label for="appointment_time">Preferred Time</label>
                            <input type="time" id="appointment_time" name="appointment_time" required oninput="validateClinicHours()">
                        </div>
                    </div>
                    
                    <div class="modal-actions">
                        <button type="button" id="cancelBookBtn" class="btn-modal-secondary">Cancel</button>
                        <button type="submit" id="submitBookBtn" class="btn-modal-primary">Confirm Booking</button>
                    </div>
                </form>

                <div class="modal-side-panel">
                    <h4><i class="fa-solid fa-clock" style="color: #6366f1;"></i> Clinic Hours</h4>
                    <ul class="schedule-list">
                        <li><span>Monday</span> <span>9:00 AM - 5:00 PM</span></li>
                        <li><span>Tuesday</span> <span>9:00 AM - 5:00 PM</span></li>
                        <li class="closed-day"><span>Wednesday</span> <span>CLOSED</span></li>
                        <li><span>Thursday</span> <span>9:00 AM - 5:00 PM</span></li>
                        <li><span>Friday</span> <span>9:00 AM - 5:00 PM</span></li>
                        <li><span>Saturday</span> <span>9:00 AM - 5:00 PM</span></li>
                        <li class="closed-day"><span>Sunday</span> <span>CLOSED</span></li>
                    </ul>
                    <div id="clinicValidationAlert" class="schedule-alert-box"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let registeredPetsArray = [];

        document.addEventListener('DOMContentLoaded', () => {
            loadPetProfiles();
            loadUpcomingAppointments();
            setupGlobalClickHandlers();
            setupFormSubmissionListeners();
            
            const todayStr = new Date().toISOString().split('T')[0];
            const dateInput = document.getElementById('appointment_date');
            if (dateInput) dateInput.min = todayStr;
        });

        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
        }

        function getHeaders() {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            return {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest'
            };
        }

        /* --- LOGOUT IMPLEMENTATION --- */
        function handleLogout() {
            if (confirm("Are you sure you want to log out of your dashboard session?")) {
                document.getElementById('logoutForm').submit();
            }
        }

        /* --- RESPONSIVE SIDEBAR & NOTIFICATIONS --- */
        function toggleMobileMenu() {
            document.getElementById('sidebar').classList.toggle('open');
        }

        function toggleNotifPanel(event) {
            if (event) event.stopPropagation();
            const panel = document.getElementById('notifPanel');
            panel.style.display = (panel.style.display === 'block') ? 'none' : 'block';
        }

        function clearNotifications() {
            document.getElementById('notifList').innerHTML = '<li class="notif-empty">No new system updates.</li>';
            const badge = document.getElementById('notifBadge');
            badge.style.display = 'none';
            badge.innerText = '0';
        }

        document.addEventListener('click', () => {
            const panel = document.getElementById('notifPanel');
            if (panel) panel.style.display = 'none';
        });

        /* --- CLINIC COMPLIANCE ENGINE --- */
        function validateClinicHours() {
            const dateVal = document.getElementById('appointment_date').value;
            const timeVal = document.getElementById('appointment_time').value;
            const alertBox = document.getElementById('clinicValidationAlert');
            const submitBtn = document.getElementById('submitBookBtn');

            alertBox.style.display = "none";
            alertBox.innerHTML = "";
            submitBtn.disabled = false;

            if (!dateVal) return;

            const dateParts = dateVal.split('-');
            const selectedDate = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]);
            const dayOfWeek = selectedDate.getDay(); 

            if (dayOfWeek === 0 || dayOfWeek === 3) {
                const offDayName = (dayOfWeek === 3) ? "Wednesdays" : "Sundays";
                alertBox.innerHTML = `<i class="fa-solid fa-circle-exclamation"></i> <strong>Notice:</strong> The clinic is closed on <b>${offDayName}</b>.`;
                alertBox.style.display = "block";
                submitBtn.disabled = true;
                return;
            }

            if (timeVal) {
                const timeParts = timeVal.split(':');
                const hour = parseInt(timeParts[0], 10);
                const minutes = parseInt(timeParts[1], 10);
                const totalMinutes = (hour * 60) + minutes;

                if (totalMinutes < (9 * 60) || totalMinutes > (17 * 60)) {
                    alertBox.innerHTML = `<i class="fa-solid fa-circle-exclamation"></i> <strong>Notice:</strong> Hours are <b>9:00 AM to 5:00 PM</b>.`;
                    alertBox.style.display = "block";
                    submitBtn.disabled = true;
                }
            }
        }

        /* --- FETCH AND RENDER PETS PROFILE --- */
        async function loadPetProfiles() {
            try {
                const response = await fetch("/api/pets", { headers: getHeaders() });
                const data = await response.json();
                registeredPetsArray = data.pets || data;

                const sidebarPetList = document.getElementById('sidebarPetList');
                const bookPetSelect = document.getElementById('book_pet_id');

                sidebarPetList.innerHTML = '';
                bookPetSelect.innerHTML = '<option value="" disabled selected>Choose registered pet</option>';

                if (!Array.isArray(registeredPetsArray) || registeredPetsArray.length === 0) {
                    sidebarPetList.innerHTML = '<li style="padding: 10px 24px; font-size:0.85rem; color:#94a3b8;">No profile entries saved</li>';
                    return;
                }

                registeredPetsArray.forEach(pet => {
                    const id = pet.pet_id || pet.id;
                    sidebarPetList.innerHTML += `
                        <li>
                            <div class="sidebar-pet-link" onclick="openManagePetModal(${id})">
                                <span class="pet-icon"><i class="fa-solid fa-paw"></i></span>
                                <strong>${escapeHtml(pet.pet_name)}</strong>
                                <span class="pet-breed-badge">${escapeHtml(pet.breed)}</span>
                            </div>
                        </li>
                    `;
                    bookPetSelect.innerHTML += `<option value="${id}">${escapeHtml(pet.pet_name)}</option>`;
                });
            } catch (err) {
                console.error("Critical pet engine data capture failure:", err);
            }
        }

        /* --- FETCH AND RENDER UPCOMING APPOINTMENTS --- */
        async function loadUpcomingAppointments() {
            try {
                const response = await fetch("/api/appointments", { headers: getHeaders() });
                const data = await response.json();
                const appointments = data.appointments || data;
                const tbody = document.getElementById('appBody');
                tbody.innerHTML = '';

                if (!Array.isArray(appointments) || appointments.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="5" style="text-align: center; color: #94a3b8; padding: 40px;"><i class="fa-regular fa-calendar-minus" style="font-size: 1.5rem; display:block; margin-bottom:10px;"></i> No upcoming medical clinic appointments scheduled.</td></tr>`;
                    return;
                }

                appointments.forEach(app => {
                    let petName = app.pet_name;
                    if (!petName && app.pet) {
                        petName = app.pet.pet_name;
                    }
                    if (!petName) petName = "Unknown Pet";

                    const timeFormatted = app.appointment_time ? app.appointment_time.substring(0, 5) : '';
                    const displayStatus = app.status || 'pending';
                    
                    let actionButton = '';
                    if (displayStatus.toLowerCase() !== 'cancelled') {
                        actionButton = `<button class="action-icon" onclick="handleCancelAppointment(${app.appointment_id || app.id})" style="background:none; border:none; cursor:pointer; color:#94a3b8; font-size:1rem;" title="Cancel Appointment"><i class="fa-solid fa-calendar-xmark"></i></button>`;
                    } else {
                        actionButton = `<span style="color:#cbd5e1; font-size:0.85rem; font-style:italic;">None</span>`;
                    }

                    tbody.innerHTML += `
                        <tr>
                            <td><strong>${escapeHtml(petName)}</strong></td>
                            <td><i class="fa-regular fa-calendar" style="color:#6366f1; margin-right:6px;"></i> ${escapeHtml(app.appointment_date)} at ${escapeHtml(timeFormatted)}</td>
                            <td>${escapeHtml(app.service_type)}</td>
                            <td><span class="status-badge status-${displayStatus.toLowerCase()}">${escapeHtml(displayStatus)}</span></td>
                            <td style="text-align: center;">${actionButton}</td>
                        </tr>
                    `;
                });
            } catch (err) {
                console.error("Critical appointment parsing context exception:", err);
            }
        }

        /* --- APPOINTMENT CANCELLATION --- */
        async function handleCancelAppointment(id) {
            if (!confirm("Are you sure you want to cancel this scheduled clinical appointment checkup?")) return;
            try {
                const response = await fetch(`/api/appointments/${id}/cancel`, {
                    method: 'POST',
                    headers: getHeaders()
                });
                const result = await response.json();
                if (result.success || result.status === 'success') {
                    alert(result.message || "Appointment cancelled successfully!");
                    loadUpcomingAppointments();
                    pushSystemNotification("Appointment status update: Cancelled successfully.");
                } else {
                    alert(result.message || "Failed to cancel appointment entry.");
                }
            } catch (err) {
                console.error("Cancellation interface exception request error:", err);
            }
        }

        /* --- DYNAMIC NOTIFICATION SYSTEM PUSH --- */
        function pushSystemNotification(msg) {
            const list = document.getElementById('notifList');
            const badge = document.getElementById('notifBadge');
            
            if (list.querySelector('.notif-empty')) {
                list.innerHTML = '';
            }

            const currentCount = parseInt(badge.innerText, 10) || 0;
            badge.innerText = currentCount + 1;
            badge.style.display = 'block';

            list.innerHTML = `
                <li class="notif-item">
                    <i class="fa-solid fa-circle-info" style="color:#6366f1; margin-top:3px;"></i>
                    <div>${escapeHtml(msg)}<br><small style="color:#94a3b8;">Just now</small></div>
                </li>
            ` + list.innerHTML;
        }

        /* --- MODAL DISPLAY HOOK MANAGEMENT CONTROLLERS --- */
        function setupGlobalClickHandlers() {
            const toggleModal = (modalId, isOpen) => {
                document.getElementById(modalId).classList.toggle('hidden', !isOpen);
            };

            document.getElementById('openAddPetModalBtn').onclick = () => toggleModal('addPetModal', true);
            document.getElementById('closePetXBtn').onclick = () => toggleModal('addPetModal', false);
            document.getElementById('cancelPetBtn').onclick = () => toggleModal('addPetModal', false);

            document.getElementById('closeManagePetXBtn').onclick = () => toggleModal('managePetModal', false);
            document.getElementById('cancelManagePetBtn').onclick = () => toggleModal('managePetModal', false);

            document.getElementById('openBookModalBtn').onclick = () => toggleModal('bookModal', true);
            document.getElementById('closeBookXBtn').onclick = () => toggleModal('bookModal', false);
            document.getElementById('cancelBookBtn').onclick = () => toggleModal('bookModal', false);
        }

        function openManagePetModal(id) {
            const pet = registeredPetsArray.find(p => (p.pet_id || p.id) == id);
            if (!pet) return;

            document.getElementById('edit_pet_id').value = id;
            document.getElementById('edit_pet_name').value = pet.pet_name;
            document.getElementById('edit_species').value = pet.type;
            document.getElementById('edit_breed').value = pet.breed;
            document.getElementById('edit_birthday').value = pet.birthdate;
            document.getElementById('edit_gender').value = pet.gender;

            document.getElementById('managePetModal').classList.remove('hidden');
        }

        /* --- FORM ASYNCHRONOUS ENGINE SUBMISSIONS --- */
        function setupFormSubmissionListeners() {
            // Add New Pet Profile Entry Data pipeline
            document.getElementById('addPetForm').onsubmit = async (e) => {
                e.preventDefault();
                const formData = {
                    pet_name: document.getElementById('pet_name').value,
                    type: document.getElementById('species').value,
                    breed: document.getElementById('breed').value,
                    birthdate: document.getElementById('birthday').value,
                    gender: document.getElementById('gender').value
                };

                try {
                    const response = await fetch('/api/pets', {
                        method: 'POST',
                        headers: getHeaders(),
                        body: JSON.stringify(formData)
                    });
                    const res = await response.json();
                    if (res.pet_id || res.id || res.status === 'success') {
                        document.getElementById('addPetModal').classList.add('hidden');
                        document.getElementById('addPetForm').reset();
                        loadPetProfiles();
                        pushSystemNotification(`Profile for ${formData.pet_name} saved successfully.`);
                    }
                } catch (err) {
                    console.error("Pet tracking entry synchronization runtime error:", err);
                }
            };

            // Update Changes on Existing Records Profile Pipeline
            document.getElementById('managePetForm').onsubmit = async (e) => {
                e.preventDefault();
                const id = document.getElementById('edit_pet_id').value;
                const formData = {
                    pet_name: document.getElementById('edit_pet_name').value,
                    type: document.getElementById('edit_species').value,
                    breed: document.getElementById('edit_breed').value,
                    birthdate: document.getElementById('edit_birthday').value,
                    gender: document.getElementById('edit_gender').value
                };

                try {
                    const response = await fetch(`/api/pets/${id}`, {
                        method: 'PUT',
                        headers: getHeaders(),
                        body: JSON.stringify(formData)
                    });
                    if (response.ok) {
                        document.getElementById('managePetModal').classList.add('hidden');
                        loadPetProfiles();
                        loadUpcomingAppointments();
                        pushSystemNotification(`Changes for ${formData.pet_name} updated locally.`);
                    }
                } catch (err) {
                    console.error("Pet patch payload pipeline modification error:", err);
                }
            };

            // Save and Log New Form Booking pipeline processing
            document.getElementById('bookAppointmentForm').onsubmit = async (e) => {
                e.preventDefault();
                const formData = {
                    pet_id: document.getElementById('book_pet_id').value,
                    service_type: document.getElementById('service_type').value,
                    appointment_date: document.getElementById('appointment_date').value,
                    appointment_time: document.getElementById('appointment_time').value
                };

                try {
                    const response = await fetch('/api/appointments', {
                        method: 'POST',
                        headers: getHeaders(),
                        body: JSON.stringify(formData)
                    });
                    const res = await response.json();
                    if (res.appointment_id || res.id || res.status === 'success') {
                        document.getElementById('bookModal').classList.add('hidden');
                        document.getElementById('bookAppointmentForm').reset();
                        loadUpcomingAppointments();
                        pushSystemNotification("New healthcare reservation successfully queued.");
                    } else {
                        alert(res.message || "Failed to book appointment.");
                    }
                } catch (err) {
                    console.error("Appointment persistence engine creation failure context error:", err);
                }
            };
        }

        /* --- DELETE PET RECORD HANDLER --- */
        async function handleDeletePet() {
            const id = document.getElementById('edit_pet_id').value;
            const petName = document.getElementById('edit_pet_name').value;
            if (!confirm(`Are you absolutely sure you want to permanently delete the profile data for ${petName}? This action cannot be undone.`)) return;

            try {
                const response = await fetch(`/api/pets/${id}`, {
                    method: 'DELETE',
                    headers: getHeaders()
                });
                if (response.ok) {
                    document.getElementById('managePetModal').classList.add('hidden');
                    loadPetProfiles();
                    loadUpcomingAppointments();
                    pushSystemNotification(`Profile data for ${petName} deleted cleanly.`);
                }
            } catch (err) {
                console.error("Profile termination parsing operation failure:", err);
            }
        }
    </script>
</body>
</html>