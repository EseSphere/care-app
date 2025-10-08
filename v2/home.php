<?php include_once 'header1.php'; ?>

<div id="overlay"></div>

<div class="topbar mb-3 p-2">
    <div class="d-flex align-items-center justify-content-between mb-2">
        <button class="menu-btn fs-1" id="menuBtn"><i class="bi bi-list"></i></button>
        <h4 class="mb-0">Geosoft</h4>
        <div class="d-flex align-items-center gap-2">
            <div class="chip"><span id="today-clock">--:--</span></div>
            <button class="btn btn-sm btn-light" id="refreshBtn" title="Refresh"><i class="bi bi-arrow-clockwise"></i></button>
            <button class="btn btn-sm btn-light" id="todayBtn" title="Today"><i class="bi bi-calendar-check"></i></button>
            <button class="btn btn-sm btn-light" id="darkModeBtn" title="Dark Mode"><i class="bi bi-moon"></i></button>
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

<!-- Libraries -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="./js/jquery-3.7.0.min.js"></script>

<script>
    AOS.init();

    let selectedDate = new Date().toISOString().slice(0, 10);
    const dateStrip = document.getElementById('dateStrip');
    const visitsContainer = document.getElementById('visitsContainer');

    const avatarColors = ['#f44336', '#e91e63', '#9c27b0', '#673ab7', '#3f51b5', '#2196f3', '#03a9f4', '#00bcd4', '#009688', '#4caf50', '#8bc34a', '#cddc39', '#ffeb3b', '#ffc107', '#ff9800', '#ff5722', '#795548', '#607d8b'];

    function getInitials(name) {
        const parts = name.split(' ');
        return (parts[0]?.charAt(0).toUpperCase() || '') + (parts[parts.length - 1]?.charAt(0).toUpperCase() || '');
    }

    function getColorForName(name) {
        let hash = 0;
        for (let i = 0; i < name.length; i++) hash = name.charCodeAt(i) + ((hash << 5) - hash);
        return avatarColors[Math.abs(hash) % avatarColors.length];
    }

    function addDays(d, n) {
        const x = new Date(d);
        x.setDate(x.getDate() + n);
        return x;
    }

    function openDB() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open('geosoft');
            request.onsuccess = e => resolve(e.target.result);
            request.onerror = e => reject(e.target.error);
        });
    }

    // Get user_special_Id from tbl_goesoft_carers_account
    function getUserSpecialId() {
        return new Promise(async (resolve, reject) => {
            try {
                const db = await openDB();
                const tx = db.transaction('tbl_goesoft_carers_account', 'readonly');
                const store = tx.objectStore('tbl_goesoft_carers_account');
                const req = store.getAll(); // assuming single user or first user
                req.onsuccess = e => {
                    const users = e.target.result;
                    if (users.length > 0) resolve(users[0].user_special_Id);
                    else resolve(null);
                };
                req.onerror = e => reject(e.target.error);
            } catch (err) {
                reject(err);
            }
        });
    }

    // Fetch visits from tbl_schedule_calls filtered by first_carer_Id = user_special_Id
    function getVisitsFromDB(userSpecialId) {
        return new Promise(async (resolve, reject) => {
            try {
                const db = await openDB();
                const tx = db.transaction('tbl_schedule_calls', 'readonly');
                const store = tx.objectStore('tbl_schedule_calls');
                const allVisits = [];
                const req = store.openCursor();
                req.onsuccess = e => {
                    const cursor = e.target.result;
                    if (cursor) {
                        const v = cursor.value;
                        if (v.first_carer_Id === userSpecialId) {
                            allVisits.push({
                                id: v.userId,
                                name: v.client_name,
                                service: v.client_area,
                                date: v.Clientshift_Date,
                                time_in: v.dateTime_in,
                                time_out: v.dateTime_out,
                                carers: parseInt(v.col_required_carers) || 1,
                                status: v.call_status.toLowerCase(),
                                runName: v.col_run_name
                            });
                        }
                        cursor.continue();
                    } else resolve(allVisits);
                };
                req.onerror = e => reject(e.target.error);
            } catch (err) {
                reject(err);
            }
        });
    }

    // Render visits for selected date
    async function renderVisits() {
        const userSpecialId = await getUserSpecialId();
        if (!userSpecialId) {
            visitsContainer.innerHTML = '<div class="text-center small-muted p-5">No user found</div>';
            return;
        }
        const allVisits = await getVisitsFromDB(userSpecialId);
        const filtered = allVisits.filter(v => v.date === selectedDate);
        renderVisitsFiltered(filtered);
        renderTimelineAndAlerts(filtered);
    }

    function renderDatePills(centerDate) {
        dateStrip.innerHTML = '';
        for (let i = -7; i <= 7; i++) {
            const d = addDays(centerDate, i);
            const iso = d.toISOString().slice(0, 10);
            const pill = document.createElement('div');
            pill.className = 'date-pill';
            pill.dataset.date = iso;
            pill.innerHTML = `<div style="font-weight:600">${d.toLocaleDateString(undefined,{weekday:'short'})}</div><div style="font-size:.85rem">${d.getDate()} ${d.toLocaleString(undefined,{month:'short'})}</div>`;
            if (iso === selectedDate) pill.classList.add('active');
            pill.addEventListener('click', () => {
                selectedDate = iso;
                renderDatePills(centerDate);
                renderVisits();
                setTimeout(scrollToActiveDate, 100);
            });
            dateStrip.appendChild(pill);
        }
    }

    function scrollToActiveDate() {
        const activePill = document.querySelector('.date-pill.active');
        if (activePill) activePill.scrollIntoView({
            behavior: 'smooth',
            inline: 'center'
        });
    }

    function updateQuickStats(visits) {
        document.getElementById('countCalls').textContent = visits.length;
    }

    function updateProgress(visits) {
        if (!visits.length) {
            document.getElementById('progressBar').style.width = '0%';
            return;
        }
        const completed = visits.filter(v => v.status === 'completed').length;
        document.getElementById('progressBar').style.width = Math.round((completed / visits.length) * 100) + '%';
    }

    function renderVisitsFiltered(visits) {
        visitsContainer.innerHTML = '';
        if (!visits.length) {
            visitsContainer.innerHTML = '<div class="text-center small-muted p-5">No visits found</div>';
            document.getElementById('totalHours').textContent = '0h 0m';
            updateQuickStats([]);
            updateProgress([]);
            document.getElementById('runName').textContent = 'N/A';
            return;
        }
        const tpl = document.getElementById('visitTpl');
        let totalMinutes = 0;

        visits.forEach(v => {
            const node = document.importNode(tpl.content, true);
            const avatarDiv = node.querySelector('.avatar');
            const initials = getInitials(v.name);
            const color = getColorForName(v.name);

            // Always show main avatar
            avatarDiv.innerHTML = `<div class="avatar-initials" style="background-color:${color};color:white;font-weight:bold;border-radius:.5rem;width:4rem;height:4rem;display:flex;align-items:center;justify-content:center;font-size:1.8rem;flex-shrink:0;transition:transform .2s">${initials}</div>`;
            avatarDiv.querySelector('.avatar-initials').addEventListener('mouseenter', e => e.currentTarget.style.transform = 'scale(1.05)');
            avatarDiv.querySelector('.avatar-initials').addEventListener('mouseleave', e => e.currentTarget.style.transform = 'scale(1)');

            const nameEl = node.querySelector('.name');
            nameEl.textContent = v.name;
            nameEl.style.cursor = 'pointer';
            nameEl.addEventListener('click', () => window.location.href = `care-plan?id=${v.id}`);
            node.querySelector('.service').textContent = v.service;
            node.querySelector('.times').textContent = `${v.time_in} - ${v.time_out}`;
            const carersDiv = node.querySelector('.carers-icons');
            carersDiv.innerHTML = '';

            // Carers icon logic: 
            if (v.carers === 2) {
                // Show one “combined” icon for two carers
                carersDiv.innerHTML = '<span>👥</span>';
            } else if (v.carers > 2) {
                for (let j = 0; j < v.carers; j++) carersDiv.innerHTML += '<span>👤</span>';
            }
            // carers = 1 → show nothing

            const badge = node.querySelector('.status');
            badge.textContent = v.status.replace('-', ' ');
            badge.className = 'badge badge-status ms-1';
            if (v.status === 'scheduled') badge.classList.add('bg-info', 'text-dark');
            if (v.status === 'in-progress') badge.classList.add('bg-warning', 'text-dark');
            if (v.status === 'completed') badge.classList.add('bg-success');

            const visitStart = new Date(`${v.date}T${v.time_in}:00`);
            const visitEnd = new Date(`${v.date}T${v.time_out}:00`);
            totalMinutes += (visitEnd - visitStart) / 60000;
            const now = new Date();
            if (visitStart > now && visitStart - now <= 3600000) node.querySelector('.card').style.border = '2px solid var(--accent2)';
            if (visitStart < now && v.status !== 'completed') node.querySelector('.card').style.border = '2px solid red';

            visitsContainer.appendChild(node);
        });

        const hours = Math.floor(totalMinutes / 60);
        const minutes = Math.floor(totalMinutes % 60);
        document.getElementById('totalHours').textContent = `${hours}h ${minutes}m`;
        updateQuickStats(visits);
        updateProgress(visits);
        document.getElementById('runName').textContent = visits[0]?.runName || 'N/A';
    }


    function renderTimelineAndAlerts(visits) {
        const alertsContainer = document.getElementById('alertsContainer');
        alertsContainer.innerHTML = '';
        const now = new Date();
        visits.forEach(v => {
            const visitStart = new Date(`${v.date}T${v.time_in}:00`);
            const visitEnd = new Date(`${v.date}T${v.time_out}:00`);
            if (visitStart > now && visitStart - now <= 3600000) {
                const alert = document.createElement('div');
                alert.className = 'alert-item text-info';
                alert.textContent = `Upcoming: ${v.name} at ${v.time_in}`;
                alertsContainer.appendChild(alert);
            } else if (visitEnd < now && v.status !== 'completed') {
                const alert = document.createElement('div');
                alert.className = 'alert-item text-danger';
                alert.textContent = `Overdue: ${v.name} (${v.time_in} - ${v.time_out})`;
                alertsContainer.appendChild(alert);
            }
        });
        if (!alertsContainer.hasChildNodes()) alertsContainer.textContent = 'No alerts for today';
    }

    function updateClock() {
        document.getElementById('today-clock').textContent = new Date().toLocaleTimeString(undefined, {
            hour: '2-digit',
            minute: '2-digit'
        });
    }
    setInterval(updateClock, 1000);
    updateClock();

    renderDatePills(new Date());
    renderVisits();
    setTimeout(scrollToActiveDate, 100);

    document.getElementById('prevDay').addEventListener('click', () => {
        const d = new Date(selectedDate);
        d.setDate(d.getDate() - 1);
        selectedDate = d.toISOString().slice(0, 10);
        renderDatePills(d);
        renderVisits();
        setTimeout(scrollToActiveDate, 100);
    });
    document.getElementById('nextDay').addEventListener('click', () => {
        const d = new Date(selectedDate);
        d.setDate(d.getDate() + 1);
        selectedDate = d.toISOString().slice(0, 10);
        renderDatePills(d);
        renderVisits();
        setTimeout(scrollToActiveDate, 100);
    });
    document.getElementById('todayBtn').addEventListener('click', () => {
        selectedDate = new Date().toISOString().slice(0, 10);
        renderDatePills(new Date());
        renderVisits();
        setTimeout(scrollToActiveDate, 100);
    });

    document.getElementById('refreshBtn').addEventListener('click', () => renderVisits());

    document.getElementById('searchVisits').addEventListener('input', async e => {
        const term = e.target.value.toLowerCase();
        const userSpecialId = await getUserSpecialId();
        if (!userSpecialId) return;
        const allVisits = await getVisitsFromDB(userSpecialId);
        const filtered = allVisits.filter(v => v.date === selectedDate && v.name.toLowerCase().includes(term));
        renderVisitsFiltered(filtered);
        renderTimelineAndAlerts(filtered);
    });

    document.getElementById('darkModeBtn').addEventListener('click', () => document.body.classList.toggle('dark-mode'));

    window.addEventListener('offline', () => document.getElementById('offlineStatus').style.display = 'inline-block');
    window.addEventListener('online', () => {
        document.getElementById('offlineStatus').style.display = 'none';
        const queue = JSON.parse(localStorage.getItem('offlineQueue') || '[]');
        queue.forEach(v => console.log('Syncing visit:', v));
        localStorage.removeItem('offlineQueue');
    });

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
</script>


<?php include_once 'footer.php'; ?>