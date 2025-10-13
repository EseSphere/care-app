<?php include_once 'header.php'; ?>
<style>
    .status-updated,
    .status-not-updated {
        white-space: nowrap;
        font-weight: bold;
    }

    .status-updated {
        color: green;
    }

    .status-not-updated {
        color: white;
        background-color: grey;
        /* Added for Pending status */
        padding: 0.2rem 0.5rem;
        border-radius: 0.25rem;
    }

    .prn-badge {
        background-color: red;
        color: white;
        border-radius: 50%;
        padding: 0.2rem 0.5rem;
        font-size: 0.8rem;
        margin-left: 5px;
    }
</style>


<div class="main-wrapper container">

    <!-- Client Profile Horizontal Layout -->
    <?php require_once 'client-profile-extension.php'; ?>

    <!-- Care Activities -->
    <div class="card p-3">
        <h5 class="w-100">
            Visit Activities
            <button class="btn btn-warning prn-btn text-end" data-bs-toggle="modal" data-bs-target="#prnModal" style="display:inline-block;">
                <i class="bi bi-bandaid"></i> PRN
                <span class="prn-badge" id="prnCount" style="display:none;">0</span>
            </button>
        </h5>
        <hr>
        <div id="careActivitiesContainer">
            <!-- Activities will be injected here dynamically -->
        </div>
    </div>

    <!-- Start Button -->
    <div class="col-md-12 mt-3">
        <a href="" id="continueBtn" class="btn btn-primary btn-lg">Continue</a>
    </div>

    <!-- Highlight -->
    <div class="col-md-12 mt-3">
        <div class="card p-3">
            <div class="row">
                <div class="col-sm-4 mb-3 fs-5 fw-bold">Highlight:</div>
                <hr>
                <div class="col-sm-8 fs-6" id="highlight">Loading...</div>
            </div>
        </div>
    </div>

</div>

<!-- PRN Modal -->
<div class="modal fade" id="prnModal" tabindex="-1" aria-labelledby="prnModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">PRN Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Log PRN medication or task here.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" class="btn btn-primary" id="logPRNBtn">Log PRN</a>
            </div>
        </div>
    </div>
</div>

<script>
    // Get URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const clientId = urlParams.get('uryyToeSS4');
    const clientshift_date = urlParams.get('Clientshift_Date'); // Updated
    const careCall = urlParams.get('care_calls');
    const userId = urlParams.get('userId');
    const carerId = urlParams.get('carerId');

    const continueBtn = document.getElementById('continueBtn');

    continueBtn.addEventListener('click', (e) => {
        e.preventDefault(); // Prevent default <a> behavior
        // Use clientshift_date from URL
        const url = `processing-tasks.php?uryyToeSS4=${clientId}&Clientshift_Date=${clientshift_date}&care_calls=${careCall}&userId=${userId}&carerId=${carerId}`;
        window.location.href = url;
    });


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

    async function openDB() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open('care_app');
            request.onsuccess = e => resolve(e.target.result);
            request.onerror = e => reject(e.target.error);
        });
    }

    async function fetchRecords(storeName, clientId, careCall) {
        const db = await openDB();
        return new Promise((resolve, reject) => {
            if (!db.objectStoreNames.contains(storeName)) return resolve([]);
            const tx = db.transaction(storeName, 'readonly');
            const store = tx.objectStore(storeName);
            const req = store.getAll();
            req.onsuccess = () => {
                const results = req.result.filter(r =>
                    r.uryyToeSS4 === clientId &&
                    (
                        r.care_call1 === careCall || r.care_call2 === careCall ||
                        r.care_call3 === careCall || r.care_call4 === careCall ||
                        r.extra_call1 === careCall || r.extra_call2 === careCall ||
                        r.extra_call3 === careCall || r.extra_call4 === careCall
                    )
                );
                resolve(results);
            };
            req.onerror = () => reject(`Failed to fetch records from ${storeName}`);
        });
    }

    async function fetchFinishedRecords(storeName, clientId, date, careCall) {
        const db = await openDB();
        return new Promise((resolve, reject) => {
            if (!db.objectStoreNames.contains(storeName)) return resolve([]);
            const tx = db.transaction(storeName, 'readonly');
            const store = tx.objectStore(storeName);
            const req = store.getAll();
            req.onsuccess = () => {
                const filtered = req.result.filter(r =>
                    r.uryyToeSS4 === clientId &&
                    (r.task_date === date || r.med_date === date) &&
                    r.care_calls === careCall
                );
                resolve(filtered);
            };
            req.onerror = () => reject(`Failed to fetch finished records from ${storeName}`);
        });
    }

    function getStatus(record, finishedTasks, finishedMeds) {
        if (record.type === 'task') {
            const found = finishedTasks.find(f => f.uniqueId === record.col_taskId && f.uryyToeSS4 === record.uryyToeSS4);
            return found ? found.col_status || 'Updated' : 'Pending';
        } else if (record.type === 'medication') {
            const found = finishedMeds.find(f => f.uniqueId === record.col_taskId && f.uryyToeSS4 === record.uryyToeSS4);
            return found ? found.col_status || 'Updated' : 'Pending';
        }
        return 'Pending';
    }

    async function getClientDetails(uryyToeSS4) {
        if (!uryyToeSS4) return null;
        const db = await openDB();
        return new Promise((resolve, reject) => {
            const tx = db.transaction('tbl_general_client_form', 'readonly');
            const store = tx.objectStore('tbl_general_client_form');
            const req = store.getAll();
            req.onsuccess = e => {
                const client = e.target.result.find(c => c.uryyToeSS4 === uryyToeSS4);
                resolve(client || null);
            };
            req.onerror = e => reject(e.target.error);
        });
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
            highlightDiv.innerHTML = paragraphs.map(p => `<p>${p.trim().replace(/\n/g, '<br>')}</p>`).join('');
        } else {
            highlightDiv.innerHTML = '<p>No highlights available.</p>';
        }
    }

    renderClientProfileAndHighlight();

    async function renderActivities() {
        const container = document.getElementById('careActivitiesContainer');
        container.innerHTML = '<p>Loading activities...</p>';

        const prnButton = document.querySelector('.prn-btn');
        const prnCountSpan = document.getElementById('prnCount');
        const prnModalBody = document.querySelector('#prnModal .modal-body');
        const logPRNBtn = document.getElementById('logPRNBtn');

        try {
            const meds = await fetchRecords('tbl_clients_medication_records', clientId, careCall);
            const tasks = await fetchRecords('tbl_clients_task_records', clientId, careCall);

            const finishedMeds = await fetchFinishedRecords('tbl_finished_meds', clientId, clientshift_date, careCall); // Updated
            const finishedTasks = await fetchFinishedRecords('tbl_finished_tasks', clientId, clientshift_date, careCall); // Updated

            const prnMeds = [];

            const activities = [
                ...tasks.map(t => ({
                    type: 'task',
                    title: t.client_taskName,
                    status: getStatus({
                        ...t,
                        type: 'task'
                    }, finishedTasks, finishedMeds),
                    details: t.client_task_details,
                    recordId: t.id || t.col_taskId,
                    ...t
                })),
                ...meds.map(m => {
                    if (m.med_package && m.med_package.toUpperCase() === 'PRN') {
                        prnMeds.push({
                            display: `${m.med_name} (${m.med_dosage})`,
                            recordId: m.id || m.uniqueId || m.col_taskId
                        });
                    }
                    return {
                        type: 'medication',
                        title: `${m.med_name} (${m.med_dosage})`,
                        status: getStatus({
                            ...m,
                            type: 'medication'
                        }, finishedTasks, finishedMeds),
                        details: m.med_details,
                        recordId: m.id || m.uniqueId || m.col_taskId,
                        ...m
                    };
                })
            ];

            activities.sort((a, b) => {
                if (a.status === 'Pending' && b.status !== 'Pending') return -1;
                if (a.status !== 'Pending' && b.status === 'Pending') return 1;
                return 0;
            });

            container.innerHTML = '';
            activities.forEach(c => {
                const icon = c.type === 'task' ? 'bi-list-task' : 'bi-capsule';
                const color = c.type === 'task' ? '#0d6efd' : '#fd7e14';
                const statusClass = c.status === 'Updated' || c.status === 'Completed' || c.status === 'Given' ? 'status-updated' : 'status-not-updated';
                const recordId = c.recordId;

                const div = document.createElement('div');
                div.className = 'care-item';
                div.style.background = `${color}20`;
                div.style.cursor = 'pointer';
                div.onclick = () => {
                    if (recordId) window.location.href = `activity-report.php?col_taskId=${recordId}&clientId=${clientId}&care_calls=${careCall}&date=${clientshift_date}&userId=${userId}&carerId=${carerId}`; // Updated
                };
                div.innerHTML = `<div><i class="bi ${icon} care-icon" style="color:${color}"></i> ${c.title}</div>
                             <span class="${statusClass}">${c.status}</span>`;
                container.appendChild(div);
            });

            // PRN modal
            if (prnMeds.length > 0) {
                prnModalBody.innerHTML = `<ul>${prnMeds.map(m => `<li>${m.display}</li>`).join('')}</ul>`;
                prnButton.style.display = 'inline-block';
                prnCountSpan.textContent = prnMeds.length;
                prnCountSpan.style.display = 'inline-block';
                const firstPRN = prnMeds[0];
                if (firstPRN.recordId) {
                    logPRNBtn.setAttribute('href', `activity-report.php?col_taskId=${firstPRN.recordId}&clientId=${clientId}&care_calls=${careCall}&date=${clientshift_date}&userId=${userId}&carerId=${carerId}`); // Updated
                } else logPRNBtn.removeAttribute('href');
            } else {
                prnModalBody.innerHTML = 'No PRN medications for today.';
                prnButton.style.display = 'inline-block';
                prnCountSpan.textContent = 0;
                prnCountSpan.style.display = 'inline-block';
                logPRNBtn.removeAttribute('href');
            }

        } catch (err) {
            container.innerHTML = `<p class="text-danger">Failed to load activities: ${err}</p>`;
            document.getElementById('highlight').textContent = 'Error loading highlight';
            prnButton.style.display = 'inline-block';
            prnCountSpan.textContent = 0;
            prnCountSpan.style.display = 'inline-block';
            prnModalBody.innerHTML = 'No PRN medications for today.';
            logPRNBtn.removeAttribute('href');
        }
    }

    // Initial render
    renderActivities();

    // Auto-refresh when user returns from activity-report.php
    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') {
            renderActivities();
        }
    });
</script>

<?php include_once 'footer.php'; ?>