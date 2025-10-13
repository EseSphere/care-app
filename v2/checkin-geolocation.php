<script>
    // Open IndexedDB safely
    function openDB() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open('care_app');
            request.onupgradeneeded = e => {
                const db = e.target.result;
                if (!db.objectStoreNames.contains('tbl_daily_shift_records')) {
                    const store = db.createObjectStore('tbl_daily_shift_records', {
                        keyPath: 'userId'
                    });
                    const columns = [
                        'shift_status', 'shift_date', 'planned_timeIn', 'planned_timeOut', 'shift_start_time',
                        'client_name', 'uryyToeSS4', 'col_care_call', 'client_group', 'carer_Name', 'col_carer_Id',
                        'col_area_Id', 'col_company_Id', 'col_call_status', 'col_miles', 'col_mileage',
                        'col_visit_status', 'col_visit_confirmation', 'col_care_call_Id', 'col_postcode', 'dateTime'
                    ];
                    columns.forEach(col => store.createIndex(col, col, {
                        unique: false
                    }));
                }
            };
            request.onsuccess = e => resolve(e.target.result);
            request.onerror = e => reject(e.target.error);
        });
    }

    // Get URL query parameter
    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    // Haversine formula to calculate miles
    function getDistanceMiles(lat1, lon1, lat2, lon2) {
        const R = 3958.8;
        const toRad = deg => deg * Math.PI / 180;
        const dLat = toRad(lat2 - lat1);
        const dLon = toRad(lon2 - lon1);
        const a = Math.sin(dLat / 2) ** 2 +
            Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) * Math.sin(dLon / 2) ** 2;
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }

    // Fetch record from IndexedDB
    async function getRecordById(storeName, key) {
        const db = await openDB();
        return new Promise((resolve, reject) => {
            const tx = db.transaction(storeName, 'readonly');
            const store = tx.objectStore(storeName);
            const req = store.getAll();
            req.onsuccess = e => {
                const record = e.target.result.find(r =>
                    r.userId == key || r.uryyToeSS4 == key || r.uryyTteamoeSS4 == key
                );
                resolve(record || null);
            };
            req.onerror = e => reject(e.target.error);
        });
    }

    // Update call_status in tbl_schedule_calls
    async function updateCallStatus(userId, status) {
        const db = await openDB();
        return new Promise((resolve, reject) => {
            const tx = db.transaction('tbl_schedule_calls', 'readwrite');
            const store = tx.objectStore('tbl_schedule_calls');
            const getAllReq = store.getAll();
            getAllReq.onsuccess = e => {
                const all = e.target.result || [];
                const rec = all.find(r =>
                    r.userId == userId || r.userId == Number(userId) || r.uryyToeSS4 == userId || r.uryyTteamoeSS4 == userId
                );
                if (!rec) return console.warn('updateCallStatus: no matching record found for', userId);
                rec.call_status = status;
                rec.col_call_status = status;
                store.put(rec);
            };
            getAllReq.onerror = ev => reject(ev.target.error);
            tx.oncomplete = () => resolve(true);
            tx.onerror = ev => reject(ev.target.error);
        });
    }

    // Calculate worked hours in decimal using actual check-in/out
    function getWorkedHours(start, end) {
        const startDate = new Date(start);
        const endDate = new Date(end);
        if (isNaN(startDate.getTime()) || isNaN(endDate.getTime())) return 0;
        const diffMs = endDate - startDate;
        return Math.max(diffMs / (1000 * 60 * 60), 0);
    }

    // Copy visit to tbl_daily_shift_records with prorated rates
    async function copyShiftRecord(userId) {
        const visit = await getRecordById('tbl_schedule_calls', userId);
        if (!visit) throw new Error('Visit not found.');

        const client = await getRecordById('tbl_general_client_form', visit.uryyToeSS4);
        if (!client) throw new Error('Client info not found.');

        const carer = await getRecordById('tbl_general_team_form', visit.first_carer_Id);
        if (!carer) throw new Error('Carer info not found.');

        // Geolocation
        const position = await new Promise((resolve, reject) => {
            navigator.geolocation.getCurrentPosition(resolve, reject, {
                enableHighAccuracy: true
            });
        });

        const miles = getDistanceMiles(
            position.coords.latitude,
            position.coords.longitude,
            parseFloat(client.client_latitude || 0),
            parseFloat(client.client_longitude || 0)
        );

        const mileageRate = parseFloat(carer.col_mileage || 0);
        const totalMileage = (mileageRate * parseFloat(miles.toFixed(2))).toFixed(2);

        const now = new Date();
        const shiftStartTime = `${now.getHours().toString().padStart(2,'0')}:${now.getMinutes().toString().padStart(2,'0')}`;

        // --- Calculate worked hours using actual check-in/out from tbl_schedule_calls ---
        const workedHours = getWorkedHours(visit.dateTime_in, visit.dateTime_out);

        // --- Parse rates and calculate pay rate & client rate ---
        const payRatePerHour = parseFloat(visit.pay_rate) || 0;
        const clientRatePerHour = parseFloat(visit.client_rate) || 0;

        const careCallRate = Math.round(workedHours * payRatePerHour * 100) / 100;
        const clientRate = Math.round(workedHours * clientRatePerHour * 100) / 100;

        const shiftRecord = {
            userId: visit.userId,
            shift_status: 'Checked in',
            shift_date: visit.Clientshift_Date,
            planned_timeIn: visit.dateTime_in,
            planned_timeOut: visit.dateTime_out,
            shift_start_time: shiftStartTime,
            shift_end_time: null,
            client_name: visit.client_name,
            uryyToeSS4: visit.uryyToeSS4,
            col_care_call: visit.care_calls,
            client_group: visit.client_area,
            carer_Name: visit.first_carer,
            task_note: null,
            col_carer_Id: visit.first_carer_Id,
            col_area_Id: visit.col_area_Id,
            col_company_Id: visit.col_company_Id,
            col_call_status: visit.call_status,
            col_carecall_rate: careCallRate.toFixed(2),
            col_miles: miles.toFixed(2),
            col_mileage: totalMileage,
            col_worked_time: workedHours.toFixed(2),
            col_client_rate: clientRate.toFixed(2),
            col_visit_status: 'True',
            col_visit_confirmation: 'Unconfirmed',
            col_care_call_Id: visit.userId,
            col_postcode: client.client_poster_code,
            dateTime: new Date().toISOString()
        };

        const db = await openDB();
        const tx = db.transaction('tbl_daily_shift_records', 'readwrite');
        const store = tx.objectStore('tbl_daily_shift_records');
        store.put(shiftRecord);

        return new Promise((resolve, reject) => {
            tx.oncomplete = async () => {
                try {
                    await updateCallStatus(userId, 'in-progress');
                } catch (upErr) {
                    console.error('Failed to update call_status:', upErr);
                }
                resolve(shiftRecord);
            };
            tx.onerror = e => reject(e.target.error);
        });
    }

    // Auto-start shift and redirect on success
    async function startShift() {
        const userId = getQueryParam('userId');
        if (!userId) return console.error('No userId provided.');
        try {
            const record = await copyShiftRecord(userId);
            console.log(`Shift started for ${record.client_name}. Miles: ${record.col_miles}. Start: ${record.shift_start_time}`);
            window.location.href = 'activities.php?uryyToeSS4=' + encodeURIComponent(record.uryyToeSS4) +
                '&Clientshift_Date=' + encodeURIComponent(record.shift_date) +
                '&care_calls=' + encodeURIComponent(record.col_care_call) +
                '&userId=' + encodeURIComponent(record.userId) +
                '&carerId=' + encodeURIComponent(record.col_carer_Id);
        } catch (err) {
            console.error('Error starting shift:', err);
        }
    }

    startShift();
</script>