<?php include_once 'header.php'; ?>

<div id="overlay"></div>

<!-- Topbar -->
<div class="topbar">
    <button class="menu-btn fs-1" id="menuBtn"><i class="bi bi-list"></i></button>
    <h4>Care Plan</h4>
    <div class="d-flex align-items-center gap-3">
        <span id="topClock"></span>
        <i class="bi bi-bell-fill fs-5" title="Notifications"></i>
        <button class="btn btn-light" id="darkModeBtn"><i class="bi bi-moon"></i></button>
    </div>
</div>

<div class="main-wrapper container">
    <div class="row gutters-sm">
        <!-- Client Profile Horizontal Layout -->
        <div class="col-md-12 mb-3">
            <div class="card p-3 d-flex flex-row align-items-center">
                <div style="flex:0 0 120px; text-align:center;">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Profile" style="width:100px;height:100px;border-radius:50%;object-fit:cover;">
                </div>
                <div style="flex:1; padding-left:20px;">
                    <h4 id="clientName">Duru Artrick</h4>
                    <p id="clientAge" class="text-muted mb-1">Age: 34</p>
                    <div class="d-flex gap-2">
                        <a href="#" id="dnacprBtn">DNACPR</a>
                        <a href="#" id="allergiesBtn">ALLERGIES</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Client Info & Stats -->
        <div class="col-md-12">
            <div class="card p-3">
                <h5>Client Information</h5>
                <hr>
                <div class="row">
                    <div class="col-sm-4 fw-bold">Phone:</div>
                    <div class="col-sm-8" id="clientPhone">07440111555</div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-4 fw-bold">Key Safe:</div>
                    <div class="col-sm-8" id="clientKeySafe">3342</div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-4 fw-bold">Address:</div>
                    <a href="https://www.google.com/maps/search/?api=1&query=Bay+Area,+San+Francisco,+CA" target="_blank" id="clientAddress" class="text-decoration-none text-dark">
                        <div class="col-sm-8">
                            Bay Area, San Francisco, CA
                        </div>
                    </a>
                </div>
                <hr>
                <div class="quick-stats mt-3">
                    <div class="stat alert alert-success">
                        <h6>Total Carers</h6><span id="totalCarers">2</span>
                    </div>
                    <div class="stat alert alert-danger">
                        <h6>Pending Tasks</h6><span id="pendingTasks">3</span>
                    </div>
                    <div class="stat alert alert-primary">
                        <h6>Visits Today</h6><span id="visitsToday">2</span>
                    </div>
                </div>
                <hr>
                <hr>
                <div class="row">
                    <div class="col-sm-4 fw-bold">Email:</div>
                    <div class="col-sm-8" id="clientEmail">duruartrick@example.com</div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-4 fw-bold">City:</div>
                    <div class="col-sm-8" id="clientCity">San Francisco</div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-4 fw-bold">Pronoun:</div>
                    <div class="col-sm-8" id="clientPronoun">He/Him</div>
                </div>
            </div>
        </div>

        <!-- Assigned Carers Panel -->
        <div class="col-md-12 mt-3">
            <div class="card p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">Assigned Carers</h5>
                </div>
                <div class="d-flex flex-wrap gap-3" id="carersContainer"></div>
            </div>
        </div>

        <!-- Recent Notes / Observations -->
        <div class="col-md-12 mt-3">
            <div class="card p-3">
                <h5>Recent Notes / Observations</h5>
                <div id="notesContainer"></div>
            </div>
        </div>

        <!-- Assessment Links as Separate Cards -->
        <div class="col-md-12 mt-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0">Assessments</h5>
                <button class="btn btn-danger btn-sm"><i class="bi bi-file-earmark-pdf"></i> Report</button>
            </div>
            <div id="assessmentCards"></div>
        </div>

    </div>

    <!-- ✅ Start Button pinned right -->
    <div style="position: fixed; top:65px; right:20px;" class="ms-auto">
        <a href="client-activities.php" class="btn btn-primary">
            <i class="bi bi-play-circle"></i> Start
        </a>
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
            role: 'Lead Carer',
            phone: '07440111222',
            img: 'https://randomuser.me/api/portraits/women/44.jpg'
        },
        {
            name: 'Bob Smith',
            role: 'Carer',
            phone: '07440111333',
            img: 'https://randomuser.me/api/portraits/men/45.jpg'
        }
    ];
    const carersContainer = document.getElementById('carersContainer');
    assignedCarers.forEach(c => {
        const div = document.createElement('div');
        div.className = 'd-flex flex-column align-items-center text-center p-2';
        div.style.width = '120px';
        div.innerHTML = `<div style="width:80px;height:80px;border-radius:50%;overflow:hidden;margin-bottom:5px;"><img src="${c.img}" style="width:100%;height:100%;object-fit:cover;" alt="${c.name}"></div>
  <strong style="font-size:.9rem;">${c.name}</strong><small class="text-muted">${c.role}</small>
  <a href="tel:${c.phone}" class="btn btn-sm btn-outline-success mt-1">Call</a>`;
        carersContainer.appendChild(div);
    });

    // Recent Notes
    const recentNotes = [{
            author: 'Alice Johnson',
            time: '2025-09-17',
            text: 'Client responded well to morning medication.'
        },
        {
            author: 'Bob Smith',
            time: '2025-09-16',
            text: 'Assisted with meal preparation, client seemed tired.'
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
        noteDiv.innerHTML = `<div class="d-flex justify-content-between"><strong>${n.author}</strong><small class="text-muted">${formatted}</small></div><div>${n.text}</div>`;
        notesContainer.appendChild(noteDiv);
    });

    // Assessment Cards with unique icons
    const assessments = [{
            title: 'What is important to me',
            link: './page1.php',
            icon: 'bi-heart'
        },
        {
            title: 'My likes and dislikes',
            link: './page2.php',
            icon: 'bi-emoji-smile'
        },
        {
            title: 'My current condition',
            link: './page3.php',
            icon: 'bi-activity'
        },
        {
            title: 'My medical history',
            link: './page4.php',
            icon: 'bi-journal-medical'
        },
        {
            title: 'My physical health',
            link: './page5.php',
            icon: 'bi-heart-pulse'
        },
        {
            title: 'My mental health',
            link: './page6.php',
            icon: 'bi-brain'
        },
        {
            title: 'How I communicate',
            link: './page7.php',
            icon: 'bi-chat-left-text'
        },
        {
            title: 'Assistive equipment issues',
            link: './page8.php',
            icon: 'bi-tools'
        }
    ];

    const assessmentContainer = document.getElementById('assessmentCards');
    assessments.forEach(a => {
        const card = document.createElement('div');
        card.className = 'card mb-2 p-3 assessment-card';
        card.innerHTML = `<a href="${a.link}"><i class="bi ${a.icon} me-2"></i>${a.title}</a>`;
        assessmentContainer.appendChild(card);
    });
</script>

<?php include_once 'footer.php'; ?>