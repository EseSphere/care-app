<?php include_once 'header.php'; ?>

<div class="main-wrapper container py-4">
    <!-- Client Profile Section -->
    <div class="card mb-4 shadow-sm border-0 rounded-3">
        <div class="card-body d-flex align-items-center flex-wrap">
            <!-- Client Avatar -->
            <div class="text-center me-4 mb-3 mb-md-0" style="flex: 0 0 120px;">
                <div id="clientInitials"
                    class="rounded-circle mx-auto d-flex align-items-center justify-content-center"
                    style="width: 100px; height: 100px; font-size: 2rem; font-weight: bold; color: white; background: #6c757d;">
                    --
                </div>
            </div>

            <!-- Client Info -->
            <div style="flex: 1;">
                <h4 id="clientName" class="fw-bold mb-1">Loading...</h4>
                <p id="clientAge" class="text-muted mb-2">Age: --</p>

                <div class="d-flex flex-wrap gap-2">
                    <a href="#" class="btn btn-sm btn-danger">
                        <i class="bi bi-heart-pulse"></i> Health
                    </a>
                    <a href="#" class="btn btn-sm btn-info text-white">
                        <i class="bi bi-exclamation-triangle"></i> Emergency
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Observation Form -->
    <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Daily Observation</h5>
            <p class="text-muted small mb-3">
                Please provide a brief observation about the client’s condition, mood, or any significant events during this care call.
            </p>
            <form id="observationForm" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="observationText" class="form-label fw-semibold">Observation Notes</label>
                    <textarea class="form-control" id="observationText" rows="5" placeholder="Write your observation here..." required></textarea>
                    <div class="invalid-feedback">Observation field cannot be empty.</div>
                </div>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-send"></i> Check Out
                </button>
            </form>
        </div>
    </div>

    <!-- Highlight Section -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">
            <h6 class="fw-bold text-secondary">Highlights</h6>
            <hr>
            <p id="highlight" class="text-muted mb-0">Loading highlights...</p>
        </div>
    </div>
</div>

<!-- Pending Activities Modal -->
<div class="modal fade" id="pendingModal" tabindex="-1" aria-labelledby="pendingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-warning text-dark border-0">
                <h5 class="modal-title fw-bold" id="pendingModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Pending Activities
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p class="fs-6 mb-2">Some tasks or medications are still pending.</p>
                <p class="fw-semibold text-danger">Please complete all pending activities before submitting your observation.</p>
            </div>
            <div class="modal-footer border-0 d-flex justify-content-center">
                <a href="activities.php" class="btn btn-warning w-100">
                    <i class="bi bi-arrow-left-circle"></i> Go Back to Activities
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    // Utility: Get URL parameter
    function getQueryParam(param) {
        return new URLSearchParams(window.location.search).get(param);
    }

    // Convert HH:MM to decimal hours
    function timeStringToHours(timeStr) {
        const [h, m] = timeStr.split(':').map(Number);
        return h + m / 60;
    }

    // Calculate worked hours (from shift_start_time to checkout)
    function calculateWorkedHours(shiftStart, shiftEnd) {
        const start = timeStringToHours(shiftStart);
        const end = timeStringToHours(shiftEnd);
        return Math.max(0, end - start);
    }

    // Normalize date to YYYY-MM-DD
    function normalizeDate(dateStr) {
        const d = new Date(dateStr);
        if (isNaN(d)) return dateStr;
        return d.toISOString().split('T')[0];
    }

    // Open IndexedDB
    function openDB() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open('geosoft');
            request.onsuccess = e => resolve(e.target.result);
            request.onerror = e => reject(e.target.error);
        });
    }

    // Get shift record (robust)
    async function getShiftRecord(carerId, uryyToeSS4, shiftDate, careCall) {
        const db = await openDB();
        return new Promise((resolve, reject) => {
            const tx = db.transaction('tbl_daily_shift_records', 'readonly');
            const store = tx.objectStore('tbl_daily_shift_records');
            const req = store.getAll();
            req.onsuccess = e => {
                const record = e.target.result.find(r =>
                    String(r.col_carer_Id) === String(carerId) &&
                    String(r.uryyToeSS4) === String(uryyToeSS4) &&
                    normalizeDate(r.shift_date) === normalizeDate(shiftDate) &&
                    String(r.col_care_call).toLowerCase() === String(careCall).toLowerCase()
                );
                resolve(record || null);
            };
            req.onerror = e => reject(e.target.error);
        });
    }

    // Get pay_rate and client_rate from tbl_schedule_calls
    async function getRates(userId) {
        const db = await openDB();
        return new Promise((resolve, reject) => {
            const tx = db.transaction('tbl_schedule_calls', 'readonly');
            const store = tx.objectStore('tbl_schedule_calls');
            const req = store.getAll();
            req.onsuccess = e => {
                const record = e.target.result.find(r => String(r.userId) === String(userId));
                resolve(record ? {
                    pay_rate: parseFloat(record.pay_rate || 0),
                    client_rate: parseFloat(record.client_rate || 0)
                } : {
                    pay_rate: 0,
                    client_rate: 0
                });
            };
            req.onerror = e => reject(e.target.error);
        });
    }

    // Placeholder for pending activities check
    async function checkPendingActivities() {
        return false;
    }

    // Checkout shift and update rates
    async function checkoutShift(observation) {
        const carerId = getQueryParam('carerId');
        const uryyToeSS4 = getQueryParam('uryyToeSS4');
        const shiftDate = getQueryParam('Clientshift_Date');
        const careCall = getQueryParam('care_calls');
        const userId = getQueryParam('userId');

        // ✅ Guard for missing params
        if (!carerId || !uryyToeSS4 || !shiftDate || !careCall || !userId) {
            console.error('Missing required URL parameters:', {
                carerId,
                uryyToeSS4,
                shiftDate,
                careCall,
                userId
            });
            alert('Cannot checkout: missing required information. Please go back and try again.');
            return null;
        }

        const shift = await getShiftRecord(carerId, uryyToeSS4, shiftDate, careCall);
        if (!shift) {
            console.error('Shift record not found for checkout:', {
                carerId,
                uryyToeSS4,
                shiftDate,
                careCall
            });
            alert('Shift record not found. Please check your activities page.');
            return null;
        }

        const rates = await getRates(userId);

        const now = new Date();
        const shiftEndTime = `${now.getHours().toString().padStart(2,'0')}:${now.getMinutes().toString().padStart(2,'0')}`;

        const workedHours = calculateWorkedHours(shift.shift_start_time, shiftEndTime);

        const careCallRate = Math.round(workedHours * rates.pay_rate * 100) / 100;
        const clientRate = Math.round(workedHours * rates.client_rate * 100) / 100;

        const timesheet_date = now.toISOString().split('T')[0];

        const updatedShift = {
            ...shift,
            shift_end_time: shiftEndTime,
            task_note: observation,
            timesheet_date,
            col_worked_time: workedHours.toFixed(2),
            col_carecall_rate: careCallRate.toFixed(2),
            col_client_rate: clientRate.toFixed(2)
        };

        const db = await openDB();
        const tx = db.transaction('tbl_daily_shift_records', 'readwrite');
        const store = tx.objectStore('tbl_daily_shift_records');
        store.put(updatedShift);

        return new Promise((resolve, reject) => {
            tx.oncomplete = () => resolve(updatedShift);
            tx.onerror = e => reject(e.target.error);
        });
    }

    // Observation form submission
    const obsForm = document.getElementById('observationForm');
    obsForm.addEventListener('submit', async e => {
        e.preventDefault();
        e.stopPropagation();
        obsForm.classList.add('was-validated');

        const observation = document.getElementById('observationText').value.trim();
        if (!observation) return;

        const hasPending = await checkPendingActivities();
        if (hasPending) {
            const pendingModal = new bootstrap.Modal(document.getElementById('pendingModal'));
            pendingModal.show();
            return;
        }

        try {
            const updated = await checkoutShift(observation);
            if (!updated) return; // stop if checkout failed due to missing params
            console.log('Shift checked out successfully:', updated);
            alert('Checkout successful!');
            window.location.href = "activities.php";
        } catch (err) {
            console.error('Error checking out shift:', err);
            alert('Failed to check out. Please try again.');
        }
    });

    // Example client info (replace with real fetch)
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('clientInitials').textContent = "JD";
        document.getElementById('clientName').textContent = "John Doe";
        document.getElementById('clientAge').textContent = "Age: 72";
        document.getElementById('highlight').textContent = "Client was cheerful and cooperative during the morning visit.";
    });
</script>

<?php include_once 'footer.php'; ?>