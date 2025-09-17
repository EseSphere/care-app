<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Activity Report — Professional</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Reuse CSS from client-activities.php */
        :root {
            --accent: #4A90E2;
            --accent2: #50C9C3;
            --muted: #6c757d;
            --bg1: #f7faff;
            --bg2: #eef5ff;
            --task-color: #3498db;
            --med-color: #2ecc71;
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

        body.dark-mode .text-secondary {
            color: #aaa !important;
        }

        body.dark-mode #sideNav {
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

        #sideNav h5 {
            color: var(--accent);
            margin-bottom: 1rem;
            font-weight: 600;
        }

        #sideNav .user-info {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 2rem;
            text-align: center;
        }

        #sideNav .user-info img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 0.5rem;
        }

        #sideNav .user-info .name {
            font-weight: 600;
            margin-bottom: 0;
        }

        #sideNav .user-info .email,
        .phone {
            font-size: .85rem;
            color: var(--muted);
            margin-bottom: 0.25rem;
        }

        #sideNav .logout-btn {
            margin-top: 0.5rem;
            width: 100%;
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

        .main-wrapper {
            padding: 20px;
        }

        .card {
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(30, 40, 60, .06);
            margin-bottom: 15px;
        }

        .status-updated {
            color: white;
            background: #2ecc71;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: .8rem;
        }

        .status-not-updated {
            color: white;
            background: #e74c3c;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: .8rem;
        }

        .care-item {
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 6px;
            transition: 0.2s;
        }

        .care-item:hover {
            opacity: 0.9;
        }

        .care-icon {
            margin-right: 10px;
            font-size: 1.2rem;
        }

        .prn-btn {
            margin-left: 10px;
        }

        #allergiesBtn,
        #dnacprBtn {
            font-weight: 800;
            color: red;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <!-- SideNav -->
    <div id="sideNav">
        <div class="user-info">
            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Profile">
            <p class="name">Samson Gift</p>
            <p class="email">samsonosaretin@yahoo.com</p>
            <p class="phone">07448222483</p>
            <button class="btn btn-outline-danger logout-btn"><i class="bi bi-box-arrow-right"></i> Logout</button>
        </div>
        <ul class="list-unstyled">
            <li><a href="#"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
            <li><a href="#"><i class="bi bi-calendar-event me-2"></i> Visits</a></li>
            <li><a href="#"><i class="bi bi-graph-up me-2"></i> Reports</a></li>
            <li><a href="#"><i class="bi bi-gear me-2"></i> Settings</a></li>
        </ul>
    </div>
    <div id="overlay"></div>

    <!-- Topbar -->
    <div class="topbar">
        <button class="menu-btn" id="menuBtn"><i class="bi bi-list"></i></button>
        <h4>Activity Report</h4>
        <div class="d-flex align-items-center gap-3">
            <span id="topClock"></span>
            <i class="bi bi-bell-fill fs-5" title="Notifications"></i>
            <button class="btn btn-light" id="darkModeBtn"><i class="bi bi-moon"></i></button>
        </div>
    </div>

    <div class="main-wrapper container">

        <!-- Client Profile Card with PRN -->
        <div class="col-md-12 mb-3">
            <div class="card p-3 d-flex flex-row align-items-center justify-content-between">
                <div style="flex:1;">
                    <h4 id="clientName">Duru Artrick</h4>
                    <p id="clientLocation" class="text-muted mb-1">Bay Area, San Francisco, CA</p>
                    <div class="d-flex gap-2">
                        <a href="#" id="dnacprBtn">DNACPR</a>
                        <a href="#" id="allergiesBtn">ALLERGIES</a>
                    </div>
                </div>
                <button class="btn btn-warning prn-btn" data-bs-toggle="modal" data-bs-target="#prnModal"><i class="bi bi-bandaid"></i> PRN</button>
            </div>
        </div>

        <!-- Select Care Activity or Medication -->
        <div class="card p-3 mb-3">
            <h5>Submit Activity Report</h5>
            <form id="activityReportForm">
                <div class="mb-3">
                    <label for="activitySelect" class="form-label">Select Task / Medication</label>
                    <select class="form-select" id="activitySelect" required>
                        <option value="">-- Select Activity --</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="statusSelect" class="form-label">Status</label>
                    <select class="form-select" id="statusSelect" required>
                        <option value="Completed">Completed</option>
                        <option value="Not Completed">Not Completed</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="reportText" class="form-label">Report / Notes</label>
                    <textarea class="form-control" id="reportText" rows="3" placeholder="Enter details here" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit Report</button>
            </form>
        </div>

        <!-- Assigned Carers Panel -->
        <div class="col-md-12 mt-3">
            <div class="card p-3">
                <h5>Assigned Carers</h5>
                <div class="d-flex flex-wrap gap-3" id="carersContainer"></div>
            </div>
        </div>

        <!-- Previous Reports -->
        <div class="col-md-12 mt-3">
            <div class="card p-3">
                <h5>Previous Reports</h5>
                <div id="previousReportsContainer"></div>
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

    <!-- PRN Modal -->
    <div class="modal fade" id="prnModal" tabindex="-1" aria-labelledby="prnModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">PRN Action</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">Log PRN medication or task here.</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Log PRN</button>
                </div>
            </div>
        </div>
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

        // Sample Care Activities
        const careActivities = [{
            type: 'task',
            title: 'Check Blood Pressure'
        }, {
            type: 'task',
            title: 'Assist with Bath'
        }, {
            type: 'medication',
            title: 'Paracetamol 500mg'
        }, {
            type: 'medication',
            title: 'Insulin 10 units'
        }];
        const activitySelect = document.getElementById('activitySelect');
        careActivities.forEach(c => {
            const opt = document.createElement('option');
            opt.value = c.title;
            opt.textContent = c.title;
            activitySelect.appendChild(opt);
        });

        // Assigned Carers
        const assignedCarers = [{
            name: 'Alice Johnson',
            role: 'Primary Carer',
            phone: '07440111222',
            img: 'https://randomuser.me/api/portraits/women/45.jpg'
        }, {
            name: 'John Smith',
            role: 'Backup Carer',
            phone: '07440111333',
            img: 'https://randomuser.me/api/portraits/men/56.jpg'
        }];
        const carersContainer = document.getElementById('carersContainer');
        assignedCarers.forEach(c => {
            const div = document.createElement('div');
            div.className = 'd-flex flex-column align-items-center text-center p-2';
            div.style.width = '120px';
            div.innerHTML = `<div style="width:80px;height:80px;border-radius:50%;overflow:hidden;margin-bottom:5px;"><img src="${c.img}" style="width:100%;height:100%;object-fit:cover;" alt="${c.name}"></div><strong style="font-size:.9rem;">${c.name}</strong><small class="text-muted">${c.role}</small><a href="tel:${c.phone}" class="btn btn-sm btn-outline-success mt-1">Call</a>`;
            carersContainer.appendChild(div);
        });

        // Submit Report Form
        const previousReportsContainer = document.getElementById('previousReportsContainer');
        document.getElementById('activityReportForm').addEventListener('submit', (e) => {
            e.preventDefault();
            const activity = activitySelect.value;
            const status = document.getElementById('statusSelect').value;
            const notes = document.getElementById('reportText').value;
            const reportDiv = document.createElement('div');
            reportDiv.className = 'mb-2 p-2';
            reportDiv.style.borderBottom = '1px solid #eee';
            const now = new Date();
            reportDiv.innerHTML = `<div class="d-flex justify-content-between"><strong>${activity}</strong><small class="text-muted">${now.toLocaleString()}</small></div><div>Status: ${status}<br>Notes: ${notes}</div>`;
            previousReportsContainer.prepend(reportDiv);
            e.target.reset();
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>