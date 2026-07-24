<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
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

// Build query based on filters
$allowed_sort = ['EOInumber', 'job_ref', 'firstName', 'lastName', 'status'];
$sort = isset($_POST['sort_field']) && in_array($_POST['sort_field'], $allowed_sort)
    ? $_POST['sort_field']
    : 'EOInumber';

$allowed_direction = ['ASC', 'DESC'];
$direction = isset($_POST['sort_direction']) && in_array($_POST['sort_direction'], $allowed_direction)
    ? $_POST['sort_direction']
    : 'ASC';

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
$sql .= " ORDER BY " . $sort . " " . $direction;

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

<?php include 'header.inc'; ?>
<?php include 'nav.inc'; ?>

<div class="page-header">
    <div class="container">
        <h1>HR Manager Dashboard</h1>
        <p>Manage submitted expressions of interest.</p>
    </div>
</div>

<div class="section-light">
    <div class="container">

        <?php if ($delete_msg): ?>
            <p class="success-msg"><?php echo htmlspecialchars($delete_msg); ?></p>
        <?php endif; ?>

        <div class="form-card filter-card">
            <form method="post" action="manage.php">
                <div class="field-row">
                    <div class="field-group">
                        <label for="filter_ref">Filter by job reference</label>
                        <input type="text" id="filter_ref" name="filter_ref"
                            value="<?php echo isset($_POST['filter_ref']) ? htmlspecialchars($_POST['filter_ref']) : ''; ?>">
                    </div>
                    <div class="field-group">
                        <label for="filter_firstname">Filter by first name</label>
                        <input type="text" id="filter_firstname" name="filter_firstname"
                            value="<?php echo isset($_POST['filter_firstname']) ? htmlspecialchars($_POST['filter_firstname']) : ''; ?>">
                    </div>
                    <div class="field-group">
                        <label for="filter_lastname">Filter by last name</label>
                        <input type="text" id="filter_lastname" name="filter_lastname"
                            value="<?php echo isset($_POST['filter_lastname']) ? htmlspecialchars($_POST['filter_lastname']) : ''; ?>">
                    </div>
                </div>

                <div class="field-row">
                    <div class="field-group">
                        <label for="sort_field">Sort by</label>
                        <select id="sort_field" name="sort_field">
                            <option value="EOInumber" <?php echo $sort === 'EOInumber' ? 'selected' : ''; ?>>EOI Number</option>
                            <option value="job_ref" <?php echo $sort === 'job_ref' ? 'selected' : ''; ?>>Job Reference</option>
                            <option value="firstName" <?php echo $sort === 'firstName' ? 'selected' : ''; ?>>First Name</option>
                            <option value="lastName" <?php echo $sort === 'lastName' ? 'selected' : ''; ?>>Last Name</option>
                            <option value="status" <?php echo $sort === 'status' ? 'selected' : ''; ?>>Status</option>
                        </select>
                    </div>
                    <div class="field-group">
                        <label for="sort_direction">Direction</label>
                        <select id="sort_direction" name="sort_direction">
                            <option value="ASC" <?php echo $direction === 'ASC' ? 'selected' : ''; ?>>Ascending</option>
                            <option value="DESC" <?php echo $direction === 'DESC' ? 'selected' : ''; ?>>Descending</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-cta">Apply filters &amp; sort</button>
                    <a href="manage.php" class="btn btn-outline-dark">Reset</a>
                </div>
            </form>
        </div>

        <table class="manage-table">
            <thead>
                <tr>
                    <th>EOI #</th>
                    <th>Job Ref</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) === 0): ?>
                    <tr><td colspan="7">No applications found.</td></tr>
                <?php else: ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['EOInumber']); ?></td>
                            <td><?php echo htmlspecialchars($row['job_ref']); ?></td>
                            <td><?php echo htmlspecialchars($row['firstName'] . ' ' . $row['lastName']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td>
                                <form method="post" action="manage.php" class="inline-form">
                                    <input type="hidden" name="eoi_id" value="<?php echo $row['EOInumber']; ?>">
                                    <select name="new_status" onchange="this.form.submit()">
                                        <option value="New" <?php echo $row['status'] === 'New' ? 'selected' : ''; ?>>New</option>
                                        <option value="Current" <?php echo $row['status'] === 'Current' ? 'selected' : ''; ?>>Current</option>
                                        <option value="Final" <?php echo $row['status'] === 'Final' ? 'selected' : ''; ?>>Final</option>
                                    </select>
                                    <input type="hidden" name="change_status" value="1">
                                </form>
                            </td>
                            <td>
                                <form method="post" action="manage.php" class="inline-form"
                                    onsubmit="return confirm('Delete ALL applications for job ref <?php echo htmlspecialchars($row['job_ref']); ?>?');">
                                    <input type="hidden" name="delete_ref" value="<?php echo htmlspecialchars($row['job_ref']); ?>">
                                    <button type="submit" class="btn btn-outline-dark">Delete by ref</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>

    </div>
</div>

<?php include 'footer.inc'; ?>