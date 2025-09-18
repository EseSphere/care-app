<?php include_once 'header.php'; ?>

<div id="overlay"></div>

<!-- Topbar -->
<div class="topbar">
    <button class="menu-btn fs-1" id="menuBtn"><i class="bi bi-list"></i></button>
    <h4>Visits Log</h4>
    <div class="d-flex align-items-center gap-3">
        <span id="topClock"></span>
        <i class="bi bi-bell-fill fs-5" title="Notifications"></i>
        <button class="btn btn-light" id="darkModeBtn"><i class="bi bi-moon"></i></button>
    </div>
</div>

<div class="main-wrapper container mt-3">

    <!-- Search & Filter Bar -->
    <div class="row search-filter-row">
        <div class="col-md-6">
            <input type="text" id="clientSearch" class="form-control" placeholder="Search by name or location...">
        </div>
        <div class="col-md-3">
            <select id="priorityFilter" class="form-select">
                <option value="">All Priorities</option>
                <option value="High">High Priority</option>
                <option value="Normal">Normal Priority</option>
            </select>
        </div>
    </div>

    <!-- Enhanced Dummy Client List -->
    <div class="row" id="clientList">
        <?php
        $clients = [
            ['name' => 'Duru Artrick', 'location' => 'San Francisco, CA', 'img' => 'https://randomuser.me/api/portraits/men/32.jpg', 'lastVisit' => '2025-09-16', 'priority' => 'High'],
            ['name' => 'Alice Johnson', 'location' => 'Los Angeles, CA', 'img' => 'https://randomuser.me/api/portraits/women/45.jpg', 'lastVisit' => '2025-09-15', 'priority' => 'Normal'],
            ['name' => 'John Smith', 'location' => 'New York, NY', 'img' => 'https://randomuser.me/api/portraits/men/56.jpg', 'lastVisit' => '2025-09-14', 'priority' => 'High'],
            ['name' => 'Emma Brown', 'location' => 'Chicago, IL', 'img' => 'https://randomuser.me/api/portraits/women/68.jpg', 'lastVisit' => '2025-09-13', 'priority' => 'Normal'],
            ['name' => 'Michael Lee', 'location' => 'Houston, TX', 'img' => 'https://randomuser.me/api/portraits/men/77.jpg', 'lastVisit' => '2025-09-12', 'priority' => 'High']
        ];

        foreach ($clients as $c) {
            $priorityColor = $c['priority'] === 'High' ? 'danger' : 'success';
            echo '<div class="col-md-6 col-lg-4 client-item" data-name="' . strtolower($c['name']) . '" data-location="' . strtolower($c['location']) . '" data-priority="' . $c['priority'] . '">
                        <div class="card p-3 client-card card-hover d-flex flex-column" onclick="window.location=\'visits.php?client=' . urlencode($c['name']) . '\'" style="transition: 0.3s; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.08); cursor:pointer;">
                            <div class="d-flex align-items-center mb-2">
                                <div style="width:60px; height:60px; overflow:hidden; margin-right:15px;">
                                    <img src="' . $c['img'] . '" style="width:100%; height:100%; object-fit:cover;" alt="' . $c['name'] . '">
                                </div>
                                <div style="flex:1;">
                                    <h6 class="mb-0" style="font-weight:600;">' . $c['name'] . '</h6>
                                    <small class="text-muted">' . $c['location'] . '</small>
                                    <div><span class="badge bg-' . $priorityColor . ' mt-1">' . $c['priority'] . '</span></div>
                                </div>
                                <i class="bi bi-arrow-right-circle fs-4 text-info"></i>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <button class="btn btn-sm btn-outline-primary">DNACPR</button>
                                <button class="btn btn-sm btn-outline-warning">ALLERGIES</button>
                                <span class="text-muted" style="font-size:.8rem;">Last visit: ' . date("d M Y", strtotime($c['lastVisit'])) . '</span>
                            </div>
                        </div>
                    </div>';
        }
        ?>
    </div>

    <!-- Assigned Carers Panel -->
    <div class="col-md-12 mt-4">
        <div class="card p-3 carers-card">
            <h5>Assigned Carers</h5>
            <div class="d-flex flex-wrap gap-3" id="carersContainer"></div>
        </div>
    </div>

    <!-- Recent Notes / Observations Panel -->
    <div class="col-md-12 mt-3">
        <div class="card p-3 notes-card">
            <h5>Recent Note</h5>
            <div id="notesContainer"></div>
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

    // Live Search & Filter
    const searchInput = document.getElementById('clientSearch');
    const prioritySelect = document.getElementById('priorityFilter');
    const clientItems = document.querySelectorAll('.client-item');

    function filterClients() {
        const searchText = searchInput.value.toLowerCase();
        const selectedPriority = prioritySelect.value;

        clientItems.forEach(item => {
            const name = item.getAttribute('data-name');
            const location = item.getAttribute('data-location');
            const priority = item.getAttribute('data-priority');

            const matchesSearch = name.includes(searchText) || location.includes(searchText);
            const matchesPriority = selectedPriority === "" || priority === selectedPriority;

            item.style.display = (matchesSearch && matchesPriority) ? 'block' : 'none';
        });
    }

    searchInput.addEventListener('input', filterClients);
    prioritySelect.addEventListener('change', filterClients);
</script>

<?php include_once 'footer.php'; ?>