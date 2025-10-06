<?php
require_once('dbconnection.php');
require_once('header-log.php');

// Get mysqli connection from Database singleton
$db = Database::getInstance();
$conn = $db->getConnection();

$tablesToSync = [
    'tbl_cancelled_call',
    'tbl_care_calls',
    'tbl_clients_medication_records',
    'tbl_clients_task_records',
    'tbl_daily_shift_records',
    'tbl_finished_meds',
    'tbl_finished_tasks',
    'tbl_general_client_form',
    'tbl_update_notice',
    'tbl_manage_runs'
];

$tablesData = [];
foreach ($tablesToSync as $tableName) {
    $dataResult = $conn->query("SELECT * FROM `$tableName`");
    $rows = [];
    if ($dataResult) {
        while ($r = $dataResult->fetch_assoc()) {
            $rows[] = $r;
        }
    }
    $tablesData[$tableName] = $rows;
}
$conn->close();
?>

<div class="container-fluid" id="splash-screen" style="height:100vh; display:flex; flex-direction:column; justify-content:center; align-items:center;">
    <div id="splash-logo img-logo">
        <img id="geosoft-logo" src="./images/logo.png" alt="Geosoft Care Logo" style="width: 185px; height: 70px;">
    </div>
</div>

<script>
    (async function() {
        const startTime = Date.now();
        const MIN_SYNC_TIME = 5000; // minimum 5 seconds display
        const dbName = "geosoft";
        const DB_VERSION = 2;
        const serverData = <?php echo json_encode($tablesData); ?>;

        const request = indexedDB.open(dbName, DB_VERSION);

        request.onupgradeneeded = function(event) {
            const db = event.target.result;
            for (const tableName in serverData) {
                if (!db.objectStoreNames.contains(tableName)) {
                    const sample = serverData[tableName][0];
                    const keyPath = sample ? Object.keys(sample)[0] : "id";
                    db.createObjectStore(tableName, {
                        keyPath
                    });
                }
            }
        };

        request.onsuccess = function(event) {
            const db = event.target.result;
            const tableNames = Object.keys(serverData);

            // Create promises for all tables
            const tablePromises = tableNames.map(tableName => {
                return new Promise(resolve => {
                    if (!db.objectStoreNames.contains(tableName)) {
                        resolve(); // skip if store not found
                        return;
                    }

                    const tx = db.transaction(tableName, "readwrite");
                    const store = tx.objectStore(tableName);

                    store.clear().onsuccess = function() {
                        for (const row of serverData[tableName]) {
                            store.put(row);
                        }
                    };

                    tx.oncomplete = function() {
                        resolve();
                    };

                    tx.onerror = function() {
                        console.error(`Failed to sync table ${tableName}`);
                        resolve(); // continue even on error
                    };
                });
            });

            // Wait for all tables to complete
            Promise.all(tablePromises).then(() => {
                const elapsed = Date.now() - startTime;
                const remaining = MIN_SYNC_TIME - elapsed;
                if (remaining > 0) {
                    setTimeout(() => window.location.href = "login.php", remaining);
                } else {
                    window.location.href = "login.php";
                }
            });
        };

        request.onerror = function() {
            alert("❌ Synchronization failed. Please try again.");
            window.location.href = "login.php";
        };
    })();
</script>

<?php require_once('footer-log.php'); ?>