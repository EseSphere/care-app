<?php include_once 'header.php'; ?>

<div class="main-wrapper container">
    <div class="row gutters-sm">
        <!-- Client Profile Horizontal Layout -->
        <div class="col-md-12 mb-3">
            <div class="card p-3 d-flex flex-row align-items-center">
                <div style="flex:0 0 120px; text-align:center;">
                    <div id="clientInitials" style="width:100px;height:100px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2rem;font-weight:bold;margin:auto;color:white;">
                        --
                    </div>
                </div>
                <div style="flex:1; padding-left:20px;">
                    <h4 id="clientName">Loading...</h4>
                    <p id="clientAge" class="mb-1">Age: --</p>
                    <div class="d-flex gap-2">
                        <a class="btn btn-sm btn-danger" id="dnacprBtn">Health</a>
                        <a class="btn btn-sm btn-info" id="allergiesBtn">Emergency</a>
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
                    <div class="col-sm-8" id="clientPhone">Loading...</div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-4 fw-bold">Key Safe:</div>
                    <div class="col-sm-8" id="clientKeySafe">Loading...</div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-4 fw-bold">Address:</div>
                    <a href="#" target="_blank" id="clientAddress" class="text-decoration-none text-dark">
                        <div class="col-sm-8">Loading...</div>
                    </a>
                </div>
                <hr>
                <!-- Assigned Carers Panel -->
                <div class="col-md-12 mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="mb-0">Assigned Carers</h5>
                    </div>
                    <div class="row" id="carersContainer"></div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-4 fw-bold">Email:</div>
                    <div class="col-sm-8" id="clientEmail">Loading...</div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-4 fw-bold">City:</div>
                    <div class="col-sm-8" id="clientCity">Loading...</div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-4 fw-bold">Pronoun:</div>
                    <div class="col-sm-8" id="clientPronoun">Loading...</div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-4 fw-bold">Dob:</div>
                    <div class="col-sm-8" id="dateofbirth">Loading...</div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-4 fw-bold">Condition:</div>
                    <div class="col-sm-8" id="condition">Loading...</div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-4 fw-bold">Gender:</div>
                    <div class="col-sm-8" id="gender">Loading...</div>
                </div>
            </div>
        </div>

        <div class="quick-stats mt-3">
            <div class="stat alert alert-success">
                <h6>Total Carers</h6><span id="totalCarers">--</span>
            </div>
            <div class="stat alert alert-danger">
                <h6>Visits Today</h6><span id="visitsToday">--</span>
            </div>
            <div class="stat alert alert-primary">
                <h6>Run Name</h6><span id="pendingTasks">--</span>
            </div>
        </div>

        <!-- Assessment Links as Separate Cards -->
        <div class="col-md-12 mt-3">
            <div class="card p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">Assessments</h5>
                    <button class="btn btn-danger btn-sm"><i class="bi bi-file-earmark-pdf"></i> Report</button>
                </div>
                <div id="assessmentCards"></div>
            </div>
        </div>
        <hr>
        <div class="card p-3">
            <div class="row">
                <div class="col-sm-4 fw-bold">Highlight:</div>
                <div class="col-sm-8" id="highlight">Loading...</div>
            </div>
        </div>
    </div>

    <!-- ✅ Start Button pinned right -->
    <div style="position: fixed; top:100px; right:20px;" class="ms-auto">
        <a href="#" id="startShiftBtn" class="btn btn-primary">
            <i class="bi bi-play-circle"></i> Start
        </a>
    </div>
</div>

<script>
    function calculateAge(dob) {
        if (!dob) return '--';
        const birthDate = new Date(dob);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        const dayDiff = today.getDate() - birthDate.getDate();
        if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) age--;
        return age;
    }

    async function openDB() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open('geosoft');
            request.onsuccess = e => resolve(e.target.result);
            request.onerror = e => reject(e.target.error);
        });
    }

    async function getUserSpecialId() {
        const db = await openDB();
        return new Promise((resolve, reject) => {
            const tx = db.transaction('tbl_goesoft_carers_account', 'readonly');
            const store = tx.objectStore('tbl_goesoft_carers_account');
            const req = store.getAll();
            req.onsuccess = e => resolve(e.target.result[0]?.user_special_Id || null);
            req.onerror = e => reject(e.target.error);
        });
    }

    async function getVisitsForCarer(userId) {
        const db = await openDB();
        return new Promise((resolve, reject) => {
            const tx = db.transaction('tbl_schedule_calls', 'readonly');
            const store = tx.objectStore('tbl_schedule_calls');
            const req = store.getAll();
            req.onsuccess = e => {
                const visits = e.target.result.filter(v => v.first_carer_Id === userId);
                resolve(visits);
            };
            req.onerror = e => reject(e.target.error);
        });
    }

    async function getClientDetails(uryyToeSS4) {
        const db = await openDB();
        return new Promise((resolve, reject) => {
            const tx = db.transaction('tbl_general_client_form', 'readonly');
            const store = tx.objectStore('tbl_general_client_form');
            const req = store.getAll();
            req.onsuccess = e => {
                const client = e.target.result.find(c => c.uryyToeSS4 === uryyToeSS4);
                resolve(client);
            };
            req.onerror = e => reject(e.target.error);
        });
    }

    function createInitialsCircle(fullName, fontSize = 2, diameter = 100) {
        const names = fullName.split(' ');
        const initials = ((names[0]?.charAt(0) || '') + (names[1]?.charAt(0) || '')).toUpperCase();
        const colors = ["#6c757d", "#0d6efd", "#198754", "#dc3545", "#ffc107", "#6f42c1", "#fd7e14"];
        const charCodeSum = (initials.charCodeAt(0) || 0) + (initials.charCodeAt(1) || 0);
        const bgColor = colors[charCodeSum % colors.length];

        const div = document.createElement('div');
        div.textContent = initials;
        div.style.width = `${diameter}px`;
        div.style.height = `${diameter}px`;
        div.style.borderRadius = '50%';
        div.style.display = 'flex';
        div.style.alignItems = 'center';
        div.style.justifyContent = 'center';
        div.style.fontSize = `${fontSize}rem`;
        div.style.fontWeight = 'bold';
        div.style.color = 'white';
        div.style.backgroundColor = bgColor;
        div.style.marginBottom = '5px';
        return div;
    }

    async function renderCarePlan() {
        const userSpecialId = await getUserSpecialId();
        if (!userSpecialId) return;

        // Get all visits for current user (Visits Today stays unchanged)
        const visits = await getVisitsForCarer(userSpecialId);
        if (!visits.length) return;

        const today = new Date().toISOString().slice(0, 10);

        // ✅ Visits Today (unchanged)
        const visitsTodayCount = visits.filter(v => v.Clientshift_Date === today).length;
        document.getElementById('visitsToday').textContent = visitsTodayCount;

        // Run Name
        const runName = visits[0]?.col_run_name || 'N/A';
        document.getElementById('pendingTasks').textContent = runName;

        // ✅ Client Special ID
        const uryyToeSS4 = visits[0]?.uryyToeSS4;
        if (!uryyToeSS4) return;

        // ✅ Total Carers and Assigned Carers for this client today
        const db = await openDB();
        const tx = db.transaction('tbl_schedule_calls', 'readonly');
        const store = tx.objectStore('tbl_schedule_calls');
        const req = store.getAll();
        const clientVisits = await new Promise((resolve, reject) => {
            req.onsuccess = e => {
                const all = e.target.result.filter(v =>
                    v.uryyToeSS4 === uryyToeSS4 && v.Clientshift_Date === today
                );
                resolve(all);
            };
            req.onerror = e => reject(e.target.error);
        });

        // Unique carers
        const carersSet = new Set();
        clientVisits.forEach(v => carersSet.add(v.first_carer));

        // Update DOM
        document.getElementById('totalCarers').textContent = carersSet.size;

        const assignedCarers = [];
        carersSet.forEach(name => assignedCarers.push({
            name,
            role: 'Carer'
        }));

        // Render Assigned Carers in Bootstrap columns
        const carersContainer = document.getElementById('carersContainer');
        carersContainer.innerHTML = '';
        assignedCarers.forEach(c => {
            const col = document.createElement('div');
            col.className = 'col-6 col-sm-4 col-md-3 col-lg-2 text-center mb-3';

            const card = document.createElement('div');
            card.className = 'd-flex flex-column align-items-center p-2';

            const initialsCircle = createInitialsCircle(c.name, 1.5, 80);
            card.appendChild(initialsCircle);

            const nameEl = document.createElement('strong');
            nameEl.style.fontSize = '.9rem';
            nameEl.textContent = c.name;
            card.appendChild(nameEl);

            const roleEl = document.createElement('small');
            roleEl.className = 'text-muted';
            roleEl.textContent = c.role;
            card.appendChild(roleEl);

            col.appendChild(card);
            carersContainer.appendChild(col);
        });

        // ✅ Client Details
        const client = await getClientDetails(uryyToeSS4);
        if (client) {
            const firstName = client.client_first_name || '';
            const lastName = client.client_last_name || '';

            const initialsDiv = document.getElementById('clientInitials');
            const clientInitialsCircle = createInitialsCircle(`${firstName} ${lastName}`, 2, 100);
            initialsDiv.replaceWith(clientInitialsCircle);
            clientInitialsCircle.id = 'clientInitials';

            document.getElementById('clientName').textContent = `${firstName} ${lastName}`;
            document.getElementById('clientAge').textContent = `Age: ${calculateAge(client.client_date_of_birth)}`;
            document.getElementById('clientEmail').textContent = client.client_email_address;
            document.getElementById('clientPhone').textContent = client.client_primary_phone;
            document.getElementById('clientKeySafe').textContent = client.client_access_details;
            document.getElementById('clientCity').textContent = client.client_city;
            document.getElementById('clientPronoun').textContent = client.client_sexuality;
            document.getElementById('dateofbirth').textContent = client.client_date_of_birth;
            document.getElementById('condition').textContent = client.client_ailment;
            document.getElementById('highlight').textContent = client.client_highlights;
            document.getElementById('gender').textContent = client.client_sexuality;

            const address = `${client.client_address_line_1}, ${client.client_address_line_2}, ${client.client_city}`;
            document.getElementById('clientAddress').href = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(address)}`;
            document.getElementById('clientAddress').querySelector('div').textContent = address;

            document.getElementById('dnacprBtn').href = `health.php?uryyToeSS4=${client.uryyToeSS4}`;
            document.getElementById('allergiesBtn').href = `emergency.php?uryyToeSS4=${client.uryyToeSS4}`;
            document.getElementById('startShiftBtn').href = `./start-shift?uryyToeSS4=${client.uryyToeSS4}`;
        }

        // ✅ Assessment Cards
        if (uryyToeSS4) {
            const assessments = [{
                    title: 'What is important to me',
                    link: `./wiitm.php?uryyToeSS4=${uryyToeSS4}`,
                    icon: 'bi-heart'
                },
                {
                    title: 'My likes and dislikes',
                    link: `./mlad.php?uryyToeSS4=${uryyToeSS4}`,
                    icon: 'bi-emoji-smile'
                },
                {
                    title: 'My current condition',
                    link: `./mcc.php?uryyToeSS4=${uryyToeSS4}`,
                    icon: 'bi-activity'
                },
                {
                    title: 'My medical history',
                    link: `./mmh.php?uryyToeSS4=${uryyToeSS4}`,
                    icon: 'bi-journal-medical'
                },
                {
                    title: 'My physical health',
                    link: `./mph.php?uryyToeSS4=${uryyToeSS4}`,
                    icon: 'bi-heart-pulse'
                },
                {
                    title: 'My mental health',
                    link: `./mmh.php?uryyToeSS4=${uryyToeSS4}`,
                    icon: 'bi-brain'
                },
                {
                    title: 'How I communicate',
                    link: `./hic.php?uryyToeSS4=${uryyToeSS4}`,
                    icon: 'bi-chat-left-text'
                },
                {
                    title: 'Assistive equipment issues',
                    link: `./aei.php?uryyToeSS4=${uryyToeSS4}`,
                    icon: 'bi-tools'
                }
            ];

            const assessmentContainer = document.getElementById('assessmentCards');
            assessmentContainer.innerHTML = '';
            assessments.forEach(a => {
                const card = document.createElement('div');
                card.className = 'card mb-2 p-3 assessment-card';
                card.innerHTML = `<a href="${a.link}"><i class="bi ${a.icon} me-2"></i>${a.title}</a>`;
                assessmentContainer.appendChild(card);
            });
        }
    }

    renderCarePlan();
</script>

<?php include_once 'footer.php'; ?>