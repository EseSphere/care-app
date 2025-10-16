<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Pending Activities</title>
</head>

<style>
    @media (max-width: 576px) {
        .modal-dialog {
            margin: 10px;
            width: 95% !important;
        }

        .modal-content {
            border-radius: 1rem;
            padding: 0.5rem;
        }

        .modal-body p,
        .modal-body ul {
            font-size: 1rem;
        }

        #goBackBtn {
            font-size: 1rem;
            width: 100%;
        }
    }

    @media (min-width: 577px) {
        .modal-dialog {
            max-width: 500px;
            width: 100%;
        }
    }

    .modal-content {
        border-radius: 1.25rem;
    }

    .modal-header {
        border-bottom: none;
        border-top-left-radius: 1.25rem;
        border-top-right-radius: 1.25rem;
    }

    .modal-body {
        padding: 1.5rem;
    }
</style>

<body>
    <div class="d-flex justify-content-center align-items-center min-vh-100 bg-dark">
        <div class="container">
            <!-- Bootstrap modal -->
            <div class="modal fade" id="completionModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content shadow-lg">
                        <div class="modal-header bg-warning text-dark">
                            <h5 class="modal-title fw-bold" id="completionModalLabel">Pending Activities</h5>
                        </div>
                        <div class="modal-body text-center">
                            <p id="completionMessage" class="fs-6 mb-3">
                                Some tasks or medications are still pending.
                            </p>
                            <ul id="pendingList" class="list-group list-group-flush mb-3"></ul>
                            <button id="goBackBtn" class="btn btn-outline-primary btn-lg w-100">
                                <i class="bi bi-arrow-left-circle"></i> Go Back to Activities
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End modal -->
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const clientId = urlParams.get('uryyToeSS4');
        const careCall = urlParams.get('care_calls');
        const clientUserId = urlParams.get('userId');
        const carerId = urlParams.get('carerId');

        let currentDate = urlParams.get('Clientshift_Date') || urlParams.get('date');
        if (!currentDate) {
            const today = new Date();
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const dd = String(today.getDate()).padStart(2, '0');
            currentDate = `${yyyy}-${mm}-${dd}`;
        }

        function normalizeDate(dateString) {
            if (!dateString) return '';
            return dateString.split('T')[0];
        }

        async function openDB() {
            return new Promise((resolve, reject) => {
                const request = indexedDB.open('care_app');
                request.onsuccess = e => resolve(e.target.result);
                request.onerror = e => reject(e.target.error);
            });
        }

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

        async function fetchFinishedRecords(storeName, clientId, currentDate, careCall) {
            const db = await openDB();
            return new Promise((resolve, reject) => {
                if (!db.objectStoreNames.contains(storeName)) return resolve([]);
                const tx = db.transaction(storeName, 'readonly');
                const store = tx.objectStore(storeName);
                const req = store.getAll();
                req.onsuccess = () => {
                    const filtered = req.result.filter(r =>
                        r.uryyToeSS4 === clientId &&
                        normalizeDate(r.task_date || r.med_date) === normalizeDate(currentDate) &&
                        r.care_calls === careCall
                    );
                    resolve(filtered);
                };
                req.onerror = () => reject(`Failed to fetch finished records from ${storeName}`);
            });
        }

        async function checkTasksAndMeds() {
            try {
                const [meds, tasks, finishedMeds, finishedTasks] = await Promise.all([
                    fetchRecords('tbl_clients_medication_records', clientId, careCall),
                    fetchRecords('tbl_clients_task_records', clientId, careCall),
                    fetchFinishedRecords('tbl_finished_meds', clientId, currentDate, careCall),
                    fetchFinishedRecords('tbl_finished_tasks', clientId, currentDate, careCall)
                ]);

                // Count completion based on the number of records
                const medsComplete = meds.length === 0 || meds.length === finishedMeds.length;
                const tasksComplete = tasks.length === 0 || tasks.length === finishedTasks.length;

                if (medsComplete && tasksComplete) {
                    // All activities finished
                    window.location.href = `observation.php?userId=${clientUserId}&uryyToeSS4=${clientId}&Clientshift_Date=${currentDate}&care_calls=${careCall}&carerId=${carerId}`;
                } else {
                    // Show modal with pending items
                    showPendingModal(meds, tasks, finishedMeds, finishedTasks);
                }
            } catch (err) {
                console.error('Error checking tasks and medications:', err);
            }
        }

        function showPendingModal(meds, tasks, finishedMeds, finishedTasks) {
            const modal = new bootstrap.Modal(document.getElementById('completionModal'));
            const pendingList = document.getElementById('pendingList');
            pendingList.innerHTML = '';

            // Create arrays of finished names
            const finishedMedsNames = finishedMeds.map(m => m.name);
            const finishedTasksNames = finishedTasks.map(t => t.name);

            // List pending medications
            meds.forEach(med => {
                const countInFinished = finishedMedsNames.filter(n => n === med.name).length;
                if (countInFinished < 1) { // If at least one record not finished
                    const li = document.createElement('li');
                    li.classList.add('list-group-item');
                    li.textContent = `Medication: ${med.name}`;
                    pendingList.appendChild(li);
                }
            });

            // List pending tasks
            tasks.forEach(task => {
                const countInFinished = finishedTasksNames.filter(n => n === task.name).length;
                if (countInFinished < 1) {
                    const li = document.createElement('li');
                    li.classList.add('list-group-item');
                    li.textContent = `Task: ${task.name}`;
                    pendingList.appendChild(li);
                }
            });

            document.getElementById('completionMessage').textContent =
                "Some activities are still pending. Please complete them before continuing.";
            modal.show();

            document.getElementById('goBackBtn').onclick = () => {
                if (document.referrer) {
                    history.back();
                } else {
                    window.location.href = `activities.php?uryyToeSS4=${clientId}&Clientshift_Date=${currentDate}&care_calls=${careCall}`;
                }
            };
        }

        checkTasksAndMeds();
    </script>
</body>

</html>