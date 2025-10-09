<?php
require_once('dbconnection.php');
header('Content-Type: application/json; charset=utf-8');

$db = Database::getInstance();
$conn = $db->getConnection();

// Get company_id from GET parameter
$companyId = $_GET['company_id'] ?? '';

if (empty($companyId)) {
    echo json_encode(["error" => "Missing company_id"]);
    exit;
}

// Tables to sync
$tablesToSync = [
    'tbl_cancelled_call' => ['col_company_Id'],
    'tbl_care_calls' => ['col_company_Id', 'col_carer_Id'],
    'tbl_clients_medication_records' => ['col_company_Id'],
    'tbl_client_status_records' => ['col_company_Id'],
    'tbl_clients_task_records' => ['col_company_Id'],
    'tbl_daily_shift_records' => ['col_company_Id', 'col_carer_Id'],
    'tbl_finished_meds' => ['col_company_Id'],
    'tbl_finished_tasks' => ['col_company_Id'],
    'tbl_general_client_form' => ['col_company_Id'],
    'tbl_client_medical' => ['col_company_Id'],
    'tbl_future_planning' => ['col_company_Id'],
    'tbl_schedule_calls' => ['col_company_Id'],
    'tbl_manage_runs' => ['col_company_Id']
];

$tablesData = [];

foreach ($tablesToSync as $tableName => $columns) {
    $sql = "SELECT * FROM `$tableName` WHERE col_company_Id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $companyId);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = [];
    while ($r = $result->fetch_assoc()) {
        $rows[] = $r;
    }

    // If table empty, return structure
    if (empty($rows)) {
        $desc = $conn->query("DESCRIBE `$tableName`");
        $structure = [];
        while ($col = $desc->fetch_assoc()) {
            $structure[$col['Field']] = null;
        }
        $rows[] = $structure;
    }

    $tablesData[$tableName] = $rows;
}

$conn->close();
echo json_encode($tablesData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
