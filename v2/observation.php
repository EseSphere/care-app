<?php include_once 'header.php'; ?>

<div class="main-wrapper container py-4">

    <!-- Client Profile Section -->
    <div class="col-md-12 mb-3">
        <div class="card p-2 d-flex flex-row align-items-center">
            <div style="flex:0 0 100px; text-align:center;">
                <div id="clientInitials" style="width:80px;height:80px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2rem;font-weight:bold;margin:auto;color:white;">
                    --
                </div>
            </div>
            <div style="flex:1; padding-left:15px;">
                <h4 id="clientName">Loading...</h4>
                <p id="clientAge" class="mb-1">Age: --</p>
                <div class="d-flex gap-2">
                    <a class="btn btn-sm btn-danger" id="dnacprBtn">Health</a>
                    <a class="btn btn-sm btn-info" id="allergiesBtn">Emergency</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Observation Form -->
    <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Daily Observation</h5>
            <p class="text-muted small mb-3">
                Please provide a brief observation about the client’s condition, mood, or any significant events during this care call.
            </p>
            <form id="observationForm" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="observationText" class="form-label fw-semibold">Observation Notes</label>
                    <textarea class="form-control" id="observationText" rows="5" placeholder="Write your observation here..." required></textarea>
                    <div class="invalid-feedback">Observation field cannot be empty.</div>
                </div>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-send"></i> Check Out
                </button>
            </form>
        </div>
    </div>

    <!-- Highlight Section -->
    <div class="card p-3">
        <div class="row">
            <div class="col-sm-4 fs-5 fw-bold">Highlight:</div>
            <hr>
            <div class="col-sm-8 fs-6" id="highlight">Loading...</div>
        </div>
    </div>

</div>

<script>
    const urlParams = new URLSearchParams(window.location.search);
    const clientId = urlParams.get('uryyToeSS4');
    const shiftDate = urlParams.get('Clientshift_Date');
    const careCall = urlParams.get('care_calls');
    const carerId = urlParams.get('carerId');

    // Open IndexedDB
    async function openDB() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open('care_app');
            request.onsuccess = e => resolve(e.target.result);
            request.onerror = e => reject(e.target.error);
        });
    }

    // Fetch client details
    async function getClientDetails(clientId) {
        if (!clientId) return null;
        const db = await openDB();
        if (!db.objectStoreNames.contains('tbl_general_client_form')) return null;
        return new Promise((resolve, reject) => {
            const tx = db.transaction('tbl_general_client_form', 'readonly');
            const store = tx.objectStore('tbl_general_client_form');
            const req = store.getAll();
            req.onsuccess = e => {
                const client = e.target.result.find(c => c.uryyToeSS4 === clientId);
                resolve(client || null);
            };
            req.onerror = e => reject(e.target.error);
        });
    }

    // Fetch daily shift record
    async function getDailyShiftRecord(clientId, shiftDate, careCall, carerId) {
        const db = await openDB();
        if (!db.objectStoreNames.contains('tbl_daily_shift_records')) return null;
        return new Promise((resolve, reject) => {
            const tx = db.transaction('tbl_daily_shift_records', 'readonly');
            const store = tx.objectStore('tbl_daily_shift_records');
            const req = store.getAll();
            req.onsuccess = e => {
                const record = e.target.result.find(r =>
                    r.uryyToeSS4 === clientId &&
                    r.shift_date === shiftDate &&
                    r.col_care_call === careCall &&
                    r.col_carer_Id === carerId
                );
                resolve(record || null);
            };
            req.onerror = e => reject(e.target.error);
        });
    }

    // Fetch care pay rate from tbl_schedule_calls
    async function getScheduleCallPayRate(clientId, shiftDate, careCall, carerId) {
        const db = await openDB();
        if (!db.objectStoreNames.contains('tbl_schedule_calls')) return 0;
        return new Promise((resolve, reject) => {
            const tx = db.transaction('tbl_schedule_calls', 'readonly');
            const store = tx.objectStore('tbl_schedule_calls');
            const req = store.getAll();
            req.onsuccess = e => {
                const record = e.target.result.find(r =>
                    r.uryyToeSS4 === clientId &&
                    r.Clientshift_Date === shiftDate &&
                    r.care_calls === careCall &&
                    r.first_carer_Id === carerId
                );
                resolve(record ? parseFloat(record.pay_rate) || 0 : 0);
            };
            req.onerror = e => reject(e.target.error);
        });
    }

    // Fetch client rate from tbl_schedule_calls
    async function getScheduleCallClientRate(clientId, shiftDate, careCall, carerId) {
        const db = await openDB();
        if (!db.objectStoreNames.contains('tbl_schedule_calls')) return 0;
        return new Promise((resolve, reject) => {
            const tx = db.transaction('tbl_schedule_calls', 'readonly');
            const store = tx.objectStore('tbl_schedule_calls');
            const req = store.getAll();
            req.onsuccess = e => {
                const record = e.target.result.find(r =>
                    r.uryyToeSS4 === clientId &&
                    r.Clientshift_Date === shiftDate &&
                    r.care_calls === careCall &&
                    r.first_carer_Id === carerId
                );
                resolve(record ? parseFloat(record.client_rate) || 0 : 0);
            };
            req.onerror = e => reject(e.target.error);
        });
    }

    // Calculate hours difference hh:mm
    function calculateHoursDifference(start, end) {
        const [startH, startM] = start.split(':').map(Number);
        const [endH, endM] = end.split(':').map(Number);
        const startTotal = startH + startM / 60;
        const endTotal = endH + endM / 60;
        return Math.max(endTotal - startTotal, 0);
    }

    // Update daily shift record including rates and call status
    async function updateDailyShiftRecord(record, note) {
        const db = await openDB();
        if (!db.objectStoreNames.contains('tbl_daily_shift_records')) return;

        record.task_note = note;
        const now = new Date();
        record.shift_end_time = `${String(now.getHours()).padStart(2,'0')}:${String(now.getMinutes()).padStart(2,'0')}`;

        // Update daily shift call status
        record.col_call_status = 'completed';

        // Get rates
        const carePayRate = await getScheduleCallPayRate(record.uryyToeSS4, record.shift_date, record.col_care_call, record.col_carer_Id);
        const clientRate = await getScheduleCallClientRate(record.uryyToeSS4, record.shift_date, record.col_care_call, record.col_carer_Id);

        if (record.planned_timeIn && record.planned_timeOut) {
            const hoursWorked = calculateHoursDifference(record.planned_timeIn, record.planned_timeOut);
            if (carePayRate) record.col_carecall_rate = parseFloat((hoursWorked * carePayRate).toFixed(2));
            if (clientRate) record.col_client_rate = parseFloat((hoursWorked * clientRate).toFixed(2));
        }

        // Save daily shift record
        await new Promise((resolve, reject) => {
            const tx = db.transaction('tbl_daily_shift_records', 'readwrite');
            const store = tx.objectStore('tbl_daily_shift_records');
            const req = store.put(record);
            req.onsuccess = () => resolve();
            req.onerror = e => reject(e.target.error);
        });

        // Update schedule call status
        if (db.objectStoreNames.contains('tbl_schedule_calls')) {
            const tx2 = db.transaction('tbl_schedule_calls', 'readwrite');
            const store2 = tx2.objectStore('tbl_schedule_calls');
            const req2 = store2.getAll();
            req2.onsuccess = e => {
                const scheduleRecord = e.target.result.find(r =>
                    r.uryyToeSS4 === record.uryyToeSS4 &&
                    r.Clientshift_Date === record.shift_date &&
                    r.care_calls === record.col_care_call &&
                    r.first_carer_Id === record.col_carer_Id
                );
                if (scheduleRecord) {
                    scheduleRecord.call_status = 'completed';
                    store2.put(scheduleRecord);
                }
            };
        }
    }

    // Age and initials helpers
    function calculateAge(dob) {
        if (!dob) return '--';
        const birthDate = new Date(dob),
            today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        const dayDiff = today.getDate() - birthDate.getDate();
        if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) age--;
        return age;
    }

    function createInitialsCircle(fullName, fontSize = 2, diameter = 100) {
        if (!fullName) fullName = '--';
        const names = fullName.split(' ');
        const initials = ((names[0]?.charAt(0) || '') + (names[1]?.charAt(0) || '')).toUpperCase();
        const colors = ["#6c757d", "#0d6efd", "#198754", "#dc3545", "#ffc107", "#6f42c1", "#fd7e14"];
        const charCodeSum = (initials.charCodeAt(0) || 0) + (initials.charCodeAt(1) || 0);
        const bgColor = colors[charCodeSum % colors.length];
        const div = document.createElement('div');
        div.textContent = initials;
        div.style.width = `${diameter}px`;
        div.style.height = `${diameter}px`;
        div.style.borderRadius = '50%';
        div.style.display = 'flex';
        div.style.alignItems = 'center';
        div.style.justifyContent = 'center';
        div.style.fontSize = `${fontSize}rem`;
        div.style.fontWeight = 'bold';
        div.style.color = 'white';
        div.style.backgroundColor = bgColor;
        div.style.marginBottom = '5px';
        return div;
    }

    // Render client profile & observation
    async function renderClientProfileAndObservation() {
        if (!clientId) return;
        const client = await getClientDetails(clientId);
        if (client) {
            const firstName = client.client_first_name || '';
            const lastName = client.client_last_name || '';
            const initialsDiv = document.getElementById('clientInitials');
            const clientInitialsCircle = createInitialsCircle(`${firstName} ${lastName}`, 2, 100);
            initialsDiv.replaceWith(clientInitialsCircle);
            clientInitialsCircle.id = 'clientInitials';

            document.getElementById('clientName').textContent = `${firstName} ${lastName}`;
            document.getElementById('clientAge').textContent = `Age: ${calculateAge(client.client_date_of_birth)}`;
            document.getElementById('dnacprBtn').href = `health.php?uryyToeSS4=${client.uryyToeSS4}`;
            document.getElementById('allergiesBtn').href = `emergency.php?uryyToeSS4=${client.uryyToeSS4}`;

            const highlightDiv = document.getElementById('highlight');
            if (client.client_highlights) {
                const paragraphs = client.client_highlights.split(/\n\s*\n/);
                highlightDiv.innerHTML = paragraphs.map(p => `<p>${p.trim().replace(/\n/g,'<br>')}</p>`).join('');
            } else highlightDiv.innerHTML = '<p>No highlights available.</p>';
        }

        const record = await getDailyShiftRecord(clientId, shiftDate, careCall, carerId);
        if (record && record.task_note) {
            document.getElementById('observationText').value = record.task_note;
        }
    }

    // Handle form submission
    document.getElementById('observationForm').addEventListener('submit', async e => {
        e.preventDefault();
        const note = document.getElementById('observationText').value.trim();
        if (!note) return;

        const record = await getDailyShiftRecord(clientId, shiftDate, careCall, carerId);
        if (record) {
            await updateDailyShiftRecord(record, note);

            // Redirect to home.php after update
            window.location.href = 'home.php';
        } else {
            alert('Daily shift record not found.');
        }
    });

    renderClientProfileAndObservation();
</script>

<?php include_once 'footer.php'; ?>