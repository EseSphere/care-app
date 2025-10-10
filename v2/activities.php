<?php include_once 'header.php'; ?>

<?php
// --- Static Data ---
$client = [
    'name' => 'Duru Artrick',
    'location' => 'Bay Area, San Francisco, CA'
];

$careActivities = [
    ['type' => 'task', 'title' => 'Check Blood Pressure', 'status' => 'Updated'],
    ['type' => 'task', 'title' => 'Assist with Bath', 'status' => 'Not Updated'],
    ['type' => 'task', 'title' => 'Morning Exercise', 'status' => 'Updated'],
    ['type' => 'task', 'title' => 'Take Weight', 'status' => 'Not Updated'],
    ['type' => 'medication', 'title' => 'Paracetamol 500mg', 'status' => 'Updated'],
    ['type' => 'medication', 'title' => 'Insulin 10 units', 'status' => 'Not Updated'],
    ['type' => 'medication', 'title' => 'Vitamin D', 'status' => 'Updated']
];

$assignedCarers = [
    ['name' => 'Alice Johnson', 'role' => 'Primary Carer', 'phone' => '07440111222', 'img' => 'https://randomuser.me/api/portraits/women/45.jpg'],
    ['name' => 'John Smith', 'role' => 'Backup Carer', 'phone' => '07440111333', 'img' => 'https://randomuser.me/api/portraits/men/56.jpg']
];

$recentNotes = [
    ['author' => 'Alice Johnson', 'time' => '2025-09-16', 'text' => 'Blood pressure checked, within normal range.'],
    ['author' => 'John Smith', 'time' => '2025-09-15', 'text' => 'Assisted with morning exercise and bath.'],
    ['author' => 'Alice Johnson', 'time' => '2025-09-14', 'text' => 'Administered insulin, monitored glucose levels.']
];
?>

<div class="main-wrapper container">

    <!-- Client Profile Horizontal Layout -->
    <div class="col-md-12 mb-3">
        <div class="card p-3 d-flex flex-row align-items-center justify-content-between">
            <div style="flex:0 0 120px; text-align:center;">
                <div id="clientInitials" style="width:100px;height:100px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2rem;font-weight:bold;margin:auto;color:white;">
                    --
                </div>
            </div>
            <div style="flex:1; padding-left:20px;">
                <h4 id="clientName">Loading...</h4>
                <p id="clientAge" class="mb-1">Age: --</p>
            </div>
        </div>
    </div>

    <!-- Care Activities -->
    <div class="card p-3">
        <h5 class="w-100">
            Visit Activities
            <button class="btn btn-warning prn-btn text-end" data-bs-toggle="modal" data-bs-target="#prnModal"><i class="bi bi-bandaid"></i> PRN</button>
        </h5>
        <hr>
        <div id="careActivitiesContainer">
            <?php foreach ($careActivities as $c):
                $icon = $c['type'] === 'task' ? 'bi-list-task' : 'bi-capsule';
                $color = $c['type'] === 'task' ? '#0d6efd' : '#fd7e14';
                $statusClass = $c['status'] === 'Updated' ? 'status-updated' : 'status-not-updated';
            ?>
                <div class="care-item" style="background: <?= $color; ?>20; cursor:pointer;"
                    onclick="window.location.href='activity-report.php?<?= http_build_query($c); ?>'">
                    <div>
                        <i class="bi <?= $icon; ?> care-icon" style="color:<?= $color; ?>"></i>
                        <?= $c['title']; ?>
                    </div>
                    <span class="<?= $statusClass; ?>"><?= $c['status']; ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Start Button -->
    <div class="col-md-12 mt-3">
        <a href="./observation" class="btn btn-primary"><i class="bi bi-arrow-right-circle"></i> Continue</a>
    </div>

    <!-- Quick Stats & Highlight -->
    <div class="col-md-12 mt-3">
        <div class="quick-stats mt-3">
            <div class="stat alert alert-success">
                <h6>Total Carers</h6><span id="totalCarers">--</span>
            </div>
            <div class="stat alert alert-danger">
                <h6>Service Users</h6><span id="visitsToday">--</span>
            </div>
            <div class="stat alert alert-primary">
                <h6>Run Name</h6><span id="pendingTasks">--</span>
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
</div>

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
    // Clock
    function updateClock() {
        const clockEl = document.getElementById('topClock');
        if (clockEl) clockEl.textContent = new Date().toLocaleTimeString([], {
            hour: '2-digit',
            minute: '2-digit'
        });
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Dark Mode
    const darkBtn = document.getElementById('darkModeBtn');
    if (darkBtn) {
        darkBtn.addEventListener('click', () => document.body.classList.toggle('dark-mode'));
    }
</script>

<?php include_once 'footer.php'; ?>