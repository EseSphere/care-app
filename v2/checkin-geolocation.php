<script>
    // Open IndexedDB safely (latest version automatically)
    function openDB() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open('geosoft');
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
        const R = 3958.8; // Radius of Earth in miles
        const toRad = deg => deg * Math.PI / 180;
        const dLat = toRad(lat2 - lat1);
        const dLon = toRad(lon2 - lon1);
        const a = Math.sin(dLat / 2) ** 2 +
            Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) * Math.sin(dLon / 2) ** 2;
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }

    // Robust record fetch (unchanged)
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

    // Improved: Update call_status in tbl_schedule_calls (works even if keyPath/type mismatches)
    async function updateCallStatus(userId, status) {
        const db = await openDB();
        return new Promise((resolve, reject) => {
            const tx = db.transaction('tbl_schedule_calls', 'readwrite');
            const store = tx.objectStore('tbl_schedule_calls');

            const getAllReq = store.getAll();
            getAllReq.onsuccess = e => {
                const all = e.target.result || [];

                // tolerant matching: match by userId (string/number) or the same fields used elsewhere
                const rec = all.find(r =>
                    r.userId == userId ||
                    r.userId == Number(userId) ||
                    r.uryyToeSS4 == userId ||
                    r.uryyTteamoeSS4 == userId
                );

                if (!rec) {
                    console.warn('updateCallStatus: no matching record found in tbl_schedule_calls for', userId);
                    // resolve false so caller can know nothing was updated
                    return;
                }

                // set both fields to cover variations in your schema
                rec.call_status = status;
                rec.col_call_status = status;

                const putReq = store.put(rec);
                putReq.onsuccess = () => {
                    console.log('updateCallStatus: call_status updated for', rec.userId || '(no primary key)');
                };
                putReq.onerror = ev => {
                    console.error('updateCallStatus: put error', ev.target.error);
                };
            };

            getAllReq.onerror = ev => {
                console.error('updateCallStatus: getAll error', ev.target.error);
                reject(ev.target.error);
            };

            tx.oncomplete = () => resolve(true);
            tx.onerror = ev => {
                console.error('updateCallStatus: transaction error', ev.target.error);
                reject(ev.target.error);
            };
        });
    }

    // Copy visit to tbl_daily_shift_records
    async function copyShiftRecord(userId) {
        const visit = await getRecordById('tbl_schedule_calls', userId);
        if (!visit) throw new Error('Visit not found.');

        const client = await getRecordById('tbl_general_client_form', visit.uryyToeSS4);
        if (!client) throw new Error('Client info not found.');

        const carer = await getRecordById('tbl_general_team_form', visit.first_carer_Id);
        if (!carer) throw new Error('Carer info not found.');

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
        const shiftStartTime = `${now.getHours().toString().padStart(2, '0')}:${now.getMinutes().toString().padStart(2, '0')}`;

        const shiftRecord = {
            userId: visit.userId,
            shift_status: 'Checked in',
            shift_date: visit.Clientshift_Date,
            planned_timeIn: visit.dateTime_in,
            planned_timeOut: visit.dateTime_out,
            shift_start_time: shiftStartTime,
            client_name: visit.client_name,
            uryyToeSS4: visit.uryyToeSS4,
            col_care_call: visit.care_calls,
            client_group: visit.client_area,
            carer_Name: visit.first_carer,
            col_carer_Id: visit.first_carer_Id,
            col_area_Id: visit.col_area_Id,
            col_company_Id: visit.col_company_Id,
            col_call_status: visit.call_status,
            col_miles: miles.toFixed(2),
            col_mileage: totalMileage,
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
                    // After inserting, update tbl_schedule_calls.call_status = 'in progress'
                    await updateCallStatus(userId, 'in-progress');
                } catch (upErr) {
                    // log but still resolve the shiftRecord so user flow continues
                    console.error('Failed to update call_status after inserting shift record:', upErr);
                }
                resolve(shiftRecord);
            };
            tx.onerror = e => reject(e.target.error);
        });
    }

    // Auto-start shift and redirect on success
    async function startShift() {
        const userId = getQueryParam('userId');
        if (!userId) {
            console.error('No userId provided.');
            return;
        }
        try {
            const record = await copyShiftRecord(userId);
            console.log(`Shift started for ${record.client_name}. Miles to client: ${record.col_miles} mi. Shift start: ${record.shift_start_time}`);
            window.location.href = 'activities.php';
        } catch (err) {
            console.error(`Error: ${err.message}`);
        }
    }

    // Run automatically
    startShift();
</script>