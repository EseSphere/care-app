<?php include_once 'header.php'; ?>

<div class="main-wrapper container mt-4">
    <!-- Page Introduction -->
    <div class="mb-4">
        <h3 class="mb-1">Your Timesheet</h3>
        <p class="text-muted">
            Here you can view all your scheduled shifts for the selected date.
            Check your client visits, shift timings, and the corresponding care call rates.
            Use the date picker below to select a different day.
        </p>
    </div>

    <!-- Timesheet Table -->
    <div class="card p-3 shadow-sm timesheet-card">
        <div class="timesheet-header mb-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Timesheet</h5>
            <input type="date" id="timesheetDate" class="form-control date-selector w-auto" value="<?php echo date('Y-m-d'); ?>">
        </div>
        <div class="table-responsive position-relative">
            <div id="loadingIndicator" class="text-center py-2" style="display:none;">Loading shifts...</div>
            <table class="table table-bordered table-striped align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Client</th>
                        <th>Time In &#8594; Out</th>
                        <th>Rate</th>
                    </tr>
                </thead>
                <tbody id="timesheetContainer"></tbody>
            </table>
            <h6 class="mt-3 text-end fw-bold" id="totalAmount">Total Amount: £0.00</h6>
        </div>
    </div>
</div>

<style>
    /* Highlight row on hover */
    .timesheet-card tbody tr:hover {
        background-color: #f1f1f1;
    }

    /* Highlight today’s shifts */
    .today-shift {
        background-color: #e6f7ff !important;
        font-weight: 500;
    }

    /* Loading indicator styling */
    #loadingIndicator {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-style: italic;
        color: #555;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const timesheetContainer = document.getElementById('timesheetContainer');
        const timesheetDate = document.getElementById('timesheetDate');
        const totalAmountEl = document.getElementById('totalAmount');
        const loadingIndicator = document.getElementById('loadingIndicator');

        // Open existing IndexedDB safely
        function openDB() {
            return new Promise((resolve, reject) => {
                const request = indexedDB.open('care_app'); // no version
                request.onsuccess = () => resolve(request.result);
                request.onerror = () => reject('IndexedDB open error');
                request.onupgradeneeded = (event) => {
                    const db = event.target.result;
                    console.log('Upgrade triggered, DB version:', db.version);
                };
            });
        }

        // Get user_special_Id
        function getUserSpecialId(db) {
            return new Promise((resolve, reject) => {
                const tx = db.transaction('tbl_goesoft_carers_account', 'readonly');
                const store = tx.objectStore('tbl_goesoft_carers_account');
                const getRequest = store.getAll();

                getRequest.onsuccess = () => {
                    if (getRequest.result.length > 0) {
                        resolve(getRequest.result[0].user_special_Id);
                    } else {
                        reject('No user found in IndexedDB');
                    }
                };
                getRequest.onerror = () => reject('Error reading user data');
            });
        }

        // Get shift records for user on selected date
        function getShiftRecords(db, userId, date) {
            return new Promise((resolve, reject) => {
                const tx = db.transaction('tbl_daily_shift_records', 'readonly');
                const store = tx.objectStore('tbl_daily_shift_records');
                const records = [];

                store.openCursor().onsuccess = (event) => {
                    const cursor = event.target.result;
                    if (cursor) {
                        const record = cursor.value;
                        // Match col_carer_Id and shift_date
                        if (record.col_carer_Id === userId && record.shift_date === date) {
                            records.push(record);
                        }
                        cursor.continue();
                    } else {
                        resolve(records);
                    }
                };

                store.openCursor().onerror = () => reject('Error reading shift records');
            });
        }

        // Populate table
        function populateTable(records, selectedDate) {
            timesheetContainer.innerHTML = '';
            let total = 0;

            if (records.length === 0) {
                const tr = document.createElement('tr');
                tr.innerHTML = `<td colspan="3" class="text-center text-muted">No shifts found for selected date.</td>`;
                timesheetContainer.appendChild(tr);
            } else {
                records.forEach(record => {
                    total += parseFloat(record.col_carecall_rate) || 0;
                    const tr = document.createElement('tr');

                    // Highlight row if shift is today
                    const today = new Date().toISOString().split('T')[0];
                    if (record.shift_date === today) {
                        tr.classList.add('today-shift');
                    }

                    tr.innerHTML = `
                    <td>${record.client_name}</td>
                    <td>${record.planned_timeIn} &#8594; ${record.planned_timeOut}</td>
                    <td>£${parseFloat(record.col_carecall_rate).toFixed(2)}</td>
                `;
                    timesheetContainer.appendChild(tr);
                });
            }

            totalAmountEl.textContent = 'Total Amount: £' + total.toFixed(2);
        }

        // Load table
        function loadTimesheet() {
            const selectedDate = timesheetDate.value;
            loadingIndicator.style.display = 'block';

            openDB()
                .then(db => getUserSpecialId(db).then(userId => ({
                    db,
                    userId
                })))
                .then(({
                    db,
                    userId
                }) => getShiftRecords(db, userId, selectedDate))
                .then(records => {
                    populateTable(records, selectedDate);
                    loadingIndicator.style.display = 'none';
                })
                .catch(err => {
                    console.error(err);
                    timesheetContainer.innerHTML = `<tr><td colspan="3" class="text-center text-danger">Error loading shifts.</td></tr>`;
                    totalAmountEl.textContent = 'Total Amount: £0.00';
                    loadingIndicator.style.display = 'none';
                });
        }

        // Initial load
        loadTimesheet();

        // Update on date change
        timesheetDate.addEventListener('change', loadTimesheet);
    });
</script>

<?php include_once 'footer.php'; ?>