<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetCare | Access Portal</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --bg-gradient: linear-gradient(135deg, #4f46e5 0%, #1e1b4b 100%);
            --border-color: #cbd5e1;
            --danger: #ef4444;
            --danger-bg: #fef2f2;
            --danger-border: #fee2e2;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; min-height: 100vh; display: flex; background-color: #f8fafc; }
        .split-container { display: flex; width: 100%; }
        .branding-side { flex: 1; background: var(--bg-gradient); display: flex; flex-direction: column; justify-content: center; align-items: center; color: white; padding: 40px; text-align: center; }
        .branding-side i.main-logo { font-size: 5rem; margin-bottom: 20px; }
        .branding-side h1 { font-size: 3rem; margin: 0 0 20px 0; font-weight: 800; }
        .branding-side p { font-size: 1.2rem; max-width: 450px; line-height: 1.6; opacity: 0.9; }
        .form-side { flex: 1; display: flex; justify-content: center; align-items: center; background: #f1f5f9; padding: 40px; }
        .login-card { width: 100%; max-width: 460px; background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05); }
        .login-card h2 { font-size: 2.2rem; color: #1e293b; margin: 0 0 5px 0; font-weight: 700; }
        .login-card p.subtitle { color: #64748b; margin: 0 0 25px 0; font-size: 1.05rem; }
        .error-alert { display: flex; align-items: center; gap: 12px; background-color: var(--danger-bg); border: 1px solid var(--danger-border); color: var(--danger); padding: 12px 16px; border-radius: 10px; margin-bottom: 24px; font-size: 0.95rem; font-weight: 500; }
        .error-alert ul { margin: 6px 0 0 0; padding-left: 20px; font-size: 0.85rem; }
        .form-group { margin-bottom: 24px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #475569; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; }
        .password-label-container { display: flex; justify-content: space-between; align-items: center; }
        .suggest-btn { background: none; border: none; color: var(--primary); font-size: 0.75rem; cursor: pointer; font-weight: 700; text-transform: uppercase; padding: 0; margin-bottom: 8px; }
        .suggest-btn:hover { text-decoration: underline; color: var(--primary-dark); }
        .strength-indicator { font-size: 0.8rem; margin-top: 6px; font-weight: 600; }
        .input-wrapper { position: relative; display: flex; align-items: center; }
        .input-wrapper i { position: absolute; left: 14px; color: #94a3b8; font-size: 1.1rem; }
        .form-control { width: 100%; padding: 14px 14px 14px 45px; border: 1px solid var(--border-color); border-radius: 10px; font-size: 1rem; outline: none; background-color: #eff6ff; transition: all 0.2s; }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); background-color: white; }
        select.form-control { cursor: pointer; appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: calc(100% - 14px) center; background-size: 16px; }
        .btn-submit { width: 100%; background: var(--primary); color: white; border: none; padding: 16px; border-radius: 12px; font-size: 1.05rem; font-weight: 700; cursor: pointer; margin-top: 10px; transition: background 0.2s; }
        .btn-submit:hover { background: var(--primary-dark); }
        .form-footer { text-align: center; margin-top: 25px; font-size: 0.95rem; color: #64748b; }
        .form-footer a { color: var(--primary); text-decoration: none; font-weight: 600; margin-left: 4px; }
        .form-footer a:hover { text-decoration: underline; }
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(8px); display: flex; justify-content: center; align-items: center; z-index: 1000; opacity: 0; pointer-events: none; transition: opacity 0.3s ease; padding: 20px; overflow-y: auto; }
        .modal-overlay.active { opacity: 1; pointer-events: auto; }
        .modal-overlay .login-card { transform: scale(0.9); transition: transform 0.3s ease; max-height: 95vh; overflow-y: auto; }
        .modal-overlay.active .login-card { transform: scale(1); }
        @media (max-width: 768px) { .split-container { flex-direction: column; } .branding-side, .form-side { padding: 60px 20px; } }
    </style>
</head>
<body>

    <div class="split-container">
        <div class="branding-side">
            <i class="fa-solid fa-paw main-logo"></i>
            <h1>PetCare</h1>
            <p>Your all-in-one clinical space for appointment booking, real-time tracking, and veterinary care.</p>
        </div>

        <div class="form-side">
            <div class="login-card">
                <h2>Welcome Back</h2>
                <p class="subtitle">Please log in to your account</p>

                <div id="errorAlert" class="error-alert" style="{{ $errors->any() ? 'display: flex;' : 'display: none;' }}">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span id="errorAlertText">@if($errors->any()) {{ $errors->first() }} @endif</span>
                </div>

                <form id="loginForm" method="POST" action="{{ route('auth.login') }}">
                    @csrf
                    <div class="form-group">
                        <label>System Workspace</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-user-shield"></i>
                            <select class="form-control" name="workspace" id="workspace" required>
                                <option value="Regular Pet Owner" {{ old('workspace') == 'Regular Pet Owner' ? 'selected' : '' }}>Client / Pet Owner</option>
                                <option value="Veterinarian" {{ old('workspace') == 'Veterinarian' ? 'selected' : '' }}>Veterinarian</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Email Address</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-envelope"></i>
                            <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}" placeholder="user@gmail.com" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Security Password</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" class="form-control" name="password" id="password" placeholder="••••••••" minlength="8" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">Secure Login</button>
                </form>

                <div class="form-footer">Don't have an account? <a href="#" id="openRegister">Sign Up Here</a></div>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="registerModal">
        <div class="login-card">
            <h2>Create Account</h2>
            <p class="subtitle">Join the PetCare platform today</p>
            
            <div id="registerErrorAlert" class="error-alert" style="display: none; flex-direction: column; align-items: flex-start; gap: 4px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span id="registerErrorAlertText">Registration failed.</span>
                </div>
                <ul id="registerErrorList"></ul>
            </div>

            <form id="registerForm">
                <div class="form-group">
                    <label>Account Assignment Type</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-user-gear"></i>
                        <select class="form-control" id="regRole" required>
                            <option value="Regular Pet Owner">Regular Pet Owner / User</option>
                            <option value="Veterinarian">Professional Veterinarian</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Full Name</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" class="form-control" id="fullName" placeholder="John Doe" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" class="form-control" id="regEmail" placeholder="user@gmail.com" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="password-label-container">
                        <label>Password</label>
                        <button type="button" class="suggest-btn" id="suggestBtn">Suggest Strong Password</button>
                    </div>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" class="form-control" id="regPassword" placeholder="••••••••" minlength="8" required>
                    </div>
                    <div id="strengthText" class="strength-indicator" style="color: #64748b;">Must be at least 8 characters long.</div>
                </div>
                <button type="submit" class="btn-submit">Register</button>
            </form>
            <div class="form-footer">Already have an account? <a href="#" id="closeRegister">Back to Login</a></div>
        </div>
    </div>

    <script>
        const errorAlert = document.getElementById('errorAlert');
        const registerModal = document.getElementById('registerModal');
        const openRegister = document.getElementById('openRegister');
        const closeRegister = document.getElementById('closeRegister');
        const registerForm = document.getElementById('registerForm');
        const regPassword = document.getElementById('regPassword');
        const strengthText = document.getElementById('strengthText');
        const suggestBtn = document.getElementById('suggestBtn');
        
        const registerErrorAlert = document.getElementById('registerErrorAlert');
        const registerErrorAlertText = document.getElementById('registerErrorAlertText');
        const registerErrorList = document.getElementById('registerErrorList');

        // Modal State Triggers
        openRegister.onclick = (e) => { 
            e.preventDefault(); 
            errorAlert.style.display = 'none'; 
            registerModal.classList.add('active'); 
        };
        
        const dismissModal = () => { 
            registerModal.classList.remove('active'); 
            registerForm.reset(); 
            registerErrorAlert.style.display = 'none'; 
            registerErrorList.innerHTML = '';
            validatePassword(''); 
        };
        
        closeRegister.onclick = (e) => { e.preventDefault(); dismissModal(); };
        registerModal.onclick = (e) => { if (e.target === registerModal) dismissModal(); };

        // Password Generator Engine
        suggestBtn.onclick = () => {
            const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()";
            let pass = "";
            for (let i = 0; i < 14; i++) pass += chars.charAt(Math.floor(Math.random() * chars.length));
            regPassword.type = "text"; 
            regPassword.value = pass; 
            validatePassword(pass);
            alert(`Your suggested strong password is:\n\n${pass}\n\nSave it safely!`);
            regPassword.type = "password";
        };

        // Live Security Metric Estimator
        const validatePassword = (val) => {
            if (!val) { strengthText.textContent = "Must be at least 8 characters long."; strengthText.style.color = "#64748b"; return; }
            if (val.length < 8) { strengthText.textContent = "❌ Too short! Minimum 8 characters required."; strengthText.style.color = "var(--danger)"; return; }
            if (/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()]).+$/.test(val)) { strengthText.textContent = "✅ Strong secure password!"; strengthText.style.color = "#16a34a"; }
            else { strengthText.textContent = "⚠️ Weak. Mix uppercase, lowercase, numbers, and symbols."; strengthText.style.color = "#ca8a04"; }
        };
        regPassword.addEventListener('input', (e) => validatePassword(e.target.value));

        // Form Submission Request Pipeline
        registerForm.onsubmit = async (e) => {
            e.preventDefault();
            registerErrorAlert.style.display = 'none';
            registerErrorList.innerHTML = '';

            try {
                const res = await fetch('/api/auth/register', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'Accept': 'application/json', 
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') 
                    },
                    body: JSON.stringify({ 
                        full_name: document.getElementById('fullName').value,
                        email: document.getElementById('regEmail').value, 
                        password: regPassword.value,
                        role: document.getElementById('regRole').value
                    })
                });
                
                const out = await res.json();
                
                if (res.ok && out.status === 'success') { 
                    alert("Account created successfully!"); 
                    dismissModal(); 
                    if (out.redirect) {
                        window.location.href = out.redirect;
                    }
                } else { 
                    registerErrorAlertText.innerText = out.message || "Registration validation error.";
                    
                    if (out.errors) {
                        Object.keys(out.errors).forEach(key => {
                            out.errors[key].forEach(errText => {
                                const li = document.createElement('li');
                                li.innerText = errText;
                                registerErrorList.appendChild(li);
                            });
                        });
                    }
                    registerErrorAlert.style.display = 'flex'; 
                }
            } catch (err) { 
                console.error(err);
                registerErrorAlertText.innerText = "Network transmission fault: connectivity down."; 
                registerErrorAlert.style.display = 'flex'; 
            }
        };
    </script>
</body>
</html>