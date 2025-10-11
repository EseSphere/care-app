<script>
    const urlParams = new URLSearchParams(window.location.search);
    const clientId = urlParams.get('uryyToeSS4');
    const careCall = urlParams.get('care_calls');

    // Use date from URL or fallback to today
    let currentDate = urlParams.get('date');
    if (!currentDate) {
        const today = new Date();
        const yyyy = today.getFullYear();
        const mm = String(today.getMonth() + 1).padStart(2, '0');
        const dd = String(today.getDate()).padStart(2, '0');
        currentDate = `${yyyy}-${mm}-${dd}`;
    }

    // Open IndexedDB
    async function openDB() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open('geosoft');
            request.onsuccess = e => resolve(e.target.result);
            request.onerror = e => reject(e.target.error);
        });
    }

    // Fetch records from a store
    async function fetchRecords(storeName, clientId, careCall) {
        const db = await openDB();
        return new Promise((resolve, reject) => {
            if (!db.objectStoreNames.contains(storeName)) return resolve([]);
            const tx = db.transaction(storeName, 'readonly');
            const store = tx.objectStore(storeName);
            const req = store.getAll();
            req.onsuccess = () => {
                const filtered = req.result.filter(r =>
                    r.uryyToeSS4 === clientId &&
                    (
                        r.care_call1 === careCall ||
                        r.care_call2 === careCall ||
                        r.care_call3 === careCall ||
                        r.care_call4 === careCall ||
                        r.extra_call1 === careCall ||
                        r.extra_call2 === careCall ||
                        r.extra_call3 === careCall ||
                        r.extra_call4 === careCall
                    )
                );
                resolve(filtered);
            };
            req.onerror = () => reject(`Failed to fetch records from ${storeName}`);
        });
    }

    // Fetch finished records for date and care call
    async function fetchFinishedRecords(storeName, clientId, currentDate, careCall) {
        const db = await openDB();
        return new Promise((resolve, reject) => {
            if (!db.objectStoreNames.contains(storeName)) return resolve([]);
            const tx = db.transaction(storeName, 'readonly');
            const store = tx.objectStore(storeName);
            const req = store.getAll();
            req.onsuccess = () => {
                const filtered = req.result.filter(r =>
                    r.uryyToeSS4 === clientId && // <-- Correct field
                    (r.task_date === currentDate || r.med_date === currentDate) &&
                    r.care_calls === careCall
                );
                resolve(filtered);
            };
            req.onerror = () => reject(`Failed to fetch finished records from ${storeName}`);
        });
    }

    // Check if number of selected tasks/medications matches finished
    async function checkTasksAndMeds() {
        try {
            const [meds, tasks, finishedMeds, finishedTasks] = await Promise.all([
                fetchRecords('tbl_clients_medication_records', clientId, careCall),
                fetchRecords('tbl_clients_task_records', clientId, careCall),
                fetchFinishedRecords('tbl_finished_meds', clientId, currentDate, careCall),
                fetchFinishedRecords('tbl_finished_tasks', clientId, currentDate, careCall)
            ]);

            console.log(`Total Medications for this care call: ${meds.length}`);
            console.log(`Finished Medications for this date: ${finishedMeds.length}`);
            console.log(`Total Tasks for this care call: ${tasks.length}`);
            console.log(`Finished Tasks for this date: ${finishedTasks.length}`);

            const medsComplete = meds.length === finishedMeds.length;
            const tasksComplete = tasks.length === finishedTasks.length;

            if (medsComplete && tasksComplete) {
                console.log('All tasks and medications completed for this care call and date.');
            } else {
                console.log('Some tasks or medications are still pending.');
            }

            return {
                medsComplete,
                tasksComplete,
                meds,
                tasks,
                finishedMeds,
                finishedTasks
            };

        } catch (err) {
            console.error('Error checking tasks and medications:', err);
            return null;
        }
    }

    // Example usage
    checkTasksAndMeds().then(result => {
        if (!result) return;
        // Update UI or logic here using result.medsComplete & result.tasksComplete
    });
</script>