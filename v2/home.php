<?php include_once 'header1.php'; ?>

<div id="overlay"></div>

<div class="topbar mb-3 p-2">
    <div class="d-flex align-items-center justify-content-between mb-2">
        <button class="menu-btn fs-1" id="menuBtn"><i class="bi bi-list"></i></button>
        <h4 class="mb-0">Care App</h4>
        <div class="d-flex align-items-center gap-2">
            <div class="chip"><span id="today-clock">--:--</span></div>
            <button class="btn btn-sm btn-light" id="refreshBtn" title="Refresh"><i class="bi bi-arrow-clockwise"></i></button>
            <button class="btn btn-sm btn-light" id="todayBtn" title="Today"><i class="bi bi-calendar-check"></i></button>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2">
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-sm btn-light" id="prevDay">‹</button>
            <button class="btn btn-sm btn-light" id="nextDay">›</button>
        </div>
        <div class="date-strip w-100" id="dateStrip" aria-label="pick a date"></div>
        <div class="text-end">
            <div class="small-muted">Hours</div>
            <div class="hour-total" id="totalHours">0h 0m</div>
        </div>
    </div>
    <div class="mt-2 mb-2">
        <div class="small-muted">Completion Progress</div>
        <div class="progress" style="height:8px;">
            <div class="progress-bar" role="progressbar" id="progressBar" style="width:0%"></div>
        </div>
    </div>
</div>

<div class="container py-3">
    <div class="row mb-3">
        <div class="col-12 col-lg-8">
            <input type="text" class="form-control mb-2" id="searchVisits" placeholder="Search visits by client name">
            <div class="card p-3 visits-list" id="visitsContainer"></div>
        </div>
        <div class="col-12 col-lg-4 mt-3 mt-lg-0">
            <div class="card p-3 fade-in-up">
                <h6>Quick stats</h6>
                <ul class="list-unstyled small-muted mb-0">
                    <li>Calls today: <strong id="countCalls">0</strong></li>
                    <li>Connected: <span id="connStatus" class="badge bg-success">Online</span></li>
                    <li id="offlineStatus" style="display:none; color:red;">Offline</li>
                    <li>Run name: <strong id="runName">N/A</strong></li>
                </ul>
            </div>
            <div class="card p-3 mt-3">
                <h6>Alerts</h6>
                <div id="alertsContainer" class="alerts-container small-muted"></div>
            </div>
        </div>
    </div>
</div>

<template id="visitTpl">
    <div class="card mb-3 visit-item fade-in-up">
        <div class="card-body p-3">
            <div class="visit-row">
                <div class="avatar"><img src="" alt="user"></div>
                <div class="visit-details">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="h6 mb-0 name"></div>
                            <div class="small-muted service"></div>
                        </div>
                        <div class="text-end carers-icons"></div>
                    </div>
                    <div class="mt-2 d-flex justify-content-between align-items-center">
                        <div class="small-muted times">09:00 - 10:00</div>
                        <div><span class="badge badge-status status">Scheduled</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<div class="footer">
    <button onclick="history.back()" title="Back" id="btn-back"><i class="bi bi-arrow-left"></i></button>
    <a href="./home" title="Home"><i class="bi bi-house"></i></a>
    <a href="./visit-logs" title="Log"><i class="bi bi-journal-text"></i></a>
    <a href="./settings" title="User"><i class="bi bi-person"></i></a>
</div>

<!-- Second Carer Modal -->
<div class="modal fade" id="secondCarerModal" tabindex="-1" aria-labelledby="secondCarerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 shadow-sm">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="secondCarerModalLabel">Second Carer</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <p class="fw-bold mb-1">Run Name: <span id="modalRunName" class="text-primary"></span></p>
                <p class="mb-0">Second Carer: <span id="modalCarerName" class="fw-semibold"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="./js/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="./js/jquery-3.7.0.min.js"></script>
<script>
    AOS.init();

    // --- Local date helpers ---
    function formatLocalISO(d) {
        const y = d.getFullYear();
        const m = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        return `${y}-${m}-${day}`;
    }

    function parseDateTimeLocal(dateStr, timeStr) {
        const [y, m, d] = dateStr.split('-').map(Number);
        const [hh = 0, mm = 0] = (timeStr || '').split(':').map(Number);
        return new Date(y, m - 1, d, hh, mm, 0);
    }

    // --- Initial setup ---
    let selectedDate = formatLocalISO(new Date());
    const dateStrip = document.getElementById('dateStrip');
    const visitsContainer = document.getElementById('visitsContainer');
    const avatarColors = ['#f44336', '#e91e63', '#9c27b0', '#673ab7', '#3f51b5', '#2196f3', '#03a9f4', '#00bcd4', '#009688', '#4caf50', '#8bc34a', '#cddc39', '#ffeb3b', '#ffc107', '#ff9800', '#ff5722', '#795548', '#607d8b'];

    function getInitials(name) {
        const parts = name.split(' ');
        return (parts[0]?.[0] || '').toUpperCase() + (parts.at(-1)?.[0] || '').toUpperCase();
    }

    function getColorForName(name) {
        let hash = 0;
        for (let i = 0; i < name.length; i++) hash = name.charCodeAt(i) + ((hash << 5) - hash);
        return avatarColors[Math.abs(hash) % avatarColors.length];
    }

    // --- IndexedDB helper ---
    function openDB() {
        return new Promise((res, rej) => {
            const req = indexedDB.open('care_app');
            req.onsuccess = e => res(e.target.result);
            req.onerror = e => rej(e.target.error);
        });
    }

    // --- Cached data ---
    let userSpecialId = null;
    let visitsCache = [];
    let cancelledCallsCache = [];
    let clientStatusCache = [];

    // --- Load userSpecialId ---
    async function loadUserSpecialId() {
        const db = await openDB();
        const tx = db.transaction('tbl_goesoft_carers_account', 'readonly');
        const store = tx.objectStore('tbl_goesoft_carers_account');
        const req = store.getAll();
        return new Promise((res, rej) => {
            req.onsuccess = e => {
                const users = e.target.result;
                userSpecialId = users.length ? users[0].user_special_Id : null;
                res(userSpecialId);
            };
            req.onerror = e => rej(e.target.error);
        });
    }

    // --- Load cancelled calls ---
    async function loadCancelledCalls() {
        const db = await openDB();
        return new Promise((res, rej) => {
            const tx = db.transaction('tbl_cancelled_call', 'readonly');
            const store = tx.objectStore('tbl_cancelled_call');
            const arr = [];
            const req = store.openCursor();
            req.onsuccess = e => {
                const cursor = e.target.result;
                if (cursor) {
                    const c = cursor.value;
                    arr.push({
                        clientId: c.col_client_Id,
                        date: c.col_date,
                        careCall: c.col_care_call
                    });
                    cursor.continue();
                } else {
                    cancelledCallsCache = arr;
                    res(arr);
                }
            };
            req.onerror = e => rej(e.target.error);
        });
    }

    // --- Load client status records ---
    async function loadClientStatusRecords() {
        const db = await openDB();
        return new Promise((res, rej) => {
            const tx = db.transaction('tbl_client_status_records', 'readonly');
            const store = tx.objectStore('tbl_client_status_records');
            const arr = [];
            const req = store.openCursor();
            req.onsuccess = e => {
                const cursor = e.target.result;
                if (cursor) {
                    const r = cursor.value;
                    arr.push({
                        clientId: r.col_client_Id,
                        start: r.col_start_date,
                        end: r.col_end_date
                    });
                    cursor.continue();
                } else {
                    clientStatusCache = arr;
                    res(arr);
                }
            };
            req.onerror = e => rej(e.target.error);
        });
    }

    // --- Load visits ---
    async function loadVisits() {
        if (!userSpecialId) return;
        const db = await openDB();
        return new Promise((res, rej) => {
            const tx = db.transaction('tbl_schedule_calls', 'readonly');
            const store = tx.objectStore('tbl_schedule_calls');
            const arr = [];
            const req = store.openCursor();
            req.onsuccess = e => {
                const cursor = e.target.result;
                if (cursor) {
                    const v = cursor.value;
                    if (v.first_carer_Id === userSpecialId) arr.push(v);
                    cursor.continue();
                } else {
                    visitsCache = arr;
                    res(arr);
                }
            };
            req.onerror = e => rej(e.target.error);
        });
    }

    // --- Filter visits by date, cancelled and client status ---
    function getFilteredVisits(dateStr) {
        return visitsCache.filter(v => {
            const isCancelled = cancelledCallsCache.some(c =>
                c.clientId === v.uryyToeSS4 && c.date === v.Clientshift_Date && c.careCall === v.care_calls
            );
            const hasStatus = clientStatusCache.some(r =>
                r.clientId === v.uryyToeSS4 &&
                ((v.Clientshift_Date >= r.start && v.Clientshift_Date <= r.end) || (v.Clientshift_Date === r.start && r.end === 'TFN'))
            );
            return !isCancelled && !hasStatus && v.Clientshift_Date === dateStr;
        }).sort((a, b) => (a.dateTime_in || '').localeCompare(b.dateTime_in || ''));
    }

    // --- Render visits ---
    function renderVisitsFiltered(v) {
        visitsContainer.innerHTML = '';
        if (!v.length) {
            visitsContainer.innerHTML = '<div class="text-center small-muted p-5">No visits found</div>';
            document.getElementById('totalHours').textContent = '0h 0m';
            updateQuickStats([]);
            updateProgress([]);
            document.getElementById('runName').textContent = 'N/A';
            return;
        }
        const tpl = document.getElementById('visitTpl');
        let total = 0;
        v.forEach(vis => {
            const node = document.importNode(tpl.content, true);
            const card = node.querySelector('.card');
            card.style.cursor = 'pointer';
            card.addEventListener('click', () => location.href = `care-plan?userId=${vis.userId}`);
            const initials = getInitials(vis.client_name);
            const color = getColorForName(vis.client_name);
            node.querySelector('.avatar').innerHTML = `<div class="avatar-initials" style="background:${color};color:#fff;font-weight:bold;border-radius:.5rem;width:4rem;height:4rem;display:flex;align-items:center;justify-content:center;font-size:1.8rem">${initials}</div>`;
            node.querySelector('.name').textContent = vis.client_name;
            node.querySelector('.service').textContent = vis.care_calls;
            node.querySelector('.times').textContent = `${vis.dateTime_in} - ${vis.dateTime_out}`;
            const carers = node.querySelector('.carers-icons');
            carers.innerHTML = '';
            if (vis.col_required_carers == 2) carers.innerHTML = '👥';
            else if (vis.col_required_carers > 2) carers.innerHTML = '👤'.repeat(vis.col_required_carers);
            const badge = node.querySelector('.status');
            badge.textContent = (vis.call_status || 'scheduled').toLowerCase();
            badge.className = 'badge badge-status ms-1';
            if (vis.call_status === 'scheduled') badge.classList.add('bg-info', 'text-dark');
            if (vis.call_status === 'in-progress') badge.classList.add('bg-warning', 'text-dark');
            if (vis.call_status === 'completed') badge.classList.add('bg-success');
            const start = parseDateTimeLocal(vis.Clientshift_Date, vis.dateTime_in);
            const end = parseDateTimeLocal(vis.Clientshift_Date, vis.dateTime_out);
            total += (end - start) / 60000;
            const now = new Date();
            if (start > now && start - now <= 3600000) card.style.border = '2px solid var(--accent2)';
            if (start < now && vis.call_status !== 'completed') card.style.border = '2px solid red';
            visitsContainer.appendChild(node);
        });
        const h = Math.floor(total / 60),
            m = Math.floor(total % 60);
        document.getElementById('totalHours').textContent = `${h}h ${m}m`;
        updateQuickStats(v);
        updateProgress(v);
        document.getElementById('runName').textContent = v[0]?.col_run_name || 'N/A';
    }

    // --- Render timeline alerts ---
    function renderTimelineAndAlerts(v) {
        const alerts = document.getElementById('alertsContainer');
        alerts.innerHTML = '';
        const now = new Date();
        v.forEach(x => {
            const s = parseDateTimeLocal(x.Clientshift_Date, x.dateTime_in);
            const e = parseDateTimeLocal(x.Clientshift_Date, x.dateTime_out);
            const div = document.createElement('div');
            if (s > now && s - now <= 3600000) {
                div.className = 'alert-item text-info';
                div.textContent = `Upcoming: ${x.client_name} at ${x.dateTime_in}`;
            } else if (e < now && x.call_status !== 'completed') {
                div.className = 'alert-item text-danger';
                div.textContent = `Overdue: ${x.client_name} (${x.dateTime_in}-${x.dateTime_out})`;
            }
            if (div.textContent) alerts.appendChild(div);
        });
        if (!alerts.hasChildNodes()) alerts.textContent = 'No alerts for today';
    }

    // --- Quick stats and progress ---
    function updateQuickStats(v) {
        document.getElementById('countCalls').textContent = v.length;
    }

    function updateProgress(v) {
        const bar = document.getElementById('progressBar');
        if (!v.length) {
            bar.style.width = '0%';
            return;
        }
        const done = v.filter(x => x.call_status === 'completed').length;
        bar.style.width = Math.round(done / v.length * 100) + '%';
    }

    // --- Scroll to active date ---
    function scrollToActiveDate() {
        const active = document.querySelector('.date-pill.active');
        if (active) active.scrollIntoView({
            behavior: 'smooth',
            inline: 'center'
        });
    }

    // --- Render date pills ---
    function renderDatePills(centerDate) {
        dateStrip.innerHTML = '';
        const y = centerDate.getFullYear(),
            m = centerDate.getMonth();
        const daysInMonth = new Date(y, m + 1, 0).getDate();
        for (let i = 1; i <= daysInMonth; i++) {
            const d = new Date(y, m, i);
            const iso = formatLocalISO(d);
            const pill = document.createElement('div');
            pill.className = 'date-pill';
            pill.dataset.date = iso;
            pill.innerHTML = `<div style="font-weight:600">${d.toLocaleDateString(undefined,{weekday:'short'})}</div>
        <div style="font-size:.85rem;position:relative">${d.getDate()} ${d.toLocaleString(undefined,{month:'short'})}</div>`;
            if (visitsCache.some(v => v.Clientshift_Date === iso)) {
                const dot = document.createElement('div');
                dot.style.cssText = 'width:6px;height:6px;background:#bdc3c7;border-radius:50%;position:absolute;bottom:-5px;left:50%;transform:translateX(-50%)';
                pill.querySelector('div:nth-child(2)').appendChild(dot);
            }
            if (iso === selectedDate) pill.classList.add('active');
            pill.addEventListener('click', () => {
                selectedDate = iso;
                renderDatePills(centerDate);
                renderFilteredVisits();
                setTimeout(scrollToActiveDate, 100);
            });
            dateStrip.appendChild(pill);
        }
        setTimeout(scrollToActiveDate, 100);
    }

    // --- Render filtered visits ---
    function renderFilteredVisits() {
        const filtered = getFilteredVisits(selectedDate);
        renderVisitsFiltered(filtered);
        renderTimelineAndAlerts(filtered);
    }

    // --- Clock ---
    function updateClock() {
        document.getElementById('today-clock').textContent = new Date().toLocaleTimeString([], {
            hour: '2-digit',
            minute: '2-digit'
        });
    }
    setInterval(updateClock, 1000);
    updateClock();

    // --- Navigation buttons ---
    document.getElementById('prevDay').onclick = () => {
        const d = new Date(selectedDate + 'T00:00');
        d.setDate(d.getDate() - 1);
        selectedDate = formatLocalISO(d);
        renderDatePills(d);
        renderFilteredVisits();
    };
    document.getElementById('nextDay').onclick = () => {
        const d = new Date(selectedDate + 'T00:00');
        d.setDate(d.getDate() + 1);
        selectedDate = formatLocalISO(d);
        renderDatePills(d);
        renderFilteredVisits();
    };
    document.getElementById('todayBtn').onclick = () => {
        selectedDate = formatLocalISO(new Date());
        renderDatePills(new Date());
        renderFilteredVisits();
    };
    document.getElementById('refreshBtn').onclick = () => renderFilteredVisits();
    document.getElementById('searchVisits').addEventListener('input', e => {
        const t = e.target.value.toLowerCase();
        renderVisitsFiltered(getFilteredVisits(selectedDate).filter(v => v.client_name.toLowerCase().includes(t)));
        renderTimelineAndAlerts(getFilteredVisits(selectedDate).filter(v => v.client_name.toLowerCase().includes(t)));
    });

    // --- Offline indicators ---
    window.addEventListener('offline', () => document.getElementById('offlineStatus').style.display = 'inline-block');
    window.addEventListener('online', () => {
        document.getElementById('offlineStatus').style.display = 'none';
        localStorage.removeItem('offlineQueue');
    });

    // --- Side menu ---
    const menuBtn = document.getElementById('menuBtn');
    const sideNav = document.getElementById('sideNav');
    const overlay = document.getElementById('overlay');
    menuBtn.onclick = () => {
        sideNav.classList.add('open');
        overlay.classList.add('show');
    };
    overlay.onclick = () => {
        sideNav.classList.remove('open');
        overlay.classList.remove('show');
    };

    // --- Initialize ---
    (async () => {
        await loadUserSpecialId();
        await Promise.all([loadCancelledCalls(), loadClientStatusRecords(), loadVisits()]);
        renderDatePills(new Date());
        renderFilteredVisits();
    })();
</script>


<?php include_once 'footer.php'; ?>