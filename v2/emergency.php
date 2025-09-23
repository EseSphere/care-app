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
                    <p id="clientAge" class="text-muted mb-1">Age: 34</p>
                    <div class="d-flex gap-2">
                        <a class="btn btn-sm btn-danger" href="./health?uryyToeSS4=uryyToeSS4" id="dnacprBtn">Health</a>
                        <a class="btn btn-sm btn-info" href="./emergency?uryyToeSS4=uryyToeSS4" id="allergiesBtn">Emergency</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Client Info & Stats -->
        <div class="col-md-12">
            <div class="card p-3">
                <h5>Emergency</h5>
                <hr>
                <div class="row">
                    <div class="col-sm-4 fw-bold">Do Not Attempt Cardiopulmonary Resuscitation (DNACPR)</div>
                    <div style="color: red; font-weight:800;" class="col-sm-8" id="resuscitation">No</div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-4 fw-bold">Does he/she have capacity to make decisions related to their health and wellbeing?</div>
                    <div class="col-sm-8" id="capacityDecision">Yes</div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-4 fw-bold">Property and Financial Affairs LPA</div>
                    <div class="col-sm-8" id="fiancialAffairs">No</div>
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
                <div class="row">
                    <div class="col-sm-4 fw-bold">Advance Decision to Refuse Treatment (ADRT / Living Will)</div>
                    <div class="col-sm-8" id="refuseTreatment">No</div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-4 fw-bold">Where is it kept?</div>
                    <div class="col-sm-8" id="locations">No</div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-4 fw-bold">Health and Welfare LPA</div>
                    <div class="col-sm-8" id="welfare">No</div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-4 fw-bold">Recommended Summary Plan for Emergency Care and Treatment (ReSPECT)</div>
                    <div class="col-sm-8" id="emergencyCare">No</div>
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
</script>

<?php include_once 'footer.php'; ?>