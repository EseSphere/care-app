<?php include_once 'header.php'; ?>

<div id="overlay"></div>

<!-- Topbar -->
<div class="topbar">
    <button class="menu-btn" id="menuBtn"><i class="bi bi-list"></i></button>
    <h4>Client Tasks</h4>
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

    <!-- Care Activities -->
    <div class="card p-3">
        <h5>Care Activities</h5>
        <hr>
        <div id="careActivitiesContainer"></div>
    </div>

    <!-- ✅ Start Button pinned right -->
    <div class="col-md-12 mt-3">
        <a href="observation.php" class="btn btn-primary"><i class="bi bi-arrow-right-circle"></i> Continue</a>
    </div>

    <!-- Assigned Carers Panel (card style) -->
    <div class="col-md-12 mt-3">
        <div class="card p-3">
            <h5>Assigned Carers</h5>
            <div class="d-flex flex-wrap gap-3" id="carersContainer"></div>
        </div>
    </div>

    <!-- Recent Notes / Observations (card style) -->
    <div class="col-md-12 mt-3">
        <div class="card p-3">
            <h5>Recent Notes / Observations</h5>
            <div id="notesContainer"></div>
        </div>
    </div>
</div>

<?php include_once 'footer.php'; ?>

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
            title: 'Check Blood Pressure',
            status: 'Updated'
        },
        {
            type: 'task',
            title: 'Assist with Bath',
            status: 'Not Updated'
        },
        {
            type: 'task',
            title: 'Morning Exercise',
            status: 'Updated'
        },
        {
            type: 'task',
            title: 'Take Weight',
            status: 'Not Updated'
        },
        {
            type: 'medication',
            title: 'Paracetamol 500mg',
            status: 'Updated'
        },
        {
            type: 'medication',
            title: 'Insulin 10 units',
            status: 'Not Updated'
        },
        {
            type: 'medication',
            title: 'Vitamin D',
            status: 'Updated'
        }
    ];
    const container = document.getElementById('careActivitiesContainer');
    careActivities.forEach(c => {
        const div = document.createElement('div');
        const icon = c.type === 'task' ? 'bi-list-task' : 'bi-capsule';
        const color = c.type === 'task' ?
            getComputedStyle(document.documentElement).getPropertyValue('--task-color') :
            getComputedStyle(document.documentElement).getPropertyValue('--med-color');
        const statusClass = c.status === 'Updated' ? 'status-updated' : 'status-not-updated';
        div.className = 'care-item';
        div.style.background = color + '20';
        div.innerHTML = `<div><i class="bi ${icon} care-icon" style="color:${color}"></i>${c.title}</div><span class="${statusClass}">${c.status}</span>`;

        // Redirect to activity-report.php with query parameters
        div.addEventListener('click', () => {
            const params = new URLSearchParams({
                title: c.title,
                type: c.type,
                status: c.status
            });
            window.location.href = `activity-report.php?${params.toString()}`;
        });

        container.appendChild(div);
    });

    // Assigned Carers (card style)
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

    // Recent Notes / Observations (card style)
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>