<?php
/*
  process_eoi.php
  OWNER: Syed  (branch: syed-part2)
  COS10026 Applied Web Project Part 2

  What this file does, in order:
    1. Blocks direct URL access (no POST data means you get sent back to apply.php).
    2. Sanitises every incoming value with trim, stripslashes and htmlspecialchars.
    3. Validates every field server-side using regular expressions and filter_var.
    4. If anything is invalid, shows an error page and sends the user back to a
       pre-filled form. Nothing touches the database.
    5. If everything is valid, connects to MySQL, creates the eoi table if it
       does not already exist, and inserts the record with a prepared statement.
    6. Shows a confirmation page with the auto-generated EOI number.
*/

session_start();

/* ============================================================
   STEP 1 — Block direct URL access
   ============================================================
   If someone types process_eoi.php into the address bar there is no POST
   data, so we redirect them to the form instead of showing a broken page.
*/
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST)) {
    header('Location: apply.php');
    exit;
}


/* ============================================================
   STEP 2 — Sanitisation
   ============================================================
   trim            removes leading/trailing whitespace
   stripslashes    removes any backslashes added by escaping
   htmlspecialchars converts < > " ' & into HTML entities so nothing the user
                   types can be executed as HTML or script (XSS protection)
*/
function sanitiseInput($value)
{
    $value = trim($value);
    $value = stripslashes($value);
    $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    return $value;
}

// Pull a single POST field, sanitised. Returns "" if it was not submitted.
function getField($name)
{
    if (!isset($_POST[$name]) || is_array($_POST[$name])) {
        return "";
    }
    return sanitiseInput($_POST[$name]);
}

// Pull a checkbox group, sanitising each element. Returns an empty array if none ticked.
function getArrayField($name)
{
    if (!isset($_POST[$name]) || !is_array($_POST[$name])) {
        return array();
    }
    $clean = array();
    foreach ($_POST[$name] as $item) {
        $clean[] = sanitiseInput($item);
    }
    return $clean;
}

$jobRefNumber  = getField('jobRefNumber');
$firstName     = getField('firstName');
$lastName      = getField('lastName');
$dob           = getField('dob');
$gender        = getField('gender');
$streetAddress = getField('streetAddress');
$suburb        = getField('suburb');
$state         = getField('state');
$postcode      = getField('postcode');
$email         = getField('email');
$phone         = getField('phone');
$skillsArray   = getArrayField('skills');
$otherSkills   = getField('otherSkills');


/* ============================================================
   STEP 3 — Server-side validation
   ============================================================
   $errors is keyed by field name so apply.php can highlight the exact field
   that failed as well as list every message at the top of the page.
*/
$errors = array();

// Values that must match the options we actually offer on the form.
$validJobRefs = array('SG321', 'SG123');
$validGenders = array('Female', 'Male', 'Prefer not to say');
$validStates  = array('SHM', 'KHR', 'SHA', 'USL', 'ADA', 'DOH', 'ARY', 'AWK');
$validSkills  = array('Network security', 'Cloud computing', 'Programming / scripting', 'Incident response', 'Other');

// --- Job reference number: exactly 5 letters or digits, and must be a real posting
if ($jobRefNumber === "") {
    $errors['jobRefNumber'] = "Job reference number is required.";
} elseif (!preg_match('/^[A-Za-z0-9]{5}$/', $jobRefNumber)) {
    $errors['jobRefNumber'] = "Job reference number must be exactly 5 letters or digits, with no spaces or dashes.";
} elseif (!in_array(strtoupper($jobRefNumber), $validJobRefs)) {
    $errors['jobRefNumber'] = "That job reference number does not match any current SecureGov vacancy. Use SG321 or SG123.";
} else {
    $jobRefNumber = strtoupper($jobRefNumber);
}

// --- First name: letters only, 1 to 20 characters
if ($firstName === "") {
    $errors['firstName'] = "First name is required.";
} elseif (!preg_match('/^[A-Za-z]{1,20}$/', $firstName)) {
    $errors['firstName'] = "First name must be letters only and no longer than 20 characters.";
}

// --- Last name: letters only, 1 to 20 characters
if ($lastName === "") {
    $errors['lastName'] = "Last name is required.";
} elseif (!preg_match('/^[A-Za-z]{1,20}$/', $lastName)) {
    $errors['lastName'] = "Last name must be letters only and no longer than 20 characters.";
}

// --- Date of birth: dd/mm/yyyy, must be a real calendar date, applicant aged 15 to 80
if ($dob === "") {
    $errors['dob'] = "Date of birth is required.";
} elseif (!preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $dob, $dobParts)) {
    $errors['dob'] = "Date of birth must be in dd/mm/yyyy format, for example 05/03/1998.";
} else {
    $dobDay   = (int)$dobParts[1];
    $dobMonth = (int)$dobParts[2];
    $dobYear  = (int)$dobParts[3];

    if (!checkdate($dobMonth, $dobDay, $dobYear)) {
        $errors['dob'] = "That date of birth does not exist on the calendar.";
    } else {
        $birth = new DateTime("$dobYear-$dobMonth-$dobDay");
        $today = new DateTime();
        if ($birth > $today) {
            $errors['dob'] = "Date of birth cannot be in the future.";
        } else {
            $age = $today->diff($birth)->y;
            if ($age < 15 || $age > 80) {
                $errors['dob'] = "Applicants must be between 15 and 80 years old.";
            }
        }
    }
}

// --- Gender: must be one of the radio options
if ($gender === "") {
    $errors['gender'] = "Please select a gender option.";
} elseif (!in_array($gender, $validGenders)) {
    $errors['gender'] = "Invalid gender selection.";
}

// --- Street address: required, max 40 characters
if ($streetAddress === "") {
    $errors['streetAddress'] = "Street address is required.";
} elseif (strlen($streetAddress) > 40) {
    $errors['streetAddress'] = "Street address must be 40 characters or fewer.";
}

// --- Suburb: required, max 40 characters
if ($suburb === "") {
    $errors['suburb'] = "Suburb or town is required.";
} elseif (strlen($suburb) > 40) {
    $errors['suburb'] = "Suburb or town must be 40 characters or fewer.";
}

// --- State / municipality: must be one of the codes in the dropdown
if ($state === "") {
    $errors['state'] = "Please select a municipality.";
} elseif (!in_array($state, $validStates)) {
    $errors['state'] = "Invalid municipality selection.";
}

// --- Postcode: exactly 4 digits
if ($postcode === "") {
    $errors['postcode'] = "Postcode is required.";
} elseif (!preg_match('/^\d{4}$/', $postcode)) {
    $errors['postcode'] = "Postcode must be exactly 4 digits.";
}

// --- Email: filter_var does the heavy lifting, plus a length cap for the column
if ($email === "") {
    $errors['email'] = "Email address is required.";
} elseif (!filter_var(html_entity_decode($email, ENT_QUOTES, 'UTF-8'), FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = "Please enter a valid email address, for example name@example.com.";
} elseif (strlen($email) > 100) {
    $errors['email'] = "Email address must be 100 characters or fewer.";
}

// --- Phone: 8 to 12 digits, numbers only
if ($phone === "") {
    $errors['phone'] = "Phone number is required.";
} elseif (!preg_match('/^\d{8,12}$/', $phone)) {
    $errors['phone'] = "Phone number must be 8 to 12 digits with no spaces, dashes or brackets.";
}

// --- Skills: at least one, and every value must be one we actually offer
if (empty($skillsArray)) {
    $errors['skills'] = "Please select at least one skill.";
} else {
    foreach ($skillsArray as $skill) {
        if (!in_array($skill, $validSkills)) {
            $errors['skills'] = "Invalid skill selection.";
            break;
        }
    }
}

// --- Other skills: required if "Other" is ticked, must be empty if it is not
$otherTicked = in_array('Other', $skillsArray);
if ($otherTicked && $otherSkills === "") {
    $errors['otherSkills'] = "You ticked \"Other skills...\", so please describe those skills.";
} elseif (!$otherTicked && $otherSkills !== "") {
    $errors['otherSkills'] = "You entered other skills but did not tick the \"Other skills...\" box.";
} elseif (strlen($otherSkills) > 255) {
    $errors['otherSkills'] = "Other skills must be 255 characters or fewer.";
}

// Store the skills as a comma separated string for the VARCHAR(255) column.
$skills = implode(', ', $skillsArray);
if ($skills !== "" && strlen($skills) > 255) {
    $errors['skills'] = "Too many skills selected for the record.";
}


/* ============================================================
   STEP 4 — If validation failed, stop here and show the errors
   ============================================================
   The database is never touched. Everything the user typed goes back into the
   session so apply.php can redisplay a filled-in form.
*/
if (!empty($errors)) {

    $_SESSION['eoi_errors'] = $errors;
    $_SESSION['eoi_old'] = array(
        'jobRefNumber'  => html_entity_decode($jobRefNumber,  ENT_QUOTES, 'UTF-8'),
        'firstName'     => html_entity_decode($firstName,     ENT_QUOTES, 'UTF-8'),
        'lastName'      => html_entity_decode($lastName,      ENT_QUOTES, 'UTF-8'),
        'dob'           => html_entity_decode($dob,           ENT_QUOTES, 'UTF-8'),
        'gender'        => $gender,
        'streetAddress' => html_entity_decode($streetAddress, ENT_QUOTES, 'UTF-8'),
        'suburb'        => html_entity_decode($suburb,        ENT_QUOTES, 'UTF-8'),
        'state'         => $state,
        'postcode'      => html_entity_decode($postcode,      ENT_QUOTES, 'UTF-8'),
        'email'         => html_entity_decode($email,         ENT_QUOTES, 'UTF-8'),
        'phone'         => html_entity_decode($phone,         ENT_QUOTES, 'UTF-8'),
        'skills'        => $skillsArray,
        'otherSkills'   => html_entity_decode($otherSkills,   ENT_QUOTES, 'UTF-8')
    );

    $pageTitle   = "Application not submitted | SecureGov";
    $pageDesc    = "Your SecureGov expression of interest could not be submitted.";
    $currentPage = "apply";
    include 'header.inc';
    include 'nav.inc';
    ?>

    <div class="page-header">
      <div class="container">
        <h1>Your application was not submitted</h1>
        <p>Nothing has been saved. Please correct the problems below and try again.</p>
      </div>
    </div>

    <div class="section-light">
      <div class="container">
        <div class="form-card">
          <div class="error-summary" role="alert">
            <h3><?php echo count($errors); ?> problem<?php echo count($errors) === 1 ? '' : 's'; ?> found</h3>
            <ul>
              <?php foreach ($errors as $message) { ?>
                <li><?php echo $message; ?></li>
              <?php } ?>
            </ul>
          </div>
          <div class="form-actions">
            <a href="apply.php" class="btn btn-cta">Back to the form</a>
          </div>
          <p class="form-note">Your answers have been kept, so you only need to fix the fields listed above.</p>
        </div>
      </div>
    </div>

    <?php
    include 'footer.inc';
    exit;
}


/* ============================================================
   STEP 5 — Connect to MySQL and make sure the eoi table exists
   ============================================================ */
require_once 'settings.php';

/*
  On PHP 8.1+ mysqli throws exceptions by default, which would produce a raw
  fatal error page. Turning reporting off restores the older behaviour where
  the mysqli functions simply return false, so we can catch problems ourselves
  and show the user a proper message instead.
*/
mysqli_report(MYSQLI_REPORT_OFF);

$conn = @mysqli_connect($host, $user, $pwd);

if (!$conn) {
    $dbError = "Could not connect to the database server.";
} else {
    // Create the database if this is a fresh install, then select it.
    mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS `$sql_db`");
    if (!mysqli_select_db($conn, $sql_db)) {
        $dbError = "Could not select the database.";
    }
}

if (!isset($dbError)) {

    /*
      eoi table structure — taken directly from Hend's eoi.sql (branch hend-part2),
      verified column by column against her file on 24/07/2026.
      IF NOT EXISTS means this page creates the table automatically the first
      time it runs, which is the requirement, and does nothing after that.
    */
    $createTable = "
        CREATE TABLE IF NOT EXISTS eoi (
            EOInumber     INT AUTO_INCREMENT PRIMARY KEY,
            jobRefNumber  VARCHAR(5)   NOT NULL,
            firstName     VARCHAR(20)  NOT NULL,
            lastName      VARCHAR(20)  NOT NULL,
            dob           VARCHAR(10)  NOT NULL,
            gender        VARCHAR(20)  NOT NULL,
            streetAddress VARCHAR(40)  NOT NULL,
            suburb        VARCHAR(40)  NOT NULL,
            state         VARCHAR(3)   NOT NULL,
            postcode      VARCHAR(4)   NOT NULL,
            email         VARCHAR(100) NOT NULL,
            phone         VARCHAR(12)  NOT NULL,
            skills        VARCHAR(255) NOT NULL,
            otherSkills   TEXT         NULL,
            status        ENUM('New','Current','Final') DEFAULT 'New'
        )
    ";

    if (!mysqli_query($conn, $createTable)) {
        $dbError = "Could not create the applications table.";
    }
}


/* ============================================================
   STEP 6 — Insert the record
   ============================================================
   A prepared statement is used so the values are sent to MySQL separately
   from the SQL text. This makes SQL injection impossible even if the
   sanitisation above were bypassed.
*/
if (!isset($dbError)) {

    $insert = "INSERT INTO eoi
                 (jobRefNumber, firstName, lastName, dob, gender,
                  streetAddress, suburb, state, postcode, email, phone,
                  skills, otherSkills)
               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $insert);

    if (!$stmt) {
        $dbError = "Could not prepare the database statement.";
    } else {
        // "s" x 13 means all thirteen values are bound as strings.
        mysqli_stmt_bind_param(
            $stmt,
            "sssssssssssss",
            $jobRefNumber, $firstName, $lastName, $dob, $gender,
            $streetAddress, $suburb, $state, $postcode, $email, $phone,
            $skills, $otherSkills
        );

        if (!mysqli_stmt_execute($stmt)) {
            $dbError = "Could not save your application.";
        } else {
            // AUTO_INCREMENT gives us the EOI number to show the applicant.
            $eoiNumber = mysqli_insert_id($conn);
        }

        mysqli_stmt_close($stmt);
    }
}

if (isset($conn) && $conn) {
    mysqli_close($conn);
}

// A successful submission means the saved session copy is no longer needed.
unset($_SESSION['eoi_old'], $_SESSION['eoi_errors']);


/* ============================================================
   STEP 7 — Output: either the database error or the confirmation
   ============================================================ */
$pageTitle   = isset($dbError) ? "Something went wrong | SecureGov" : "Application received | SecureGov";
$pageDesc    = "Confirmation of your SecureGov expression of interest.";
$currentPage = "apply";

include 'header.inc';
include 'nav.inc';
?>

<?php if (isset($dbError)) { ?>

  <div class="page-header">
    <div class="container">
      <h1>Something went wrong on our side</h1>
      <p>Your details were valid, but we could not save them right now.</p>
    </div>
  </div>

  <div class="section-light">
    <div class="container">
      <div class="form-card">
        <div class="error-summary" role="alert">
          <h3>Database error</h3>
          <p><?php echo htmlspecialchars($dbError, ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
        <div class="form-actions">
          <a href="apply.php" class="btn btn-cta">Try again</a>
        </div>
      </div>
    </div>
  </div>

<?php } else { ?>

  <div class="page-header">
    <div class="container">
      <h1>Thank you, <?php echo $firstName; ?></h1>
      <p>Your expression of interest has been received by the SecureGov recruitment team.</p>
    </div>
  </div>

  <div class="section-light">
    <div class="container">
      <div class="form-card">

        <div class="confirmation-box">
          <p class="confirmation-label">Your EOI reference number</p>
          <p class="confirmation-number">EOI-<?php echo str_pad($eoiNumber, 5, "0", STR_PAD_LEFT); ?></p>
          <p class="confirmation-hint">Please keep this number. You will need it for any enquiry about your application.</p>
        </div>

        <h2 class="form-section-title">Summary of what you submitted</h2>

        <table class="summary-table">
          <tr><th scope="row">Job reference</th><td><?php echo $jobRefNumber; ?></td></tr>
          <tr><th scope="row">Name</th><td><?php echo $firstName . " " . $lastName; ?></td></tr>
          <tr><th scope="row">Date of birth</th><td><?php echo $dob; ?></td></tr>
          <tr><th scope="row">Gender</th><td><?php echo $gender; ?></td></tr>
          <tr><th scope="row">Address</th><td><?php echo $streetAddress . ", " . $suburb . ", " . $state . " " . $postcode; ?></td></tr>
          <tr><th scope="row">Email</th><td><?php echo $email; ?></td></tr>
          <tr><th scope="row">Phone</th><td><?php echo $phone; ?></td></tr>
          <tr><th scope="row">Skills</th><td><?php echo $skills; ?></td></tr>
          <tr><th scope="row">Other skills</th><td><?php echo ($otherSkills === "") ? "Not provided" : $otherSkills; ?></td></tr>
          <tr><th scope="row">Status</th><td>New</td></tr>
        </table>

        <div class="form-actions">
          <a href="jobs.php" class="btn btn-outline-dark">Browse more roles</a>
          <a href="index.php" class="btn btn-cta">Back to home</a>
        </div>

      </div>
    </div>
  </div>

<?php } ?>

<?php include 'footer.inc'; ?>
