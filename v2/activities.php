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

    <!-- Client Profile Card with PRN -->
    <div class="col-md-12 mb-3">
        <div class="card p-3 d-flex flex-row align-items-center justify-content-between">
            <div style="flex:1;">
                <h4 id="clientName"><?= $client['name']; ?></h4>
                <p id="clientLocation" class="text-muted mb-1"><?= $client['location']; ?></p>
            </div>
            <button class="btn btn-warning prn-btn" data-bs-toggle="modal" data-bs-target="#prnModal"><i class="bi bi-bandaid"></i> PRN</button>
        </div>
    </div>

    <!-- Care Activities -->
    <div class="card p-3">
        <h5>Care Activities</h5>
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

    <!-- Assigned Carers -->
    <div class="col-md-12 mt-3">
        <div class="card p-3">
            <h5>Assigned Carers</h5>
            <div class="d-flex flex-wrap gap-3" id="carersContainer">
                <?php foreach ($assignedCarers as $c): ?>
                    <div class="d-flex flex-column align-items-center text-center p-2" style="width:120px;">
                        <div style="width:80px;height:80px;border-radius:50%;overflow:hidden;margin-bottom:5px;">
                            <img src="<?= $c['img']; ?>" style="width:100%;height:100%;object-fit:cover;" alt="<?= $c['name']; ?>">
                        </div>
                        <strong style="font-size:.9rem;"><?= $c['name']; ?></strong>
                        <small class="text-muted"><?= $c['role']; ?></small>
                        <a href="tel:<?= $c['phone']; ?>" class="btn btn-sm btn-outline-success mt-1">Call</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Recent Notes -->
    <div class="col-md-12 mt-3">
        <div class="card p-3">
            <h5>Recent Notes / Observations</h5>
            <div id="notesContainer">
                <?php foreach ($recentNotes as $n): ?>
                    <div class="mb-2 p-2" style="border-bottom:1px solid #eee;">
                        <div class="d-flex justify-content-between">
                            <strong><?= $n['author']; ?></strong>
                            <small class="text-muted"><?= date('d M Y', strtotime($n['time'])); ?></small>
                        </div>
                        <div><?= $n['text']; ?></div>
                    </div>
                <?php endforeach; ?>
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