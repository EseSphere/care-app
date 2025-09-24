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
    <div class="d-flex align-items-center gap-3">
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
                    <li>Total carers: <strong id="totalCarers">0</strong></li>
                    <li>Connected: <span id="connStatus" class="badge bg-success">Online</span></li>
                    <li id="offlineStatus" style="display:none; color:red;">Offline</li>
                    <li>Run name: <strong id="runName">N/A</strong></li>
                </ul>
            </div>
            <div class="card p-3 mt-3">
                <h6>Today's Route</h6>
                <div id="map" style="height:200px;"></div>
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
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    AOS.init();

    // --- State ---
    let allVisits = [];
    let selectedDate = new Date().toISOString().slice(0, 10);
    const dateStrip = document.getElementById('dateStrip');
    const visitsContainer = document.getElementById('visitsContainer');
    let map = null;
    let countdownInterval = null;

    // --- IndexedDB ---
    function openDB() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open("geosoft");
            request.onsuccess = (e) => resolve(e.target.result);
            request.onerror = (e) => reject("DB error: " + (e.target.errorCode || e.target.error));
            request.onblocked = () => console.warn('indexedDB open blocked');
        });
    }

    function parseDateField(val) {
        if (!val) return '';
        try {
            if (typeof val === 'string') {
                const part = val.trim().split(' ')[0];
                if (/^\d{4}-\d{2}-\d{2}$/.test(part)) return part;
                const dt = new Date(val);
                if (!isNaN(dt)) return dt.toISOString().slice(0, 10);
                return part.slice(0, 10);
            }
            if (val instanceof Date) return val.toISOString().slice(0, 10);
        } catch (e) {}
        return '';
    }

    function formatTimeField(val) {
        if (!val) return '';
        try {
            if (typeof val === 'string') {
                const t = val.trim();
                if (/^\d{2}:\d{2}(:\d{2})?$/.test(t)) return t.slice(0, 5);
                const dt = new Date(val);
                if (!isNaN(dt)) return dt.toTimeString().slice(0, 5);
                return t.slice(0, 5);
            }
        } catch (e) {}
        return '';
    }

    async function loadVisitsFromDB() {
        try {
            const db = await openDB();
            if (!db.objectStoreNames.contains('tbl_schedule_calls')) return [];

            return new Promise((resolve, reject) => {
                const tx = db.transaction('tbl_schedule_calls', 'readonly');
                const store = tx.objectStore('tbl_schedule_calls');
                const req = store.getAll();
                req.onsuccess = (e) => {
                    const rows = e.target.result || [];
                    const visits = rows.map(r => ({
                        userId: r.userId,
                        name: r.client_name || 'Unknown',
                        service: r.client_area || '',
                        date: parseDateField(r.Clientshift_Date),
                        time_in: formatTimeField(r.dateTime_in),
                        time_out: formatTimeField(r.dateTime_out),
                        carers: Number(r.col_required_carers) || 1,
                        img: './images/profile.jpg',
                        status: (r.call_status || 'scheduled').toLowerCase(),
                        run_name: r.col_run_name || 'N/A',
                        _raw: r
                    }));
                    resolve(visits);
                };
                req.onerror = (e) => reject('Read error: ' + (e.target.error || e.target.errorCode));
            });
        } catch (err) {
            console.error('loadVisitsFromDB error', err);
            return [];
        }
    }

    async function updateVisitStatusInDB(userId, newStatus) {
        try {
            const db = await openDB();
            if (!db.objectStoreNames.contains('tbl_schedule_calls')) throw new Error('Store not present');
            return new Promise((resolve, reject) => {
                const tx = db.transaction('tbl_schedule_calls', 'readwrite');
                const store = tx.objectStore('tbl_schedule_calls');
                const getReq = store.get(userId);
                getReq.onsuccess = (e) => {
                    const rec = e.target.result;
                    if (!rec) return reject('Record not found');
                    rec.call_status = newStatus;
                    const putReq = store.put(rec);
                    putReq.onsuccess = () => resolve(true);
                    putReq.onerror = (err) => reject(err.target?.error || 'put failed');
                };
                getReq.onerror = (err) => reject(err.target?.error || 'get failed');
            });
        } catch (err) {
            console.error('updateVisitStatusInDB', err);
            throw err;
        }
    }

    // --- UI Helpers ---
    function addDays(d, n) {
        const x = new Date(d);
        x.setDate(x.getDate() + n);
        return x;
    }

    function renderDatePills(centerDate) {
        dateStrip.innerHTML = '';
        const todayIso = new Date().toISOString().slice(0, 10);

        for (let i = -7; i <= 7; i++) {
            const d = addDays(centerDate, i);
            const iso = d.toISOString().slice(0, 10);
            const pill = document.createElement('div');
            pill.className = 'date-pill';
            pill.dataset.date = iso;
            pill.innerHTML = `<div style="font-weight:600">${d.toLocaleDateString(undefined,{weekday:'short'})}</div><div style="font-size:.85rem">${d.getDate()} ${d.toLocaleString(undefined,{month:'short'})}</div>`;
            if (iso === selectedDate) pill.classList.add('active');
            if (iso === todayIso) pill.style.border = '2px solid var(--accent1)';
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
        const container = dateStrip;
        if (activePill && container) {
            const containerWidth = container.offsetWidth;
            const pillOffset = activePill.offsetLeft + activePill.offsetWidth / 2;
            const scrollPos = pillOffset - containerWidth / 2;
            container.scrollTo({
                left: scrollPos,
                behavior: 'smooth'
            });
        }
    }

    function updateQuickStats(visits) {
        let maxCarers = 0;
        visits.forEach(v => {
            const carers = Number(v.carers || 1);
            if (carers > 1 && carers > maxCarers) maxCarers = carers;
        });
        document.getElementById('totalCarers').textContent = maxCarers;
        document.getElementById('countCalls').textContent = visits.length;
        document.getElementById('runName').textContent = visits[0]?.run_name || "N/A";
    }

    function updateProgress(visits) {
        if (!visits.length) {
            document.getElementById('progressBar').style.width = '0%';
            return;
        }
        const completed = visits.filter(v => v.status === 'completed').length;
        document.getElementById('progressBar').style.width = Math.round((completed / visits.length) * 100) + '%';
    }

    function updateTotalHours(visits) {
        let totalMinutes = 0;
        visits.forEach(v => {
            try {
                if (v.date && v.time_in && v.time_out) {
                    const s = new Date(`${v.date}T${v.time_in}:00`);
                    const e = new Date(`${v.date}T${v.time_out}:00`);
                    if (!isNaN(s) && !isNaN(e) && e > s) totalMinutes += Math.round((e - s) / 60000);
                }
            } catch (e) {}
        });
        const h = Math.floor(totalMinutes / 60);
        const m = totalMinutes % 60;
        document.getElementById('totalHours').textContent = `${h}h ${m}m`;
    }

    function renderMap(visits) {
        if (map) {
            map.remove();
            map = null;
        }
        if (!visits.length) return;

        map = L.map('map').setView([51.505, -0.09], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        visits.forEach(v => {
            const lat = 51.5 + Math.random() * 0.05;
            const lng = -0.09 + Math.random() * 0.05;
            L.marker([lat, lng]).addTo(map).bindPopup(`${v.name} (${v.service})`);
        });
    }

    function updateCountdowns() {
        const now = new Date();
        const cards = document.querySelectorAll('.visit-item .card');
        cards.forEach(card => {
            const timesEl = card.querySelector('.times');
            if (!card.dataset.date || !card.dataset.timeIn || !card.dataset.timeOut || !timesEl) return;

            const diff = new Date(`${card.dataset.date}T${card.dataset.timeIn}:00`) - now;
            if (diff > 0) {
                const min = Math.floor(diff / 60000);
                const sec = Math.floor((diff % 60000) / 1000);
                timesEl.textContent = `${card.dataset.timeIn} - ${card.dataset.timeOut} (Starts in ${min}m ${sec}s)`;
            } else {
                timesEl.textContent = `${card.dataset.timeIn} - ${card.dataset.timeOut}`;
            }
        });
    }


    function renderVisitsFiltered(visits) {
        visitsContainer.innerHTML = '';

        // Remove duplicates
        visits = visits.filter(
            (v, index, self) =>
            index === self.findIndex(
                t =>
                t.userId === v.userId &&
                t.date === v.date &&
                t.time_in === v.time_in
            )
        );

        if (!visits.length) {
            visitsContainer.innerHTML =
                '<div class="text-center small-muted p-5">No records in database yet. Please sync.</div>';
            updateQuickStats([]);
            updateProgress([]);
            updateTotalHours([]);
            renderMap([]);
            if (countdownInterval) {
                clearInterval(countdownInterval);
                countdownInterval = null;
            }
            return;
        }

        const tpl = document.getElementById('visitTpl');
        const now = new Date();

        for (let v of visits) {
            const node = document.importNode(tpl.content, true);

            node.querySelector('.avatar img').src = v.img;
            node.querySelector('.avatar img').alt = v.name;

            const nameEl = node.querySelector('.name');
            nameEl.textContent = v.name;
            nameEl.style.cursor = 'pointer';
            nameEl.addEventListener('click', () =>
                window.location.href = `care-plan?userId=${encodeURIComponent(v.userId)}`
            );

            node.querySelector('.service').textContent = v.service;

            const cardEl = node.querySelector('.card');
            cardEl.dataset.userId = v.userId;
            cardEl.dataset.date = v.date || '';
            cardEl.dataset.timeIn = v.time_in || '';
            cardEl.dataset.timeOut = v.time_out || '';

            const timesEl = node.querySelector('.times');
            timesEl.textContent = `${v.time_in || ''} - ${v.time_out || ''}`;

            const carersDiv = node.querySelector('.carers-icons');
            carersDiv.innerHTML = '';
            for (let j = 0; j < (Number(v.col_required_carers) || 0); j++) {
                carersDiv.innerHTML += '<span>👤</span>';
            }

            const badge = node.querySelector('.status');
            badge.textContent = (v.status || 'scheduled').replace('-', ' ');
            badge.className = 'badge badge-status ms-1';
            if (v.status === 'scheduled') badge.classList.add('bg-info', 'text-dark');
            if (v.status === 'in-progress') badge.classList.add('bg-warning', 'text-dark');
            if (v.status === 'completed') badge.classList.add('bg-success');

            badge.addEventListener('click', async () => {
                if ((v.status || '') === 'completed') return;

                v.status = 'completed';
                badge.classList.remove('bg-info', 'bg-warning');
                badge.classList.add('bg-success');
                badge.textContent = 'completed';

                updateProgress(visits);

                try {
                    await updateVisitStatusInDB(v.userId, 'completed');
                } catch (err) {
                    console.error('Failed to persist status update', err);
                    v.status = 'scheduled';
                    badge.classList.remove('bg-success');
                    badge.classList.add('bg-info', 'text-dark');
                    badge.textContent = 'scheduled';
                    updateProgress(visits);
                }
            });

            try {
                const visitStart = new Date(`${v.date}T${v.time_in}:00`);
                if (visitStart > now && visitStart - now <= 3600000)
                    cardEl.style.border = '2px solid var(--accent2)';
                if (visitStart < now && v.status !== 'completed')
                    cardEl.style.border = '2px solid red';
            } catch (e) {}

            const wrapper = document.createElement('div');
            wrapper.appendChild(node);
            visitsContainer.appendChild(wrapper.firstElementChild);
        }

        updateQuickStats(visits);
        updateProgress(visits);
        updateTotalHours(visits);
        renderMap(visits);

        if (!countdownInterval) {
            updateCountdowns();
            countdownInterval = setInterval(updateCountdowns, 1000);
        }
    }



    function renderVisits() {
        renderVisitsFiltered(allVisits.filter(v => v.date === selectedDate));
    }

    // Clock
    function updateClock() {
        document.getElementById('today-clock').textContent = new Date().toLocaleTimeString(undefined, {
            hour: '2-digit',
            minute: '2-digit'
        });
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Init
    async function init() {
        allVisits = await loadVisitsFromDB();

        // Set selectedDate to today
        const today = new Date();
        selectedDate = today.toISOString().slice(0, 10);

        renderDatePills(today);
        renderVisits();

        // Scroll the current date pill to center after render
        setTimeout(scrollToActiveDate, 200);
    }
    init();

    // Navigation buttons
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

    // Refresh
    document.getElementById('refreshBtn').addEventListener('click', async () => {
        // reload from DB and re-render
        allVisits = await loadVisitsFromDB();
        renderVisits();
    });

    // Search
    document.getElementById('searchVisits').addEventListener('input', (e) => {
        const term = e.target.value.toLowerCase();
        renderVisitsFiltered(allVisits.filter(v => v.date === selectedDate && v.name.toLowerCase().includes(term)));
    });

    // Dark mode
    document.getElementById('darkModeBtn').addEventListener('click', () => document.body.classList.toggle('dark-mode'));

    // Offline handling
    window.addEventListener('offline', () => document.getElementById('offlineStatus').style.display = 'inline-block');
    window.addEventListener('online', () => {
        document.getElementById('offlineStatus').style.display = 'none';
        const queue = JSON.parse(localStorage.getItem('offlineQueue') || '[]');
        queue.forEach(v => console.log('Syncing visit:', v));
        localStorage.removeItem('offlineQueue');
    });

    // Slide-in menu
    const menuBtn = document.getElementById('menuBtn');
    const sideNav = document.getElementById('sideNav');
    const overlay = document.getElementById('overlay');
    menuBtn.addEventListener('click', () => {
        if (sideNav) sideNav.classList.add('open');
        overlay.classList.add('show');
    });
    overlay.addEventListener('click', () => {
        if (sideNav) sideNav.classList.remove('open');
        overlay.classList.remove('show');
    });
</script>

<?php include_once 'footer.php'; ?>