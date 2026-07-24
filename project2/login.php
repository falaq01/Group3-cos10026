<?php
session_start();

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: manage.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'settings.php';

    $conn = mysqli_connect($host, $user, $password, $database);

    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    $username = trim(htmlspecialchars(stripslashes($_POST['username'])));
    $password_input = trim($_POST['password']);

    // Prepared statement to prevent SQL injection
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ?");

    if (!$stmt) {
        die("Query preparation failed: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Verify hashed password
        if (password_verify($password_input, $row['password'])) {
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            header('Location: manage.php');
            exit();
        } else {
            $error = 'Invalid username or password.';
        }
    } else {
        $error = 'Invalid username or password.';
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>

<?php include 'header.inc'; ?>
<?php include 'nav.inc'; ?>

<div class="page-header">
    <div class="container">
        <h1>HR Manager Login</h1>
        <p>This area is restricted to authorised SecureGov staff only.</p>
    </div>
</div>

<div class="section-light">
    <div class="container">
        <div class="form-card" style="max-width: 420px;">
            <?php if ($error): ?>
                <p style="color: #C0395B; margin-bottom: 1rem;"><?php echo $error; ?></p>
            <?php endif; ?>

            <form method="post" action="login.php">
                <div class="field-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username">
                </div>
                <div class="field-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password">
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-cta">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.inc'; ?>
