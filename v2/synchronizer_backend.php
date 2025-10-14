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

// Tables to sync with optional date column and range type
$tablesToSync = [
    'tbl_cancelled_call' => ['date_column' => 'col_date', 'range' => 'one_month'],
    'tbl_clients_medication_records' => [],
    'tbl_client_status_records' => ['date_column' => 'col_start_date', 'range' => 'one_month'],
    'tbl_clients_task_records' => [],
    'tbl_daily_shift_records' => ['date_column' => 'shift_date', 'range' => 'two_months'],
    'tbl_finished_meds' => ['date_column' => 'med_date', 'range' => 'two_months'],
    'tbl_finished_tasks' => ['date_column' => 'task_date', 'range' => 'two_months'],
    'tbl_general_client_form' => [],
    'tbl_client_medical' => [],
    'tbl_future_planning' => [],
    'tbl_schedule_calls' => ['date_column' => 'Clientshift_Date', 'range' => 'two_months'],
    'tbl_general_team_form' => [],
    'tbl_manage_runs' => []
];

// Define date ranges
$currentMonthStart = date('Y-m-01 00:00:00');
$currentMonthEnd = date('Y-m-t 23:59:59');

$previousMonthStart = date('Y-m-01 00:00:00', strtotime('-1 month'));
$previousMonthEnd = date('Y-m-t 23:59:59', strtotime('-1 month'));

$tablesData = [];

foreach ($tablesToSync as $tableName => $options) {
    $dateColumn = $options['date_column'] ?? null;
    $rangeType = $options['range'] ?? null;

    // Start SQL with company filter
    $sql = "SELECT * FROM `$tableName` WHERE col_company_Id = ?";
    $params = [$companyId];
    $types = "s";

    // Apply date filter if defined
    if ($dateColumn) {
        if ($rangeType === 'one_month') {
            $sql .= " AND `$dateColumn` BETWEEN ? AND ?";
            $params[] = $currentMonthStart;
            $params[] = $currentMonthEnd;
            $types .= "ss";
        } elseif ($rangeType === 'two_months') {
            $sql .= " AND `$dateColumn` BETWEEN ? AND ?";
            $params[] = $previousMonthStart;
            $params[] = $currentMonthEnd;
            $types .= "ss";
        }
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
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
