<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Check Out — Professional</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --accent: #4A90E2;
            --accent2: #50C9C3;
            --muted: #6c757d;
            --bg1: #f7faff;
            --bg2: #eef5ff;
        }

        body {
            background: linear-gradient(180deg, var(--bg1) 0%, var(--bg2) 100%);
            font-family: Inter, sans-serif;
            color: #333;
            overflow-x: hidden;
            padding-bottom: 80px;
        }

        body.dark-mode {
            background: #1e1e2f;
            color: #ccc;
        }

        body.dark-mode .card {
            background: #2a2a3d;
            color: #ccc;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 20;
            background: linear-gradient(90deg, var(--accent), var(--accent2));
            padding: 12px 15px;
            border-radius: 12px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .topbar h4 {
            margin: 0;
            font-weight: 600;
            text-align: center;
            flex: 1;
        }

        .menu-btn {
            background: none;
            border: none;
            color: white;
            font-size: 1.4rem;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 70px;
            background: #fff;
            display: flex;
            justify-content: space-around;
            align-items: center;
            box-shadow: 0 -3px 10px rgba(0, 0, 0, .1);
            z-index: 30;
            border-top: 1px solid #eee;
        }

        .footer button {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: #e0e0e0;
            font-size: 1.5rem;
            color: #4A90E2;
            transition: background .2s, color .2s;
        }

        .footer button:hover {
            background: #4A90E2;
            color: white;
        }

        #sideNav {
            position: fixed;
            top: 0;
            left: -280px;
            width: 280px;
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

        .main-wrapper {
            padding: 20px;
        }

        .card {
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(30, 40, 60, .06);
            margin-bottom: 15px;
        }

        .note-thumbnail {
            max-width: 80px;
            max-height: 80px;
            object-fit: cover;
            margin-top: 5px;
            border-radius: 8px;
        }
    </style>
</head>

<body>

    <!-- SideNav -->
    <div id="sideNav">
        <div class="user-info text-center mb-4">
            <img src="https://randomuser.me/api/portraits/men/32.jpg" class="rounded-circle mb-2" width="80" alt="Profile">
            <h5>Samson Gift</h5>
            <small>samsonosaretin@yahoo.com</small>
            <p>07448222483</p>
        </div>
        <ul class="list-unstyled">
            <li class="mb-2"><a href="#" class="d-block p-2"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
            <li class="mb-2"><a href="#" class="d-block p-2"><i class="bi bi-calendar-event me-2"></i>Visits</a></li>
            <li class="mb-2"><a href="#" class="d-block p-2"><i class="bi bi-graph-up me-2"></i>Reports</a></li>
            <li class="mb-2"><a href="#" class="d-block p-2"><i class="bi bi-gear me-2"></i>Settings</a></li>
            <li class="mt-4"><button class="btn btn-outline-danger w-100"><i class="bi bi-box-arrow-right"></i> Logout</button></li>
        </ul>
    </div>
    <div id="overlay"></div>

    <!-- Topbar -->
    <div class="topbar">
        <button class="menu-btn" id="menuBtn"><i class="bi bi-list"></i></button>
        <h4>Check Out</h4>
        <div class="d-flex align-items-center gap-3">
            <span id="topClock"></span>
            <i class="bi bi-bell-fill fs-5" title="Notifications"></i>
            <button class="btn btn-light" id="darkModeBtn"><i class="bi bi-moon"></i></button>
        </div>
    </div>

    <div class="main-wrapper container">

        <!-- Client Profile Card -->
        <div class="col-md-12 mb-3">
            <div class="card p-3 d-flex flex-row align-items-center justify-content-between">
                <div style="flex:1;">
                    <h4 id="clientName">Duru Artrick</h4>
                    <p id="clientLocation" class="text-muted mb-1">Bay Area, San Francisco, CA</p>
                    <p><strong>Check-In:</strong> <span id="checkInTime">09:00 AM</span></p>
                </div>
                <a href="home.php" class="btn btn-success text-decoration-none" id="checkoutBtn">
                    Check-Out
                </a>
            </div>
        </div>

        <!-- Summary Section -->
        <div class="card p-3 mb-3">
            <h5>Summary</h5>
            <hr>
            <p>All observations and notes have been successfully submitted.</p>
        </div>

        <!-- Assigned Carers -->
        <div class="col-md-12 mt-3">
            <div class="card p-3">
                <h5>Assigned Carers</h5>
                <div class="d-flex flex-wrap gap-3" id="carersContainer"></div>
            </div>
        </div>

        <!-- Recent Notes -->
        <div class="col-md-12 mt-3">
            <div class="card p-3">
                <h5>Recent Notes / Observations</h5>
                <div id="notesContainer"></div>
            </div>
        </div>

    </div>

    <!-- Toast Notification -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100;">
        <div id="checkoutToast" class="toast align-items-center text-white bg-success border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">Check-Out Completed Successfully!</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <button title="Back"><i class="bi bi-arrow-left"></i></button>
        <button title="Home"><i class="bi bi-house"></i></button>
        <button title="Log"><i class="bi bi-journal-text"></i></button>
        <button title="User"><i class="bi bi-person"></i></button>
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

        // Assigned Carers
        const assignedCarers = [{
                name: 'Alice Johnson',
                role: 'Primary Carer',
                phone: '07440111222',
                img: 'https://randomuser.me/api/portraits/women/45.jpg'
            },
            {
                name: 'John Smith',
                role: 'Backup Carer',
                phone: '07440111333',
                img: 'https://randomuser.me/api/portraits/men/56.jpg'
            }
        ];
        const carersContainer = document.getElementById('carersContainer');
        assignedCarers.forEach(c => {
            const div = document.createElement('div');
            div.className = 'd-flex flex-column align-items-center text-center p-2';
            div.style.width = '120px';
            div.innerHTML = `
                <div style="width:80px;height:80px;border-radius:50%;overflow:hidden;margin-bottom:5px;">
                    <img src="${c.img}" style="width:100%;height:100%;object-fit:cover;" alt="${c.name}">
                </div>
                <strong style="font-size:.9rem;">${c.name}</strong>
                <small class="text-muted">${c.role}</small>
                <a href="tel:${c.phone}" class="btn btn-sm btn-outline-success mt-1">Call</a>
            `;
            carersContainer.appendChild(div);
        });

        // Recent Notes
        const recentNotes = [{
            author: 'You',
            time: new Date(),
            text: '[Medication] Observation note example',
            photo: ''
        }];
        const notesContainer = document.getElementById('notesContainer');

        function renderNotes() {
            notesContainer.innerHTML = '';
            recentNotes.forEach(n => {
                const noteDiv = document.createElement('div');
                noteDiv.className = 'mb-2 p-2';
                noteDiv.style.borderBottom = '1px solid #eee';
                const date = new Date(n.time);
                const formatted = date.toLocaleDateString(undefined, {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                });
                let photoHTML = '';
                if (n.photo) photoHTML = `<img src="${n.photo}" class="note-thumbnail">`;
                noteDiv.innerHTML = `
                    <div class="d-flex justify-content-between">
                        <strong>${n.author}</strong>
                        <small class="text-muted">${formatted}</small>
                    </div>
                    <div>${n.text}</div>
                    ${photoHTML}
                `;
                notesContainer.appendChild(noteDiv);
            });
        }
        renderNotes();

        // Confirm Check-Out
        const confirmBtn = document.getElementById('confirmCheckoutBtn');
        confirmBtn.addEventListener('click', () => {
            const finalNote = document.getElementById('finalNote').value.trim();
            if (finalNote) {
                recentNotes.unshift({
                    author: 'You',
                    time: new Date(),
                    text: finalNote,
                    photo: ''
                });
                renderNotes();
            }

            const modalEl = document.getElementById('confirmCheckoutModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();

            const toastEl = document.getElementById('checkoutToast');
            const toast = new bootstrap.Toast(toastEl);
            toast.show();

            setTimeout(() => {
                window.location.href = 'home.php';
            }, 2000);
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>