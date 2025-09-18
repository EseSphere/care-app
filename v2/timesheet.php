<?php include_once 'header.php'; ?>

<!-- SPA main content area -->
<div id="main-content">
    <div id="overlay"></div>

    <!-- Topbar -->
    <div class="topbar">
        <button class="menu-btn fs-1" id="menuBtn"><i class="bi bi-list"></i></button>
        <h4>Timesheet</h4>
        <div class="d-flex align-items-center gap-3">
            <span id="topClock"></span>
            <i class="bi bi-bell-fill fs-5" title="Notifications"></i>
            <button class="btn btn-light" id="darkModeBtn"><i class="bi bi-moon"></i></button>
        </div>
    </div>

    <div class="main-wrapper container mt-3">

        <!-- Client Profile Card -->
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
            </div>
        </div>

        <!-- Timesheet Table -->
        <div class="card p-3 timesheet-card">
            <div class="timesheet-header mb-3">
                <h5>Timesheet</h5>
                <input type="date" id="timesheetDate" class="form-control date-selector">
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody id="timesheetContainer"></tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Total:</th>
                            <th id="totalWorkedTime">0h 0m</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Assigned Carers Panel -->
        <div class="col-md-12 mt-3">
            <div class="card p-3">
                <h5>Assigned Carers</h5>
                <div class="d-flex flex-wrap gap-3" id="carersContainer"></div>
            </div>
        </div>

        <!-- Recent Notes / Observations Panel -->
        <div class="col-md-12 mt-3">
            <div class="card p-3">
                <h5>Recent Note</h5>
                <div id="notesContainer"></div>
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

    // Sample visits data
    const visitsData = {
        '2025-09-16': [{
                client: 'Duru Artrick',
                timeIn: '08:00',
                timeOut: '10:30'
            },
            {
                client: 'Duru Artrick',
                timeIn: '11:00',
                timeOut: '12:15'
            },
            {
                client: 'Duru Artrick',
                timeIn: '13:00',
                timeOut: '15:00'
            }
        ],
        '2025-09-17': [{
                client: 'Duru Artrick',
                timeIn: '09:00',
                timeOut: '11:00'
            },
            {
                client: 'Duru Artrick',
                timeIn: '11:30',
                timeOut: '13:00'
            }
        ]
    };

    const container = document.getElementById('timesheetContainer');
    const totalWorked = document.getElementById('totalWorkedTime');
    const dateInput = document.getElementById('timesheetDate');

    function loadTimesheet(date) {
        container.innerHTML = '';
        let totalMinutes = 0;
        const visits = visitsData[date] || [];
        visits.forEach((v) => {
            const timeIn = v.timeIn.split(':');
            const timeOut = v.timeOut.split(':');
            const diffMinutes = (parseInt(timeOut[0]) * 60 + parseInt(timeOut[1])) - (parseInt(timeIn[0]) * 60 + parseInt(timeIn[1]));
            totalMinutes += diffMinutes;

            const hours = Math.floor(diffMinutes / 60);
            const minutes = diffMinutes % 60;

            const row = document.createElement('tr');
            row.innerHTML = `
            <td>${v.client}</td>
            <td>${v.timeIn}</td>
            <td>${v.timeOut}</td>
            <td>${hours}h ${minutes}m</td>
        `;
            container.appendChild(row);
        });

        const totalHours = Math.floor(totalMinutes / 60);
        const totalMins = totalMinutes % 60;
        totalWorked.textContent = `${totalHours}h ${totalMins}m`;
    }


    const today = new Date().toISOString().split('T')[0];
    dateInput.value = today;
    loadTimesheet(today);
    dateInput.addEventListener('change', () => loadTimesheet(dateInput.value));

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

    // Recent Notes / Observations
    const recentNotes = [{
            author: 'Alice Johnson',
            time: '2025-09-16',
            text: 'Blood pressure checked, within normal range.'
        },
        {
            author: 'John Smith',
            time: '2025-09-15',
            text: 'Assisted with morning exercise and bath.'
        },
        {
            author: 'Alice Johnson',
            time: '2025-09-14',
            text: 'Administered insulin, monitored glucose levels.'
        }
    ];
    const notesContainer = document.getElementById('notesContainer');
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
        noteDiv.innerHTML = `
                <div class="d-flex justify-content-between">
                    <strong>${n.author}</strong>
                    <small class="text-muted">${formatted}</small>
                </div>
                <div>${n.text}</div>
            `;
        notesContainer.appendChild(noteDiv);
    });
</script>

<?php include_once 'footer.php'; ?>