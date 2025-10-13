<?php include_once 'header.php'; ?>

<div class="main-wrapper container">
    <div class="row gutters-sm">
        <!-- Client Profile Horizontal Layout -->
        <?php require_once 'client-profile-extension.php'; ?>

        <!-- Client Info & Stats -->
        <div class="col-md-12">
            <div class="card p-3">
                <h5>Health</h5>
                <hr>
                <div class="row">
                    <div class="col-sm-4 fw-bold">NHS Number</div>
                    <div style="color: red; font-weight:800;" class="col-sm-8" id="nhsNumber">No</div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-4 fw-bold">Allergies</div>
                    <div class="col-sm-8" id="allergies">Yes</div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-4 fw-bold">GP Phone</div>
                    <div class="col-sm-8" id="gpPhone">No</div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-4 fw-bold">GP Address</div>
                    <div class="col-sm-8" id="gpAddress">No</div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-4 fw-bold">Pharmancy Phone</div>
                    <div class="col-sm-8" id="locationSupport">No</div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-4 fw-bold">Medicine support</div>
                    <div class="col-sm-8" id="medicine">No</div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-4 fw-bold">GP Name</div>
                    <div class="col-sm-8" id="gpName">No</div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-4 fw-bold">GP Email</div>
                    <div class="col-sm-8" id="gpEmail">No</div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-4 fw-bold">Pharmancy</div>
                    <div class="col-sm-8" id="pharmancy">No</div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-4 fw-bold">Pharmancy Address</div>
                    <div class="col-sm-8" id="pharmancyAdd">No</div>
                </div>
            </div>

            <hr>
            <?php require_once 'highlight-extention.php'; ?>
        </div>
    </div>
</div>

<script>
    // ✅ Utility: calculate age
    function calculateAge(dob) {
        if (!dob) return '--';
        const birthDate = new Date(dob);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        const dayDiff = today.getDate() - birthDate.getDate();
        if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) age--;
        return age;
    }

    // ✅ Utility: open IndexedDB safely
    async function openDB() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open('care_app');
            request.onsuccess = e => resolve(e.target.result);
            request.onerror = e => reject(e.target.error);
        });
    }

    // ✅ Utility: read query parameter
    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    // ✅ Fetch client details (tbl_general_client_form)
    async function getClientDetails(uryyToeSS4) {
        const db = await openDB();
        if (!db.objectStoreNames.contains('tbl_general_client_form')) return null;
        return new Promise((resolve, reject) => {
            const tx = db.transaction('tbl_general_client_form', 'readonly');
            const store = tx.objectStore('tbl_general_client_form');
            const req = store.getAll();
            req.onsuccess = e => resolve(e.target.result.find(c => c.uryyToeSS4 === uryyToeSS4) || null);
            req.onerror = e => reject(e.target.error);
        });
    }

    // ✅ Fetch health data (tbl_client_medical)
    async function getHealthData(uryyToeSS4) {
        const db = await openDB();
        if (!db.objectStoreNames.contains('tbl_client_medical')) return null;
        return new Promise((resolve, reject) => {
            const tx = db.transaction('tbl_client_medical', 'readonly');
            const store = tx.objectStore('tbl_client_medical');
            const req = store.getAll();
            req.onsuccess = e => resolve(e.target.result.find(r => r.uryyToeSS4 === uryyToeSS4) || null);
            req.onerror = e => reject(e.target.error);
        });
    }

    // ✅ Helper: create initials circle
    function createInitials(fullName) {
        const names = fullName.split(' ');
        const initials = ((names[0]?.charAt(0) || '') + (names[1]?.charAt(0) || '')).toUpperCase();
        const colors = ["#6c757d", "#0d6efd", "#198754", "#dc3545", "#ffc107", "#6f42c1", "#fd7e14"];
        const charCodeSum = (initials.charCodeAt(0) || 0) + (initials.charCodeAt(1) || 0);
        return colors[charCodeSum % colors.length];
    }

    // ✅ Render health data
    async function renderHealthData() {
        const uryyToeSS4 = getQueryParam('uryyToeSS4');
        if (!uryyToeSS4) return;

        const client = await getClientDetails(uryyToeSS4);
        if (client) {
            const fullName = `${client.client_first_name || ''} ${client.client_last_name || ''}`.trim();
            const initials = (fullName.split(' ')[0]?.charAt(0) || '') + (fullName.split(' ')[1]?.charAt(0) || '');
            const initialsDiv = document.getElementById('clientInitials');
            initialsDiv.textContent = initials.toUpperCase();
            initialsDiv.style.backgroundColor = createInitials(fullName);

            document.getElementById('clientName').textContent = fullName;
            document.getElementById('clientAge').textContent = `Age: ${calculateAge(client.client_date_of_birth)}`;
            document.getElementById('dnacprBtn').href = `health.php?uryyToeSS4=${client.uryyToeSS4}`;
            document.getElementById('allergiesBtn').href = `emergency.php?uryyToeSS4=${client.uryyToeSS4}`;

            const highlightDiv = document.getElementById('highlight');
            if (client.client_highlights)
                highlightDiv.innerHTML = client.client_highlights.replace(/\n/g, '<br>');
            else highlightDiv.innerHTML = '<p>No highlights available.</p>';
        }

        const health = await getHealthData(uryyToeSS4);
        if (health) {
            document.getElementById('nhsNumber').textContent = health.col_nhs_number || 'No';
            document.getElementById('allergies').textContent = health.col_allergies || 'None';
            document.getElementById('gpPhone').textContent = health.col_phone_number || 'No';
            document.getElementById('gpAddress').textContent = health.gp_address || 'No';
            document.getElementById('locationSupport').textContent = health.pharmacy_phone || 'No';
            document.getElementById('medicine').textContent = health.col_medical_support || 'No';
            document.getElementById('gpName').textContent = health.col_gp_name || 'No';
            document.getElementById('gpEmail').textContent = health.gp_email_address || 'No';
            document.getElementById('pharmancy').textContent = health.col_pharmancy_name || 'No';
            document.getElementById('pharmancyAdd').textContent = health.col_pharmancy_address || 'No';
        }
    }

    renderHealthData();
</script>

<?php include_once 'footer.php'; ?>