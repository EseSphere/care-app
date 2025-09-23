<?php include_once 'header.php'; ?>

<div class="main-wrapper container">
    <div class="row gutters-sm">
        <!-- Client Profile Horizontal Layout -->
        <div class="col-md-12 mb-3">
            <div class="card p-3 d-flex flex-row align-items-center">
                <div style="flex:0 0 120px; text-align:center;">
                    <img src="./images/profile.jpg" alt="Profile" style="width:100px;height:100px;border-radius:50%;object-fit:cover;">
                </div>
                <div style="flex:1; padding-left:20px;">
                    <h4 id="clientName">Duru Artrick</h4>
                    <p id="clientAge" class="mb-1">Age: 34</p>
                    <div class="d-flex gap-2">
                        <a class="btn btn-sm btn-danger" href="./health?uryyToeSS4=uniqueId" id="dnacprBtn">Health</a>
                        <a class="btn btn-sm btn-info" href="./emergency?uryyToeSS4=uniqueId" id="allergiesBtn">Emergency</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Client Info & Stats -->
        <div class="col-md-12">
            <div class="card p-3">
                <h5>Care Plan</h5>
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
                    <div class="col-sm-4 fw-bold">Gender:</div>
                    <div class="col-sm-8" id="gender">3342</div>
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
                        <h6>Run Name</h6><span id="pendingTasks">3</span>
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
                <hr>
                <div class="row">
                    <div class="col-sm-4 fw-bold">Dob:</div>
                    <div class="col-sm-8" id="dateofbirth">He/Him</div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-4 fw-bold">Condition:</div>
                    <div class="col-sm-8" id="condition">He/Him</div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-4 fw-bold">Highlight:</div>
                    <div class="col-sm-8" id="highlight">He/Him</div>
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
    <div style="position: fixed; top:100px; right:20px;" class="ms-auto">
        <a href="./start-shift?uryyToeSS4=uniqueId" class="btn btn-primary">
            <i class="bi bi-play-circle"></i> Start
        </a>
    </div>
</div>

<script>
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
            img: './images/profile.jpg'
        },
        {
            name: 'Bob Smith',
            role: 'Carer',
            phone: '07440111333',
            img: './images/profile.jpg'
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

    // Assessment Cards with unique icons
    const assessments = [{
            title: 'What is important to me',
            link: './page1?uryyToeSS4=uniqueId',
            icon: 'bi-heart'
        },
        {
            title: 'My likes and dislikes',
            link: './page2?uryyToeSS4=uniqueId',
            icon: 'bi-emoji-smile'
        },
        {
            title: 'My current condition',
            link: './page3?uryyToeSS4=uniqueId',
            icon: 'bi-activity'
        },
        {
            title: 'My medical history',
            link: './page4?uryyToeSS4=uniqueId',
            icon: 'bi-journal-medical'
        },
        {
            title: 'My physical health',
            link: './page5?uryyToeSS4=uniqueId',
            icon: 'bi-heart-pulse'
        },
        {
            title: 'My mental health',
            link: './page6?uryyToeSS4=uniqueId',
            icon: 'bi-brain'
        },
        {
            title: 'How I communicate',
            link: './page7?uryyToeSS4=uniqueId',
            icon: 'bi-chat-left-text'
        },
        {
            title: 'Assistive equipment issues',
            link: './page8?uryyToeSS4=uniqueId',
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