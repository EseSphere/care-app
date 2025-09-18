<?php include_once 'header.php'; ?>

<div id="overlay"></div>

<!-- Topbar -->
<div class="topbar">
    <button class="menu-btn fs-1" id="menuBtn"><i class="bi bi-list"></i></button>
    <h4>Visit Details</h4>
    <div class="d-flex align-items-center gap-3">
        <span id="topClock"></span>
        <i class="bi bi-bell-fill fs-5" title="Notifications"></i>
        <button class="btn btn-light" id="darkModeBtn"><i class="bi bi-moon"></i></button>
    </div>
</div>

<div class="main-wrapper container mt-3">

    <!-- Client Profile Card -->
    <?php
    $client = [
        'name' => 'Duru Artrick',
        'location' => 'San Francisco, CA',
        'img' => 'https://randomuser.me/api/portraits/men/32.jpg',
        'PRN' => 'PRN-001'
    ];
    ?>
    <div class="card p-3 client-card mb-4">
        <div class="d-flex align-items-center">
            <div style="width:80px;height:80px;overflow:hidden;margin-right:15px;">
                <img src="<?= $client['img'] ?>" alt="<?= $client['name'] ?>" style="width:100%;height:100%;object-fit:cover;">
            </div>
            <div>
                <h5><?= $client['name'] ?></h5>
                <p class="mb-1"><?= $client['location'] ?></p>
                <span class="badge bg-primary"><?= $client['PRN'] ?></span>
            </div>
        </div>
    </div>

    <!-- Visits List -->
    <?php
    $assignedCarers = [
        ['name' => 'Alice Johnson', 'img' => 'https://randomuser.me/api/portraits/women/45.jpg'],
        ['name' => 'John Smith', 'img' => 'https://randomuser.me/api/portraits/men/56.jpg']
    ];

    $visits = [
        ['call' => 'Morning', 'timeIn' => '08:00', 'timeOut' => '09:00', 'date' => '2025-09-16', 'carers' => ['Alice Johnson'], 'note' => 'Assisted with breakfast and medication.'],
        ['call' => 'Lunch', 'timeIn' => '12:00', 'timeOut' => '12:45', 'date' => '2025-09-16', 'carers' => ['John Smith'], 'note' => 'Assisted with lunch and mobility exercises.'],
        ['call' => 'Tea', 'timeIn' => '16:00', 'timeOut' => '16:30', 'date' => '2025-09-16', 'carers' => ['Alice Johnson'], 'note' => 'Served tea and monitored fluid intake.'],
        ['call' => 'Bed', 'timeIn' => '21:00', 'timeOut' => '21:30', 'date' => '2025-09-16', 'carers' => ['John Smith', 'Alice Johnson'], 'note' => 'Prepared client for bed and ensured safety.'],
        ['call' => 'Morning', 'timeIn' => '08:00', 'timeOut' => '09:00', 'date' => '2025-09-15', 'carers' => ['Alice Johnson'], 'note' => 'Assisted with shower and morning routine.']
    ];
    $careCallColors = [
        'Morning' => 'warning',
        'Lunch' => 'danger',
        'Tea' => 'tea',   // Use custom class bg-tea
        'Bed' => 'dark'
    ];
    ?>

    <h5>Visit History</h5>
    <div class="row g-3">
        <?php foreach ($visits as $index => $v): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card p-3 visit-card card-hover h-100">
                    <!-- Care Call Badge and Date -->
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-<?= $careCallColors[$v['call']] ?? 'info' ?>"><?= $v['call'] ?></span>
                        <small class="text-muted"><?= date("d M Y", strtotime($v['date'])) ?></small>
                    </div>
                    <!-- Time In / Time Out -->
                    <div class="mb-2">
                        <i class="bi bi-clock me-1"></i>
                        <strong>In:</strong> <?= $v['timeIn'] ?>
                        <strong class="ms-2">Out:</strong> <?= $v['timeOut'] ?>
                    </div>
                    <!-- Carers Section -->
                    <div class="mb-2">
                        <strong>Carers:</strong>
                        <div class="d-flex flex-wrap align-items-center mt-1">
                            <?php foreach ($v['carers'] as $carer): ?>
                                <?php
                                $img = '';
                                foreach ($assignedCarers as $c) if ($c['name'] == $carer) $img = $c['img'];
                                ?>
                                <span class="d-inline-flex align-items-center me-2 mb-1">
                                    <img src="<?= $img ?>" alt="<?= $carer ?>" class="carers-avatar2">
                                    <span class="carer-name"><?= $carer ?></span>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <!-- Note with Collapse -->
                    <div class="mb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>Note</strong>
                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#noteCollapse<?= $index ?>" aria-expanded="false" aria-controls="noteCollapse<?= $index ?>">
                                <i class="bi bi-chevron-down rotate-icon collapsed"></i>
                            </button>
                        </div>
                        <div class="collapse mt-2" id="noteCollapse<?= $index ?>">
                            <div class="border rounded p-2"><?= $v['note'] ?></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
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

    // Rotate collapse icon
    const collapseElements = document.querySelectorAll('.collapse');
    collapseElements.forEach(c => {
        c.addEventListener('show.bs.collapse', () => {
            const icon = c.previousElementSibling.querySelector('.rotate-icon');
            if (icon) icon.classList.remove('collapsed');
        });
        c.addEventListener('hide.bs.collapse', () => {
            const icon = c.previousElementSibling.querySelector('.rotate-icon');
            if (icon) icon.classList.add('collapsed');
        });
    });
</script>

<?php include_once 'footer.php'; ?>