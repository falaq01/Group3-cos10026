<?php     //we use define to create constants because they cannot be overwritten
define('DB_HOST', 'localhost'); //define creates a constant and are available globally
define('DB_USER', 'root'); //root - default XAMPP username
define('DB_PASS', ''); //empty password
define('DB_NAME', 'securegov'); //securegov — the database name you created in phpMyAdmin

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME); //$conn is the connection object — every DB query on every page uses this to talk to the database.

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error()); //mysqli_connect_error() --> comes my mysql itself
                                        // die() function is a PHP construct stops the page and displays it so the page doesnt crash
}
?>