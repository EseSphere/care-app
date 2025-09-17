<?php include_once 'header.php'; ?>

<link rel="stylesheet" href="./css/style1.css">

<div id="overlay"></div>

<div class="topbar mb-3 p-2">
    <div class="d-flex align-items-center justify-content-between mb-2">
        <button class="menu-btn" id="menuBtn"><i class="bi bi-list"></i></button>
        <h4 class="mb-0">Dashboard</h4>
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
    <!-- Progress Summary -->
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
                    <li>Pending tasks: <strong id="pendingQr">0</strong></li>
                    <li>Total carers: <strong id="totalCarers">0</strong></li>
                    <li>Connected: <span id="connStatus" class="badge bg-success">Online</span></li>
                    <li id="offlineStatus" style="display:none; color:red;">Offline</li>
                </ul>
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

<?php include_once 'footer.php'; ?>

<script>
    const sampleVisits = [{
            id: 1,
            name: 'Mrs. Edith Clarke',
            service: 'Personal Care',
            date: '2025-09-17',
            time_in: '08:30',
            time_out: '09:15',
            carers: 1,
            img: 'https://randomuser.me/api/portraits/women/65.jpg',
            status: 'scheduled'
        },
        {
            id: 2,
            name: 'Mr. John Baker',
            service: 'Medication',
            date: '2025-09-17',
            time_in: '10:00',
            time_out: '10:30',
            carers: 2,
            img: 'https://randomuser.me/api/portraits/men/32.jpg',
            status: 'in-progress'
        },
        {
            id: 3,
            name: 'Ms. Anna Wells',
            service: 'Wound Dressing',
            date: '2025-09-17',
            time_in: '11:00',
            time_out: '12:00',
            carers: 1,
            img: 'https://randomuser.me/api/portraits/women/44.jpg',
            status: 'scheduled'
        },
        {
            id: 4,
            name: 'Mr. Tom Rivers',
            service: 'Companionship',
            date: '2025-09-17',
            time_in: '13:30',
            time_out: '14:00',
            carers: 1,
            img: 'https://randomuser.me/api/portraits/men/58.jpg',
            status: 'completed'
        },
        {
            id: 5,
            name: 'Mrs. Helen Fry',
            service: 'Meal Assistance',
            date: '2025-09-17',
            time_in: '16:00',
            time_out: '17:00',
            carers: 1,
            img: 'https://randomuser.me/api/portraits/women/50.jpg',
            status: 'scheduled'
        }
    ];

    function formatDate(d) {
        return d.toISOString().slice(0, 10);
    }

    function addDays(d, n) {
        const x = new Date(d);
        x.setDate(x.getDate() + n);
        return x;
    }

    function minutesBetween(a, b) {
        const [ah, am] = a.split(':').map(Number);
        const [bh, bm] = b.split(':').map(Number);
        return (bh * 60 + bm) - (ah * 60 + am);
    }

    const dateStrip = document.getElementById('dateStrip');
    const today = new Date();
    let selectedDate = formatDate(today);

    function renderDatePills(centerDate) {
        dateStrip.innerHTML = '';
        for (let i = -7; i <= 7; i++) {
            const d = addDays(centerDate, i);
            const iso = formatDate(d);
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

    const visitsContainer = document.getElementById('visitsContainer');

    function renderVisitsFiltered(visits) {
        visitsContainer.innerHTML = '';
        if (visits.length === 0) {
            visitsContainer.innerHTML = '<div class="text-center small-muted p-5">No visits found</div>';
            updateQuickStats([]);
            updateProgress([]);
            return;
        }
        const tpl = document.getElementById('visitTpl');
        const now = new Date();
        visits.forEach(v => {
            const node = document.importNode(tpl.content, true);
            node.querySelector('.avatar img').src = v.img;
            node.querySelector('.avatar img').alt = v.name;
            const nameEl = node.querySelector('.name');
            nameEl.textContent = v.name;
            nameEl.style.cursor = 'pointer';
            nameEl.addEventListener('click', () => {
                window.location.href = `client-details.php?id=${v.id}`;
            });
            node.querySelector('.service').textContent = v.service;
            node.querySelector('.times').textContent = `${v.time_in} - ${v.time_out}`;
            const carersDiv = node.querySelector('.carers-icons');
            carersDiv.innerHTML = '';
            for (let i = 0; i < v.carers; i++) carersDiv.innerHTML += '<span>👤</span>';
            const badge = node.querySelector('.status');
            badge.textContent = v.status.replace('-', ' ');
            badge.className = 'badge badge-status ms-1';
            if (v.status === 'scheduled') badge.classList.add('bg-info', 'text-dark');
            if (v.status === 'in-progress') badge.classList.add('bg-warning', 'text-dark');
            if (v.status === 'completed') badge.classList.add('bg-success');
            badge.addEventListener('click', () => {
                if (v.status !== 'completed') {
                    v.status = 'completed';
                    renderVisits();
                }
            });
            const visitStart = new Date(`${v.date}T${v.time_in}:00`);
            if (visitStart > now && visitStart - now <= 60 * 60 * 1000) {
                node.querySelector('.card').style.border = '2px solid var(--accent2)';
            }
            visitsContainer.appendChild(node);
        });
        updateQuickStats(visits);
        updateProgress(visits);
    }

    function renderVisits() {
        renderVisitsFiltered(sampleVisits.filter(v => v.date === selectedDate));
    }

    function updateQuickStats(visits) {
        const totalCarers = visits.reduce((sum, v) => sum + v.carers, 0);
        document.getElementById('totalCarers').textContent = totalCarers;
        document.getElementById('countCalls').textContent = visits.length;
        document.getElementById('pendingQr').textContent = visits.filter(v => v.status !== 'completed').length;
    }

    function updateProgress(visits) {
        if (visits.length === 0) {
            document.getElementById('progressBar').style.width = '0%';
            return;
        }
        const completed = visits.filter(v => v.status === 'completed').length;
        const percent = Math.round((completed / visits.length) * 100);
        document.getElementById('progressBar').style.width = percent + '%';
    }

    function updateClock() {
        const now = new Date();
        document.getElementById('today-clock').textContent = now.toLocaleTimeString(undefined, {
            hour: '2-digit',
            minute: '2-digit'
        });
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Initial render
    renderDatePills(today);
    renderVisits();
    setTimeout(scrollToActiveDate, 100);

    // Navigation buttons
    document.getElementById('prevDay').addEventListener('click', () => {
        const d = new Date(selectedDate);
        d.setDate(d.getDate() - 1);
        selectedDate = formatDate(d);
        renderDatePills(d);
        renderVisits();
        setTimeout(scrollToActiveDate, 100);
    });
    document.getElementById('nextDay').addEventListener('click', () => {
        const d = new Date(selectedDate);
        d.setDate(d.getDate() + 1);
        selectedDate = formatDate(d);
        renderDatePills(d);
        renderVisits();
        setTimeout(scrollToActiveDate, 100);
    });
    document.getElementById('todayBtn').addEventListener('click', () => {
        selectedDate = formatDate(new Date());
        renderDatePills(new Date());
        renderVisits();
        setTimeout(scrollToActiveDate, 100);
    });

    // Refresh
    document.getElementById('refreshBtn').addEventListener('click', () => {
        location.reload();
    });

    // Search
    document.getElementById('searchVisits').addEventListener('input', (e) => {
        const term = e.target.value.toLowerCase();
        const filtered = sampleVisits.filter(v => v.date === selectedDate && v.name.toLowerCase().includes(term));
        renderVisitsFiltered(filtered);
    });

    // Dark mode
    document.getElementById('darkModeBtn').addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
    });

    // Offline status
    window.addEventListener('offline', () => {
        document.getElementById('offlineStatus').style.display = 'inline-block';
    });
    window.addEventListener('online', () => {
        document.getElementById('offlineStatus').style.display = 'none';
    });

    // Slide-in menu
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

    // Notifications / Reminders
    function checkReminders() {
        const now = new Date();
        sampleVisits.forEach(v => {
            if (v.date === selectedDate) {
                const visitStart = new Date(`${v.date}T${v.time_in}:00`);
                const diff = (visitStart - now) / 60000;
                if (diff > 0 && diff <= 15 && !v.notified) {
                    alert(`Upcoming visit for ${v.name} at ${v.time_in}`);
                    v.notified = true;
                }
            }
        });
    }
    setInterval(checkReminders, 60000);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>