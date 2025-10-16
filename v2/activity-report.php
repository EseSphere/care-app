<?php include_once 'header.php'; ?>

<div class="main-wrapper container">

    <!-- Client Profile Horizontal Layout -->
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

    <!-- Submit Activity Report -->
    <div class="card p-3 mb-3">
        <h5>Submit Activity Report</h5>
        <hr>
        <form method="post" action="./activities" id="activityReportForm">

            <!-- Task / Medication -->
            <div class="mb-3">
                <label class="form-label">Task / Medication</label>
                <p class="fw-bold fs-5" id="selectedActivity">
                    Loading...
                </p>
                <p style="margin-top: -15px;" class="fw-semibold fs-6" id="activityDescription">
                    Loading...
                </p>
            </div>

            <!-- Status Options -->
            <div class="mb-3">
                <label class="form-label">Status</label>
                <div class="d-flex flex-wrap gap-2" id="statusContainer">
                    <?php
                    $statusOptions = [
                        'Completed' => 'success',
                        'Not Completed' => 'danger',
                        'Refused' => 'warning',
                        'Not Available' => 'secondary',
                        'Not Necessary' => 'info',
                        'Given' => 'primary',      // New status
                        'Not Given' => 'dark'      // New status
                    ];
                    foreach ($statusOptions as $status => $color) {
                        echo "<button type='button' class='btn btn-outline-$color flex-fill status-btn'>$status</button>";
                    }
                    ?>
                </div>
            </div>

            <!-- Report / Notes -->
            <div class="mb-3">
                <label for="reportText" class="form-label">Report / Notes</label>
                <textarea class="form-control" id="reportText" rows="5" placeholder="Enter details here"></textarea>
            </div>

            <a style="width: 100px; border-radius:3px;" href="./activities" id="continueBtn" class="btn btn-info text-decoration-none">Copy</a>
            <button style="width: 100px; border-radius:3px;" type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <!-- Previous Reports -->
    <div id="previousReportsContainer" class="mb-3"></div>

    <!-- Highlight -->
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
    const clientId = urlParams.get('clientId'); // client ID from URL
    const taskId = urlParams.get('col_taskId'); // task/med unique ID from URL
    const userId = urlParams.get('userId');
    const carerId = urlParams.get('carerId');
    const careCallFromURL = urlParams.get('care_calls') || 'Morning';
    const urlDate = urlParams.get('task_date') || urlParams.get('med_date') || new Date().toISOString().split('T')[0];

    let statusSelected = '';

    // Open IndexedDB
    async function openDB() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open('care_app');
            request.onsuccess = e => resolve(e.target.result);
            request.onerror = e => reject(e.target.error);
            request.onupgradeneeded = e => {
                const db = e.target.result;
                if (!db.objectStoreNames.contains('tbl_finished_tasks')) db.createObjectStore('tbl_finished_tasks', {
                    keyPath: 'userId'
                });
                if (!db.objectStoreNames.contains('tbl_finished_meds')) db.createObjectStore('tbl_finished_meds', {
                    keyPath: 'userId'
                });
            };
        });
    }

    // Get client details
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

    // Render client profile and highlights
    async function renderClientProfileAndHighlight() {
        if (!clientId) return;
        const client = await getClientDetails(clientId);
        if (!client) return;

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
        } else {
            highlightDiv.innerHTML = '<p>No highlights available.</p>';
        }
    }

    // Get selected activity
    async function getSelectedActivity(taskId, clientId) {
        if (!taskId || !clientId) return null;
        const db = await openDB();
        const stores = ['tbl_clients_task_records', 'tbl_clients_medication_records'];
        for (let storeName of stores) {
            if (!db.objectStoreNames.contains(storeName)) continue;
            const tx = db.transaction(storeName, 'readonly');
            const store = tx.objectStore(storeName);
            const req = store.getAll();
            const result = await new Promise((resolve, reject) => {
                req.onsuccess = e => resolve(e.target.result);
                req.onerror = e => reject(e.target.error);
            });
            const record = result.find(r => (r.col_taskId === taskId || r.uniqueId === taskId) && r.uryyToeSS4 === clientId);
            if (record) return {
                ...record,
                type: storeName.includes('task') ? 'task' : 'medication'
            };
        }
        return null;
    }

    // Status toggle
    const statusButtons = document.querySelectorAll('.status-btn');
    statusButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            statusButtons.forEach(b => {
                b.classList.remove('active', 'btn-success', 'btn-danger', 'btn-warning', 'btn-secondary', 'btn-info', 'btn-primary', 'btn-dark');
                const color = b.className.match(/btn-outline-(\w+)/)[1];
                b.classList.add('btn-outline-' + color);
            });
            btn.classList.add('active');
            const text = btn.textContent.trim();
            switch (text) {
                case 'Completed':
                    btn.classList.add('btn-success');
                    break;
                case 'Not Completed':
                    btn.classList.add('btn-danger');
                    break;
                case 'Refused':
                    btn.classList.add('btn-warning');
                    break;
                case 'Not Available':
                    btn.classList.add('btn-secondary');
                    break;
                case 'Not Necessary':
                    btn.classList.add('btn-info');
                    break;
                case 'Given':
                    btn.classList.add('btn-primary');
                    break;
                case 'Not Given':
                    btn.classList.add('btn-dark');
                    break;
            }
            statusSelected = text;
        });
    });

    // Render activity and pre-fill by date
    async function renderSelectedActivity() {
        const activityEl = document.getElementById('selectedActivity');
        const descriptionEl = document.getElementById('activityDescription');
        const activity = await getSelectedActivity(taskId, clientId);
        if (!activity) {
            activityEl.textContent = '--';
            descriptionEl.textContent = '--';
            document.getElementById('reportText').value = '';
            statusSelected = '';
            statusButtons.forEach(btn => btn.classList.remove('active', 'btn-success', 'btn-danger', 'btn-warning', 'btn-secondary', 'btn-info', 'btn-primary', 'btn-dark'));
            return;
        }

        if (activity.type === 'task') {
            activityEl.innerHTML = `${activity.client_taskName || '--'} <span class="badge bg-info ms-2">Task</span>`;
            descriptionEl.textContent = activity.client_task_details || 'No details available.';
        } else {
            activityEl.innerHTML = `${activity.med_name || '--'} (${activity.med_dosage || '--'}) <span class="badge bg-warning ms-2">Medication</span>`;
            descriptionEl.textContent = activity.med_details || 'No details available.';
        }

        const db = await openDB();
        const storeName = activity.type === 'task' ? 'tbl_finished_tasks' : 'tbl_finished_meds';
        const tx = db.transaction(storeName, 'readonly');
        const store = tx.objectStore(storeName);
        const allRecords = await new Promise((resolve, reject) => {
            const req = store.getAll();
            req.onsuccess = e => resolve(e.target.result);
            req.onerror = e => reject(e.target.error);
        });

        const prevSubmission = allRecords.find(r =>
            r.uniqueId === taskId &&
            r.uryyToeSS4 === clientId &&
            ((activity.type === 'task' && r.task_date === urlDate) ||
                (activity.type === 'medication' && r.med_date === urlDate))
        );

        if (prevSubmission) {
            document.getElementById('reportText').value = prevSubmission.note || '';
            statusSelected = prevSubmission.col_status || '';
            statusButtons.forEach(btn => {
                btn.classList.remove('active', 'btn-success', 'btn-danger', 'btn-warning', 'btn-secondary', 'btn-info', 'btn-primary', 'btn-dark');
                const color = btn.className.match(/btn-outline-(\w+)/)[1];
                btn.classList.add('btn-outline-' + color);
                if (btn.textContent.trim() === statusSelected) {
                    btn.classList.add('active');
                    switch (statusSelected) {
                        case 'Completed':
                            btn.classList.add('btn-success');
                            break;
                        case 'Not Completed':
                            btn.classList.add('btn-danger');
                            break;
                        case 'Refused':
                            btn.classList.add('btn-warning');
                            break;

                        case 'Not Available':
                            btn.classList.add('btn-secondary');
                            break;
                        case 'Not Necessary':
                            btn.classList.add('btn-info');
                            break;
                        case 'Given':
                            btn.classList.add('btn-primary');
                            break;
                        case 'Not Given':
                            btn.classList.add('btn-dark');
                            break;
                    }
                }
            });
        } else {
            document.getElementById('reportText').value = '';
            statusSelected = '';
            statusButtons.forEach(btn => btn.classList.remove('active', 'btn-success', 'btn-danger', 'btn-warning', 'btn-secondary', 'btn-info', 'btn-primary', 'btn-dark'));
        }
    }

    // Handle form submission
    document.getElementById('activityReportForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const notes = document.getElementById('reportText').value;
        const activity = await getSelectedActivity(taskId, clientId);
        if (!activity) return;

        const db = await openDB();
        const now = new Date();
        const storeName = activity.type === 'task' ? 'tbl_finished_tasks' : 'tbl_finished_meds';
        const tx = db.transaction(storeName, 'readwrite');
        const store = tx.objectStore(storeName);
        const allRecords = await new Promise((resolve, reject) => {
            const req = store.getAll();
            req.onsuccess = e => resolve(e.target.result);
            req.onerror = e => reject(e.target.error);
        });

        // Check existing record by uniqueId + clientId + date
        let record = allRecords.find(r =>
            r.uniqueId === taskId &&
            r.uryyToeSS4 === clientId &&
            ((activity.type === 'task' && r.task_date === urlDate) ||
                (activity.type === 'medication' && r.med_date === urlDate))
        );

        if (record) {
            // Update existing record
            record.note = notes;
            record.col_status = statusSelected || 'Not selected';
            record.dateTime = now.toISOString();
            record.timeIn = now.toLocaleTimeString();
            store.put(record);
        } else {
            // Add new record
            const lastId = allRecords.length ? Math.max(...allRecords.map(r => Number(r.userId))) : 0;
            record = {
                userId: lastId + 1,
                uniqueId: taskId,
                uryyToeSS4: clientId,
                col_status: statusSelected || 'Not selected',
                carer_name: 'Current Carer',
                carer_Id: 'C001',
                care_calls: careCallFromURL,
                col_company_Id: 'COMP123',
                dateTime: now.toISOString(),
                timeIn: now.toLocaleTimeString(),
                note: notes
            };
            if (activity.type === 'task') {
                record.task = activity.client_taskName || '--';
                record.task_date = urlDate;
            } else {
                record.meds = activity.med_name || '--';
                record.med_date = urlDate;
            }
            store.add(record);
        }

        // Redirect back to activities page
        window.location.href = `activities.php?uryyToeSS4=${clientId}&Clientshift_Date=${urlDate}&care_calls=${careCallFromURL}&userId=${userId}&carerId=${carerId}`;
    });

    // Handle 'Copy' button
    document.getElementById('continueBtn').addEventListener('click', (e) => {
        e.preventDefault();
        const text = document.getElementById('reportText').value;
        navigator.clipboard.writeText(text).then(() => {
            window.location.href = `activities.php?uryyToeSS4=${clientId}&Clientshift_Date=${urlDate}&care_calls=${careCallFromURL}&userId=${userId}&carerId=${carerId}`;
        });
    });

    // Initialize
    renderClientProfileAndHighlight();
    renderSelectedActivity();
</script>


<?php include_once 'footer.php'; ?>