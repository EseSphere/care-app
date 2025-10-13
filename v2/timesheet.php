<?php include_once 'header.php'; ?>

<div class="main-wrapper container mt-3">

    <!-- Timesheet Table -->
    <div class="card p-3 timesheet-card">
        <div class="timesheet-header mb-3">
            <h5>Timesheet</h5>
            <input type="date" id="timesheetDate" class="form-control date-selector" value="<?php echo date('Y-m-d'); ?>">
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Time In &#8594; Out</th>
                        <th>Rate</th>
                    </tr>
                </thead>
                <tbody id="timesheetContainer"></tbody>
            </table>
            <h6 class="mt-3">Total Amount:</h6>
        </div>
    </div>

</div>

<script>
    const dbName = 'care_app';
    const dbVersion = 1; // increase if you want to trigger onupgradeneeded

    // Open DB
    const dbRequest = indexedDB.open(dbName, dbVersion);

    dbRequest.onerror = () => console.error('Failed to open IndexedDB');

    dbRequest.onupgradeneeded = (event) => {
        const db = event.target.result;

        // Create object stores if they don't exist
        if (!db.objectStoreNames.contains('tbl_goesoft_carers_account')) {
            db.createObjectStore('tbl_goesoft_carers_account', {
                keyPath: 'user_special_Id'
            });
        }
        if (!db.objectStoreNames.contains('tbl_daily_shift_records')) {
            db.createObjectStore('tbl_daily_shift_records', {
                keyPath: 'userId'
            });
        }
    };

    dbRequest.onsuccess = (event) => {
        const db = event.target.result;
        loadUserTimesheet(db);
    };

    // Load timesheet
    function loadUserTimesheet(db) {
        if (!db.objectStoreNames.contains('tbl_goesoft_carers_account') ||
            !db.objectStoreNames.contains('tbl_daily_shift_records')) {
            console.error('Required object stores not found!');
            return;
        }

        const transaction = db.transaction(['tbl_goesoft_carers_account', 'tbl_daily_shift_records'], 'readonly');
        const carersStore = transaction.objectStore('tbl_goesoft_carers_account');
        const dailyShiftStore = transaction.objectStore('tbl_daily_shift_records');

        // Get user_special_Id (assume first user)
        const getUserRequest = carersStore.getAll();
        getUserRequest.onsuccess = () => {
            const carers = getUserRequest.result;
            if (!carers.length) return console.log('No carers found in tbl_goesoft_carers_account');
            const user_special_Id = carers[0].user_special_Id;

            // Get all daily shifts for this user
            const shifts = [];
            dailyShiftStore.openCursor().onsuccess = (event) => {
                const cursor = event.target.result;
                if (cursor) {
                    const record = cursor.value;
                    if (record.col_carer_Id === user_special_Id) {
                        shifts.push(record);
                    }
                    cursor.continue();
                } else {
                    renderTimesheet(shifts);
                }
            };
        };
    }

    // Render table
    function renderTimesheet(shifts) {
        const container = document.getElementById('timesheetContainer');
        container.innerHTML = '';

        let totalMinutes = 0;

        shifts.forEach(v => {
            if (!v.planned_timeIn || !v.planned_timeOut) return;

            const timeIn = v.planned_timeIn.split(':');
            const timeOut = v.planned_timeOut.split(':');
            const diffMinutes = (parseInt(timeOut[0]) * 60 + parseInt(timeOut[1])) -
                (parseInt(timeIn[0]) * 60 + parseInt(timeIn[1]));
            totalMinutes += diffMinutes;

            const row = document.createElement('tr');
            row.innerHTML = `
            <td>${v.client_name}</td>
            <td>${v.planned_timeIn} &#8594; ${v.planned_timeOut}</td>
            <td>${v.col_care_call}</td>
        `;
            container.appendChild(row);
        });

        const totalHours = Math.floor(totalMinutes / 60);
        const totalMins = totalMinutes % 60;
        const totalDisplay = document.createElement('div');
        totalDisplay.textContent = `Total Time: ${totalHours}h ${totalMins}m`;
        container.parentElement.appendChild(totalDisplay);
    }

    // Reload on date change (optional)
    document.getElementById('timesheetDate').addEventListener('change', (e) => {
        const db = dbRequest.result;
        if (db) loadUserTimesheet(db);
    });
</script>


<?php include_once 'footer.php'; ?>