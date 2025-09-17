<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Carer Daily Calls — Professional</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --accent: #4A90E2;
            --accent2: #50C9C3;
            --card-bg: #ffffff;
            --muted: #6c757d;
            --bg1: #f7faff;
            --bg2: #eef5ff;
        }

        body {
            background: linear-gradient(180deg, var(--bg1) 0%, var(--bg2) 100%);
            font-family: Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
            color: #333;
            overflow-x: hidden;
            padding-bottom: 70px;
            /* footer space */
        }

        .date-strip {
            overflow-x: auto;
            white-space: nowrap;
            padding: 0.5rem 0;
            background: linear-gradient(90deg, rgba(74, 144, 226, 0.08), rgba(80, 201, 195, 0.08));
            border-radius: 12px;
        }

        .date-pill {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-width: 88px;
            padding: 10px;
            border-radius: 10px;
            margin: 6px;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all .2s ease;
            background: #fff;
        }

        .date-pill:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 18px rgba(43, 140, 255, .08);
        }

        .date-pill.active {
            background: var(--accent);
            color: #fff;
            box-shadow: 0 10px 30px rgba(43, 140, 255, .14);
        }

        .visits-list .card {
            border-radius: 16px;
            border: 0;
            box-shadow: 0 6px 20px rgba(30, 40, 60, .06);
            overflow: hidden;
            background: #fff;
            transition: transform .2s;
        }

        .visits-list .card:hover {
            transform: translateY(-3px);
        }

        .visit-row {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .avatar {
            flex: 0 0 80px;
            height: 80px;
            border-radius: 12px;
            overflow: hidden;
            background: #eee;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .visit-details {
            flex: 1;
        }

        .small-muted {
            color: var(--muted);
            font-size: .9rem;
        }

        .badge-status {
            font-weight: 600;
            border-radius: 8px;
            padding: .25rem .5rem;
        }

        @media (max-width:576px) {
            .avatar {
                flex: 0 0 64px;
                height: 64px
            }

            .date-pill {
                min-width: 72px;
                padding: 8px
            }
        }

        .fade-in-up {
            animation: fadeUp .6s ease both;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        /* Topbar gradient */
        .topbar {
            position: sticky;
            top: 0;
            z-index: 20;
            background: linear-gradient(90deg, var(--accent), var(--accent2));
            padding: 12px 0;
            border-radius: 12px;
            color: white;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        /* Carers icons wrapper */
        .carers-icons span {
            font-size: 1rem;
            margin-left: 2px;
        }

        /* Fixed Footer */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 60px;
            background: #fff;
            display: flex;
            justify-content: space-around;
            align-items: center;
            box-shadow: 0 -3px 10px rgba(0, 0, 0, 0.1);
            z-index: 30;
            border-top: 1px solid #eee;
        }

        .footer button {
            background: none;
            border: none;
            font-size: 1.25rem;
            color: #4A90E2;
            transition: color .2s;
        }

        .footer button:hover {
            color: #50C9C3;
        }

        /* Slide-in Navbar */
        #sideNav {
            position: fixed;
            top: 0;
            left: -260px;
            width: 260px;
            height: 100%;
            background: #fff;
            box-shadow: 2px 0 12px rgba(0, 0, 0, .2);
            z-index: 50;
            transition: left .3s ease;
            padding: 1.5rem;
        }

        #sideNav.open {
            left: 0;
        }

        #sideNav h5 {
            color: var(--accent);
            margin-bottom: 1rem;
            font-weight: 600;
        }

        #sideNav ul li a {
            text-decoration: none;
            display: block;
            padding: 10px 0;
            color: #333;
            font-weight: 500;
            transition: color .2s;
        }

        #sideNav ul li a:hover {
            color: var(--accent2);
        }

        #overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, .4);
            z-index: 40;
            display: none;
        }

        #overlay.show {
            display: block;
        }

        .menu-btn {
            font-size: 1.4rem;
            cursor: pointer;
            background: none;
            border: none;
            color: white;
        }
    </style>
</head>

<body>

    <!-- Slide-in nav -->
    <div id="sideNav">
        <h5>Menu</h5>
        <ul class="list-unstyled">
            <li><a href="#">Dashboard</a></li>
            <li><a href="#">Visits</a></li>
            <li><a href="#">Reports</a></li>
            <li><a href="#">Settings</a></li>
        </ul>
    </div>
    <div id="overlay"></div>

    <div class="container py-3">
        <div class="topbar mb-3 p-2">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <button class="menu-btn" id="menuBtn">☰</button>
                <h4 class="mb-0">Dashboard</h4>
                <div class="chip"><span id="today-clock">--:--</span></div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-light" id="prevDay">‹</button>
                    <button class="btn btn-sm btn-light" id="nextDay">›</button>
                </div>
                <div class="date-strip w-100" id="dateStrip" aria-label="pick a date"></div>
                <div class="text-end">
                    <div class="small-muted">Total care hours</div>
                    <div class="hour-total" id="totalHours">0h 0m</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="card p-3 visits-list" id="visitsContainer"></div>
            </div>
            <div class="col-12 col-lg-4 mt-3 mt-lg-0">
                <div class="card p-3 fade-in-up">
                    <h6>Quick stats</h6>
                    <ul class="list-unstyled small-muted mb-0">
                        <li>Calls today: <strong id="countCalls">0</strong></li>
                        <li>Pending tasks: <strong id="pendingQr">0</strong></li>
                        <li>Connected: <span id="connStatus" class="badge bg-success">Online</span></li>
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

    <!-- Fixed Footer -->
    <div class="footer">
        <button title="Back">←</button>
        <button title="Home">🏠</button>
        <button title="Log">📋</button>
        <button title="User">👤</button>
    </div>

    <script>
        // Sample visits
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
                });
                dateStrip.appendChild(pill);
            }
        }

        const visitsContainer = document.getElementById('visitsContainer');

        function renderVisits() {
            visitsContainer.innerHTML = '';
            const tpl = document.getElementById('visitTpl');
            const visits = sampleVisits.filter(v => v.date === selectedDate);
            document.getElementById('countCalls').textContent = visits.length;
            let totalMins = 0;
            visits.forEach(v => totalMins += Math.max(0, minutesBetween(v.time_in, v.time_out)));
            const h = Math.floor(totalMins / 60),
                m = totalMins % 60;
            document.getElementById('totalHours').textContent = `${h}h ${m}m`;
            document.getElementById('pendingQr').textContent = visits.filter(v => v.status !== 'completed').length;
            if (visits.length === 0) {
                visitsContainer.innerHTML = '<div class="text-center small-muted p-5">No visits for this date</div>';
                return;
            }
            visits.forEach(v => {
                const node = document.importNode(tpl.content, true);
                node.querySelector('.avatar img').src = v.img;
                node.querySelector('.avatar img').alt = v.name;
                node.querySelector('.name').textContent = v.name;
                node.querySelector('.service').textContent = v.service;
                node.querySelector('.times').textContent = `${v.time_in} - ${v.time_out}`;
                // Carers icons
                const carersDiv = node.querySelector('.carers-icons');
                carersDiv.innerHTML = '';
                for (let i = 0; i < v.carers; i++) {
                    carersDiv.innerHTML += `<span>👤</span>`;
                }
                const badge = node.querySelector('.status');
                badge.textContent = v.status.replace('-', ' ');
                badge.className = 'badge badge-status ms-1';
                if (v.status === 'scheduled') badge.classList.add('bg-info', 'text-dark');
                if (v.status === 'in-progress') badge.classList.add('bg-warning', 'text-dark');
                if (v.status === 'completed') badge.classList.add('bg-success');
                visitsContainer.appendChild(node);
            });
        }

        renderDatePills(today);
        renderVisits();
        document.getElementById('prevDay').addEventListener('click', () => {
            const d = new Date(selectedDate);
            d.setDate(d.getDate() - 1);
            selectedDate = formatDate(d);
            renderDatePills(d);
            renderVisits();
        });
        document.getElementById('nextDay').addEventListener('click', () => {
            const d = new Date(selectedDate);
            d.setDate(d.getDate() + 1);
            selectedDate = formatDate(d);
            renderDatePills(d);
            renderVisits();
        });

        function updateClock() {
            const now = new Date();
            document.getElementById('today-clock').textContent = now.toLocaleTimeString(undefined, {
                hour: '2-digit',
                minute: '2-digit'
            });
        }
        setInterval(updateClock, 1000);
        updateClock();

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
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>