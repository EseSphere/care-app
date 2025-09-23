<?php include_once 'header.php'; ?>

<div class="main-wrapper container mt-3">

    <!-- User Profile Card with Upload -->
    <div class="col-md-12 mb-3">
        <div class="card p-3 d-flex flex-row align-items-center gap-3 profile-card">
            <input type="file" id="profilePicInput" accept="image/*" style="display:none;">
            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Profile Picture" id="profilePic">
            <div style="flex:1;">
                <h4 id="profileName">Samson Gift</h4>
                <p class="text-muted mb-1" id="profileEmail">samsonosaretin@yahoo.com</p>
                <p class="text-muted mb-1" id="profilePhone">07448222483</p>
                <button class="btn btn-primary btn-sm" id="changePicBtn">
                    <i class="bi bi-camera"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Account Settings Panel -->
    <div class="col-md-12 mb-3">
        <div class="card p-3">
            <h5 class="card-header-title mb-3">Account Settings</h5>
            <div id="alertContainer"></div>
            <form id="settingsForm">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="fullName" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="fullName" value="Samson Gift">
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" value="samsonosaretin@yahoo.com">
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="phone" value="07448222483">
                    </div>
                    <div class="col-md-6">
                        <label for="currentPassword" class="form-label">Current Password</label>
                        <input type="password" class="form-control" id="currentPassword" placeholder="••••••••">
                    </div>
                    <div class="col-md-6">
                        <label for="newPassword" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="newPassword" placeholder="••••••••">
                    </div>
                    <div class="col-md-6">
                        <label for="confirmPassword" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirmPassword" placeholder="••••••••">
                    </div>
                </div>
                <button type="submit" class="btn btn-success mt-3">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- Notification Settings Panel -->
    <div class="col-md-12 mb-3">
        <div class="card p-3">
            <h5 class="card-header-title mb-3">Notification Settings</h5>
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" id="emailNotify" checked>
                <label class="form-check-label" for="emailNotify">Email Notifications</label>
            </div>
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" id="smsNotify">
                <label class="form-check-label" for="smsNotify">SMS Notifications</label>
            </div>
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" id="pushNotify" checked>
                <label class="form-check-label" for="pushNotify">Push Notifications</label>
            </div>
        </div>
    </div>

    <!-- Recent Activity Panel -->
    <div class="col-md-12 mb-3">
        <div class="card p-3">
            <h5 class="card-header-title mb-3">Recent Activity</h5>
            <div id="activityContainer">
                <div class="mb-2 p-2" style="border-bottom:1px solid #eee;">
                    <div class="d-flex justify-content-between">
                        <strong>Login</strong>
                        <small class="text-muted">2025-09-17</small>
                    </div>
                    <div>Logged in from IP 192.168.1.10</div>
                </div>
                <div class="mb-2 p-2" style="border-bottom:1px solid #eee;">
                    <div class="d-flex justify-content-between">
                        <strong>Password Change</strong>
                        <small class="text-muted">2025-09-16</small>
                    </div>
                    <div>Password updated successfully</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="col-md-12 mb-3">
        <div class="card p-3 danger-zone">
            <h5 class="card-header-title mb-3 text-danger">Danger Zone</h5>
            <button class="btn btn-danger" id="deleteAccountBtn">Delete Account</button>
        </div>
    </div>

</div>

<script>
    // SideNav toggle
    const menuBtn = document.getElementById('menuBtn');
    const sideNav = document.getElementById('sideNav');
    const overlay = document.getElementById('overlay');
    menuBtn.addEventListener('click', () => {
        sideNav.classList.add('open');
        overlay.classList.add('show');
    });
    overlay.addEventListener('click', () => {
        sideNav.classList.remove('open');
        overlay.classList.remove('show');
    });

    // Clock
    function updateClock() {
        document.getElementById('topClock').textContent = new Date().toLocaleTimeString([], {
            hour: '2-digit',
            minute: '2-digit'
        });
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Dark Mode
    const darkBtn = document.getElementById('darkModeBtn');
    darkBtn.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
    });

    // Profile Picture Upload
    const profilePic = document.getElementById('profilePic');
    const profileInput = document.getElementById('profilePicInput');
    const changePicBtn = document.getElementById('changePicBtn');

    profilePic.addEventListener('click', () => profileInput.click());
    changePicBtn.addEventListener('click', () => profileInput.click());

    profileInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = () => profilePic.src = reader.result;
            reader.readAsDataURL(file);
        }
    });

    // Form submission (AJAX example)
    const settingsForm = document.getElementById('settingsForm');
    const alertContainer = document.getElementById('alertContainer');

    settingsForm.addEventListener('submit', (e) => {
        e.preventDefault();

        const data = {
            fullName: document.getElementById('fullName').value,
            email: document.getElementById('email').value,
            phone: document.getElementById('phone').value,
            currentPassword: document.getElementById('currentPassword').value,
            newPassword: document.getElementById('newPassword').value,
            confirmPassword: document.getElementById('confirmPassword').value
        };

        // Simple client-side validation
        if (data.newPassword && data.newPassword !== data.confirmPassword) {
            alertContainer.innerHTML = `<div class="alert alert-danger">New passwords do not match.</div>`;
            return;
        }

        alertContainer.innerHTML = `<div class="alert alert-success">Settings updated successfully!</div>`;

        // Here you would send `data` to your PHP backend via AJAX
        // $.post('update-settings.php', data, response => console.log(response));
    });

    // Delete Account Confirmation
    const deleteBtn = document.getElementById('deleteAccountBtn');
    deleteBtn.addEventListener('click', () => {
        if (confirm('Are you sure you want to delete your account? This action is irreversible.')) {
            alert('Account deleted (mock). Implement backend call here.');
        }
    });
</script>

<?php include_once 'footer.php'; ?>