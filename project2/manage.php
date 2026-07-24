<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

require_once 'settings.php';

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Handle delete by job reference
$delete_msg = '';
if (isset($_POST['delete_ref'])) {
    $delete_ref = trim(htmlspecialchars(stripslashes($_POST['delete_ref'])));
    $stmt = mysqli_prepare($conn, "DELETE FROM eoi WHERE job_ref = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $delete_ref);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $delete_msg = "All EOIs for job reference " . $delete_ref . " have been deleted.";
    } else {
        $delete_msg = "Error: " . mysqli_error($conn);
    }
}

// Handle status change
if (isset($_POST['change_status'])) {
    $eoi_id = intval($_POST['eoi_id']);
    $new_status = trim($_POST['new_status']);
    $allowed_statuses = ['New', 'Current', 'Final'];
    if (in_array($new_status, $allowed_statuses)) {
        $stmt = mysqli_prepare($conn, "UPDATE eoi SET status = ? WHERE EOInumber = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "si", $new_status, $eoi_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

// Build query based on filters using prepared statements
$allowed_sort = ['EOInumber', 'job_ref', 'firstName', 'lastName', 'status'];
$sort = isset($_POST['sort_field']) && in_array($_POST['sort_field'], $allowed_sort)
    ? $_POST['sort_field']
    : 'EOInumber';

$where = [];
$params = [];
$types = '';

if (!empty($_POST['filter_ref'])) {
    $where[] = "job_ref = ?";
    $params[] = trim($_POST['filter_ref']);
    $types .= 's';
}

if (!empty($_POST['filter_firstname'])) {
    $where[] = "firstName LIKE ?";
    $params[] = '%' . trim($_POST['filter_firstname']) . '%';
    $types .= 's';
}

if (!empty($_POST['filter_lastname'])) {
    $where[] = "lastName LIKE ?";
    $params[] = '%' . trim($_POST['filter_lastname']) . '%';
    $types .= 's';
}

$sql = "SELECT * FROM eoi";
if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY " . $sort;

if (!empty($params)) {
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    } else {
        die("Query error: " . mysqli_error($conn));
    }
} else {
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        die("Query error: " . mysqli_error($conn));
    }
}
?>
