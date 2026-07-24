<?php
/*
  apply.php
  OWNER: Syed  (branch: syed-part2)
  COS10026 Applied Web Project Part 2

  Part 2 requirement: ALL HTML5 client-side validation is removed.
  There are no required, pattern, min, minlength, maxlength attributes, and
  no type="email" / type="tel" / type="date" inputs anywhere on this page,
  because those all trigger the browser's own validation. Every field is a
  plain text input, and ALL validation happens server-side in process_eoi.php.

  Sticky form: if process_eoi.php rejects the submission it stores the user's
  previous answers and the error list in the session, then sends them back
  here so they don't have to retype everything.
*/

session_start();

// Pull anything process_eoi.php handed back, then clear it so a refresh is clean.
$old    = isset($_SESSION['eoi_old'])    ? $_SESSION['eoi_old']    : array();
$errors = isset($_SESSION['eoi_errors']) ? $_SESSION['eoi_errors'] : array();
unset($_SESSION['eoi_old'], $_SESSION['eoi_errors']);

/* ---------- small helpers for redisplaying old input safely ---------- */

// Echo a previous text value, escaped so it can never inject HTML.
function old($field, $old)
{
    if (isset($old[$field])) {
        return htmlspecialchars($old[$field], ENT_QUOTES, 'UTF-8');
    }
    return "";
}

// Re-select a dropdown option.
function selectedIf($field, $value, $old)
{
    return (isset($old[$field]) && $old[$field] === $value) ? ' selected' : '';
}

// Re-check a radio button.
function checkedIf($field, $value, $old)
{
    return (isset($old[$field]) && $old[$field] === $value) ? ' checked' : '';
}

// Re-check a checkbox that was part of the skills array.
function checkedInArray($field, $value, $old)
{
    if (isset($old[$field]) && is_array($old[$field]) && in_array($value, $old[$field])) {
        return ' checked';
    }
    return '';
}

// Add an error class to a field wrapper so the CSS can highlight it.
function errClass($field, $errors)
{
    return isset($errors[$field]) ? ' has-error' : '';
}

// Print the inline error message under a field.
function errMsg($field, $errors)
{
    if (isset($errors[$field])) {
        echo '<p class="field-error">' . htmlspecialchars($errors[$field], ENT_QUOTES, 'UTF-8') . '</p>';
    }
}

$pageTitle   = "Apply Now | SecureGov";
$pageDesc    = "Submit your expression of interest for an open role at SecureGov, the national IT and cybersecurity services agency.";
$currentPage = "apply";

include 'header.inc';
include 'nav.inc';
?>

  <div class="page-header">
    <div class="container">
      <h1>Apply for a role at SecureGov</h1>
      <p>Fill in the form below. Fields marked with an asterisk (*) are required.</p>
    </div>
  </div>

  <div class="section-light">
    <div class="container">
      <h2 class="form-section-title">Expression of Interest</h2>

      <?php if (!empty($errors)) { ?>
        <div class="error-summary" role="alert">
          <h3>We couldn't submit your application</h3>
          <p>Please fix the following <?php echo count($errors); ?> problem<?php echo count($errors) === 1 ? '' : 's'; ?> and submit again.</p>
          <ul>
            <?php foreach ($errors as $message) { ?>
              <li><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></li>
            <?php } ?>
          </ul>
        </div>
      <?php } ?>

      <div class="form-card">

        <form action="process_eoi.php" method="post" novalidate>

          <fieldset class="form-fieldset">
            <legend>Role &amp; your details</legend>
            <p class="fieldset-sub">Tell us which role you're applying for and how to reach you.</p>

            <div class="field-group<?php echo errClass('jobRefNumber', $errors); ?>">
              <label for="jobRefNumber">Job reference number<span class="req">*</span></label>
              <input type="text" id="jobRefNumber" name="jobRefNumber" value="<?php echo old('jobRefNumber', $old); ?>">
              <p class="field-hint">Exactly 5 letters or numbers, no dash. Use SG321 or SG123 (see the Careers page).</p>
              <?php errMsg('jobRefNumber', $errors); ?>
            </div>

            <div class="field-row">
              <div class="field-group<?php echo errClass('firstName', $errors); ?>">
                <label for="firstName">First name<span class="req">*</span></label>
                <input type="text" id="firstName" name="firstName" value="<?php echo old('firstName', $old); ?>">
                <p class="field-hint">Letters only, max 20 characters.</p>
                <?php errMsg('firstName', $errors); ?>
              </div>
              <div class="field-group<?php echo errClass('lastName', $errors); ?>">
                <label for="lastName">Last name<span class="req">*</span></label>
                <input type="text" id="lastName" name="lastName" value="<?php echo old('lastName', $old); ?>">
                <p class="field-hint">Letters only, max 20 characters.</p>
                <?php errMsg('lastName', $errors); ?>
              </div>
            </div>

            <div class="field-row">
              <div class="field-group<?php echo errClass('email', $errors); ?>">
                <label for="email">Email address<span class="req">*</span></label>
                <input type="text" id="email" name="email" value="<?php echo old('email', $old); ?>">
                <p class="field-hint">Max 100 characters.</p>
                <?php errMsg('email', $errors); ?>
              </div>
              <div class="field-group<?php echo errClass('phone', $errors); ?>">
                <label for="phone">Phone number<span class="req">*</span></label>
                <input type="text" id="phone" name="phone" value="<?php echo old('phone', $old); ?>">
                <p class="field-hint">8 to 12 digits, numbers only.</p>
                <?php errMsg('phone', $errors); ?>
              </div>
            </div>

          </fieldset>

          <fieldset class="form-fieldset">
            <legend>Personal details</legend>
            <p class="fieldset-sub">We need this information to verify your identity for security clearance purposes.</p>

            <div class="field-group<?php echo errClass('dob', $errors); ?>">
              <label for="dob">Date of birth<span class="req">*</span></label>
              <input type="text" id="dob" name="dob" value="<?php echo old('dob', $old); ?>">
              <p class="field-hint">Format: dd/mm/yyyy, for example 05/03/1998. Applicants must be 15 to 80 years old.</p>
              <?php errMsg('dob', $errors); ?>
            </div>

            <fieldset class="form-fieldset-inline<?php echo errClass('gender', $errors); ?>">
              <legend>Gender<span class="req">*</span></legend>
              <div class="choice-group">
                <label class="choice-item"><input type="radio" name="gender" value="Female"<?php echo checkedIf('gender', 'Female', $old); ?>> Female</label>
                <label class="choice-item"><input type="radio" name="gender" value="Male"<?php echo checkedIf('gender', 'Male', $old); ?>> Male</label>
                <label class="choice-item"><input type="radio" name="gender" value="Prefer not to say"<?php echo checkedIf('gender', 'Prefer not to say', $old); ?>> Prefer not to say</label>
              </div>
              <?php errMsg('gender', $errors); ?>
            </fieldset>

          </fieldset>

          <fieldset class="form-fieldset">
            <legend>Address</legend>
            <p class="fieldset-sub">Your current residential address.</p>

            <div class="field-group<?php echo errClass('streetAddress', $errors); ?>">
              <label for="streetAddress">Street address<span class="req">*</span></label>
              <input type="text" id="streetAddress" name="streetAddress" value="<?php echo old('streetAddress', $old); ?>">
              <p class="field-hint">Up to 40 characters.</p>
              <?php errMsg('streetAddress', $errors); ?>
            </div>

            <div class="field-row">
              <div class="field-group<?php echo errClass('suburb', $errors); ?>">
                <label for="suburb">Suburb / Town<span class="req">*</span></label>
                <input type="text" id="suburb" name="suburb" value="<?php echo old('suburb', $old); ?>">
                <p class="field-hint">Up to 40 characters.</p>
                <?php errMsg('suburb', $errors); ?>
              </div>
              <div class="field-group<?php echo errClass('state', $errors); ?>">
                <label for="state">Municipality<span class="req">*</span></label>
                <select id="state" name="state">
  <option value="">Select a state</option>
  <option value="NSW"<?php echo selectedIf('state', 'NSW', $old); ?>>New South Wales</option>
  <option value="VIC"<?php echo selectedIf('state', 'VIC', $old); ?>>Victoria</option>
  <option value="QLD"<?php echo selectedIf('state', 'QLD', $old); ?>>Queensland</option>
  <option value="WA"<?php echo selectedIf('state', 'WA', $old); ?>>Western Australia</option>
  <option value="SA"<?php echo selectedIf('state', 'SA', $old); ?>>South Australia</option>
  <option value="TAS"<?php echo selectedIf('state', 'TAS', $old); ?>>Tasmania</option>
  <option value="ACT"<?php echo selectedIf('state', 'ACT', $old); ?>>Australian Capital Territory</option>
  <option value="NT"<?php echo selectedIf('state', 'NT', $old); ?>>Northern Territory</option>
</select>
                <?php errMsg('state', $errors); ?>
              </div>
            </div>

            <div class="field-group<?php echo errClass('postcode', $errors); ?>">
              <label for="postcode">Postcode<span class="req">*</span></label>
              <input type="text" id="postcode" name="postcode" value="<?php echo old('postcode', $old); ?>">
              <p class="field-hint">Exactly 4 digits, for example 3122.</p>
              <?php errMsg('postcode', $errors); ?>
            </div>

          </fieldset>

          <fieldset class="form-fieldset">
            <legend>Skills</legend>
            <p class="fieldset-sub">Select at least one skill that applies to you.</p>

            <div class="field-group<?php echo errClass('skills', $errors); ?>">
              <div class="choice-group">
                <label class="choice-item"><input type="checkbox" name="skills[]" value="Network security"<?php echo checkedInArray('skills', 'Network security', $old); ?>> Network security</label>
                <label class="choice-item"><input type="checkbox" name="skills[]" value="Cloud computing"<?php echo checkedInArray('skills', 'Cloud computing', $old); ?>> Cloud computing</label>
                <label class="choice-item"><input type="checkbox" name="skills[]" value="Programming / scripting"<?php echo checkedInArray('skills', 'Programming / scripting', $old); ?>> Programming / scripting</label>
                <label class="choice-item"><input type="checkbox" name="skills[]" value="Incident response"<?php echo checkedInArray('skills', 'Incident response', $old); ?>> Incident response</label>
                <label class="choice-item"><input type="checkbox" name="skills[]" value="Other"<?php echo checkedInArray('skills', 'Other', $old); ?>> Other skills...</label>
              </div>
              <?php errMsg('skills', $errors); ?>
            </div>

            <div class="field-group<?php echo errClass('otherSkills', $errors); ?>">
              <label for="otherSkills">If "Other skills..." is ticked, tell us more</label>
              <textarea id="otherSkills" name="otherSkills" rows="4"><?php echo old('otherSkills', $old); ?></textarea>
              <p class="field-hint">Leave this empty unless you ticked "Other skills...". Max 255 characters.</p>
              <?php errMsg('otherSkills', $errors); ?>
            </div>

          </fieldset>

          <div class="form-actions">
            <button type="reset" class="btn btn-outline-dark">Clear form</button>
            <button type="submit" class="btn btn-cta">Submit application</button>
          </div>

          <p class="form-note">
            Submitting this form records your expression of interest with our recruitment team.
            You'll be given an EOI reference number on the confirmation screen, so keep a note of it.
          </p>

        </form>
      </div>
    </div>
  </div>

<?php include 'footer.inc'; ?>
