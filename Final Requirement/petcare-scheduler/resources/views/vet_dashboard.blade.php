<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetCare | Clinical Workspace</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f1f5f9;
            color: #0f172a;
        }
        .app-container {
            display: grid;
            grid-template-columns: 260px 1fr;
            min-height: 100vh;
        }
        .sidebar {
            background-color: #0f172a;
            color: #f8fafc;
            display: flex;
            flex-direction: column;
            padding: 24px 0;
        }
        .sidebar-brand { padding: 0 24px; font-size: 1.5rem; font-weight: 700; color: #38bdf8; margin-bottom: 30px; }
        .nav-links { list-style: none; flex: 1; }
        .nav-links li { padding: 14px 24px; display: flex; align-items: center; gap: 12px; color: #94a3b8; cursor: pointer; transition: all 0.2s; }
        .nav-links li.active, .nav-links li:hover { background-color: #1e293b; color: #f8fafc; }
        
        .btn-logout {
            margin: auto 24px 0; padding: 12px; background: #3b4252; color: #f8fafc; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-logout:hover { background: #bf616a; color: white; }

        .main-content { padding: 40px; max-width: 1400px; width: 100%; margin: 0 auto; }
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; }
        .top-bar h1 { font-size: 1.75rem; color: #1e293b; }
        
        .workspace-card { background: #ffffff; border-radius: 16px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; }
        .workspace-card h3 { margin-bottom: 20px; font-size: 1.2rem; color: #334155; }
        
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th, td { padding: 14px 16px; border-bottom: 1px solid #e2e8f0; font-size: 0.95rem; }
        th { background-color: #f8fafc; color: #64748b; font-weight: 600; }
        
        .status-badge { padding: 4px 10px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; display: inline-block; }
        .status-pending { background-color: #fef3c7; color: #d97706; }
        .status-approved { background-color: #d1fae5; color: #059669; }
        .status-completed { background-color: #e0f2fe; color: #0369a1; }
        .status-rescheduled { background-color: #f3e8ff; color: #6b21a8; }
        .status-cancelled { background-color: #fee2e2; color: #dc2626; }

        .select-action { padding: 6px 10px; border-radius: 6px; border: 1px solid #cbd5e1; font-family: inherit; font-size: 0.9rem; outline: none; background: white; cursor: pointer; }
        .select-action:focus { border-color: #38bdf8; }
    </style>
</head>
<body>
    <div class="app-container">
        <nav class="sidebar">
            <div class="sidebar-brand">PetCare Vet</div>
            <ul class="nav-links">
                <li class="active"><i class="fa-solid fa-stethoscope"></i> Clinical Triage</li>
            </ul>
            <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <button class="btn-logout" onclick="handleLogout()">
                <i class="fa-solid fa-right-from-bracket"></i> Clinic Sign-Out
            </button>
        </nav>

        <main class="main-content">
            <header class="top-bar">
                <div>
                    <h1>Clinical Workspace Pipeline</h1>
                    <p style="color: #64748b; margin-top: 4px;">Logged in as Dr. {{ Auth::user()->full_name ?? Auth::user()->username ?? 'Veterinarian' }}</p>
                </div>
            </header>

            <section class="workspace-card">
                <h3>Active Consultation Schedules</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Patient Pet</th>
                            <th>Owner Profile</th>
                            <th>Date & Time Slot</th>
                            <th>Treatment Scope</th>
                            <th>Current Status</th>
                            <th>Pipeline Actions</th>
                        </tr>
                    </thead>
                    <tbody id="vetAppBody">
                        <tr><td colspan="6" style="text-align: center; color: #94a3b8; padding: 30px;">Extracting system records...</td></tr>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            loadClinicAppointments();
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

        function handleLogout() {
            if (confirm("Close secure clinic workstation session?")) {
                document.getElementById('logoutForm').submit();
            }
        }

        async function loadClinicAppointments() {
            try {
                // FIXED: Forward cookie parameters via credentials key to solve the 401 unauthenticated exception
                const response = await fetch("/api/vet/appointments", { 
                    method: 'GET',
                    credentials: 'same-origin',
                    headers: getHeaders() 
                });
                const data = await response.json();
                const tbody = document.getElementById('vetAppBody');
                tbody.innerHTML = '';

                const appointments = data.appointments || data;

                if (!Array.isArray(appointments) || appointments.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="6" style="text-align: center; color: #94a3b8; padding: 30px;">No patient records mapped to triage grids.</td></tr>`;
                    return;
                }

                appointments.forEach(app => {
                    const status = (app.status || 'Pending').toLowerCase();
                    const targetId = app.appointment_id || app.id;
                    
                    let badgeClass = 'status-pending';
                    if (status === 'approved') badgeClass = 'status-approved';
                    if (status === 'completed') badgeClass = 'status-completed';
                    if (status === 'rescheduled') badgeClass = 'status-rescheduled';
                    if (status === 'cancelled') badgeClass = 'status-cancelled';

                    let dateString = '';
                    if (app.appointment_date) {
                        dateString = typeof app.appointment_date === 'object' && app.appointment_date.date 
                            ? app.appointment_date.date.split(' ')[0] 
                            : app.appointment_date;
                    }

                    let statusLabel = app.status;
                    if (app.veterinarian) {
                        statusLabel += ` (Dr. ${app.veterinarian.full_name || app.veterinarian.username})`;
                    } else if (status === 'approved' && app.vet_id) {
                        statusLabel = "Approved (Claimed)";
                    }

                    tbody.innerHTML += `
                        <tr>
                            <td><strong>${escapeHtml(app.pet?.pet_name || app.pet_name || 'Patient')}</strong></td>
                            <td>${escapeHtml(app.pet?.user?.full_name || app.user?.full_name || app.owner_name || 'Client')}</td>
                            <td>${escapeHtml(dateString)} at ${escapeHtml(app.appointment_time)}</td>
                            <td><span style="color: #0284c7; font-weight: 500;">${escapeHtml(app.service_type)}</span></td>
                            <td><span class="status-badge ${badgeClass}">${escapeHtml(statusLabel)}</span></td>
                            <td>
                                <select class="select-action" onchange="handleActionRoute('${targetId}', this.value)">
                                    <option value="" disabled selected>Update flow...</option>
                                    <option value="Approved" ${status === 'approved' ? 'disabled' : ''}>
                                        ${status === 'approved' ? '✓ Claimed & Approved' : 'Approve & Claim Slot'}
                                    </option>
                                    <option value="Completed" ${status === 'completed' ? 'disabled' : ''}>Mark Completed</option>
                                    <option value="Rescheduled" ${status === 'rescheduled' ? 'disabled' : ''}>Reschedule</option>
                                    <option value="Cancelled">Approve Cancel & Delete</option>
                                </select>
                            </td>
                        </tr>
                    `;
                });
            } catch (err) {
                console.error("Clinical tracking collection error:", err);
                document.getElementById('vetAppBody').innerHTML = `<tr><td colspan="6" style="text-align: center; color: #ef4444; padding: 30px;">Error parsing records database infrastructure.</td></tr>`;
            }
        }

        function handleActionRoute(id, actionValue) {
            if (actionValue === 'Cancelled') {
                executePurgeFlow(id);
            } else {
                updateAppointmentStatus(id, actionValue);
            }
        }

        async function updateAppointmentStatus(id, newStatus) {
            try {
                // FIXED: Attached stateful credentials to the POST mutation request mapping
                const response = await fetch("/api/vet/update-status", {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: getHeaders(),
                    body: JSON.stringify({ appointment_id: id, status: newStatus })
                });

                if (response.ok) {
                    loadClinicAppointments();
                } else {
                    const errorDetails = await response.json();
                    alert(errorDetails.message || "Failed to commit status pipeline upgrade.");
                }
            } catch (err) {
                console.error("Pipeline progression failure:", err);
            }
        }

        async function executePurgeFlow(id) {
            const userConfirmed = confirm("Notice: Are you sure you want to approve this cancellation request? This action will permanently delete this appointment record from the database.");
            
            if (userConfirmed) {
                try {
                    // FIXED: Attached stateful credentials to the DELETE record mapping block
                    const response = await fetch(`/api/vet/appointments/${id}`, {
                        method: 'DELETE',
                        credentials: 'same-origin',
                        headers: getHeaders()
                    });

                    if (response.ok) {
                        alert("Appointment record has been successfully verified, cancelled, and deleted.");
                        loadClinicAppointments();
                    } else {
                        const errorDetails = await response.json();
                        alert(errorDetails.message || "Failed to execute schedule data purge.");
                    }
                } catch (err) {
                    console.error("Purge system connection failure:", err);
                }
            } else {
                loadClinicAppointments();
            }
        }
    </script>
</body>
</html>