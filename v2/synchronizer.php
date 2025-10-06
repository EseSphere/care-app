<?php
require_once('db_connect.php');
require_once('header-log.php');

$tablesToSync = [
    'tbl_goesoft_carers_account',
    'tbl_schedule_calls',
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

<div class="container-fluid" id="splash-screen">
    <div id="splash-logo img-logo">
        <img id="geosoft-logo" src="./images/logo.png" alt="Geosoft Care Logo" style="width: 185px; height: 70px;">
    </div>
</div>

<script>
    (async function() {
        const startTime = Date.now();
        const MIN_SYNC_TIME = 5000; // 5 seconds minimum
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

            let tablesProcessed = 0;
            const totalTables = Object.keys(serverData).length;

            for (const tableName in serverData) {
                const rows = serverData[tableName];
                if (!db.objectStoreNames.contains(tableName)) continue;

                const tx = db.transaction(tableName, "readwrite");
                const store = tx.objectStore(tableName);

                const clearReq = store.clear();
                clearReq.onsuccess = function() {
                    rows.forEach(row => store.put(row));
                };

                tx.oncomplete = function() {
                    tablesProcessed++;
                    if (tablesProcessed === totalTables) {
                        const elapsed = Date.now() - startTime;
                        const remaining = MIN_SYNC_TIME - elapsed;
                        setTimeout(() => {
                            window.location.href = "login.php";
                        }, remaining > 0 ? remaining : 0);
                    }
                };
            }
        };

        request.onerror = function() {
            alert("❌ Synchronization failed. Please try again.");
            window.location.href = "login.php";
        };
    })();
</script>

<?php require_once('footer-log.php'); ?>