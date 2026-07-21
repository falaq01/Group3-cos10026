<?php
$pageTitle = "Apply Now | SecureGov";
$currentPage = "apply";
require_once('settings.php');
require_once('header.inc');
require_once('nav.inc');
?>

<div class="page-header">
  <div class="container">
    <h1>Apply for a role at SecureGov</h1>
    <p>Fill in the form below. Fields marked with an asterisk (*) are required.</p>
  </div>
</div>

<div class="section-light">
  <div class="container">
    <h2 class="form-section-title">Job Application Form</h2>
    <div class="form-card">

      <form action="process_eoi.php" method="post">

        <fieldset class="form-fieldset">
          <legend>Role &amp; your details</legend>
          <p class="fieldset-sub">Tell us which role you're applying for and how to reach you.</p>

          <div class="field-group">
            <label for="jobRefNumber">Job reference number<span class="req">*</span></label>
            <input type="text" id="jobRefNumber" name="jobRefNumber"
                   maxlength="5"
                   placeholder=" ">
            <p class="field-hint">Exactly 5 alphanumeric characters.</p>
          </div>

          <div class="field-row">
            <div class="field-group">
              <label for="firstName">First name<span class="req">*</span></label>
              <input type="text" id="firstName" name="firstName"
                     maxlength="20"
                     placeholder=" "
                     autocomplete="given-name">
              <p class="field-hint">Letters only, max 20 characters.</p>
            </div>
            <div class="field-group">
              <label for="lastName">Last name<span class="req">*</span></label>
              <input type="text" id="lastName" name="lastName"
                     maxlength="20"
                     placeholder=" "
                     autocomplete="family-name">
              <p class="field-hint">Letters only, max 20 characters.</p>
            </div>
          </div>

          <div class="field-group">
            <label for="dob">Date of birth<span class="req">*</span></label>
            <input type="text" id="dob" name="dob"
                   placeholder="dd/mm/yyyy">
            <p class="field-hint">Format: dd/mm/yyyy</p>
          </div>

          <fieldset class="form-fieldset-inline">
            <legend>Gender<span class="req">*</span></legend>
            <div class="choice-group">
              <label class="choice-item"><input type="radio" name="gender" value="male"> Male</label>
              <label class="choice-item"><input type="radio" name="gender" value="female"> Female</label>
              <label class="choice-item"><input type="radio" name="gender" value="other"> Other</label>
              <label class="choice-item"><input type="radio" name="gender" value="prefer-not"> Prefer not to say</label>
            </div>
          </fieldset>

        </fieldset>

        <fieldset class="form-fieldset">
          <legend>Address</legend>

          <div class="field-group">
            <label for="streetAddress">Street address<span class="req">*</span></label>
            <input type="text" id="streetAddress" name="streetAddress"
                   maxlength="40"
                   placeholder=" "
                   autocomplete="off">
            <p class="field-hint">Max 40 characters.</p>
          </div>

          <div class="field-row">
            <div class="field-group">
              <label for="suburb">Suburb / Town<span class="req">*</span></label>
              <input type="text" id="suburb" name="suburb"
                     maxlength="40"
                     placeholder=" "
                     autocomplete="address-level2">
            </div>
            <div class="field-group">
              <label for="state">State<span class="req">*</span></label>
              <select id="state" name="state" autocomplete="address-level1">
                <option value="">Select a state</option>
                <option value="VIC">VIC</option>
                <option value="NSW">NSW</option>
                <option value="QLD">QLD</option>
                <option value="NT">NT</option>
                <option value="WA">WA</option>
                <option value="SA">SA</option>
                <option value="TAS">TAS</option>
                <option value="ACT">ACT</option>
              </select>
            </div>
          </div>

          <div class="field-group">
            <label for="postcode">Postcode<span class="req">*</span></label>
            <input type="text" id="postcode" name="postcode"
                   maxlength="4"
                   placeholder=" "
                   autocomplete="postal-code">
            <p class="field-hint">Exactly 4 digits.</p>
          </div>

        </fieldset>

        <fieldset class="form-fieldset">
          <legend>Contact details</legend>

          <div class="field-row">
            <div class="field-group">
              <label for="email">Email address<span class="req">*</span></label>
              <input type="email" id="email" name="email"
                     placeholder=" "
                     autocomplete="email">
            </div>
            <div class="field-group">
              <label for="phone">Phone number<span class="req">*</span></label>
              <input type="tel" id="phone" name="phone"
                     placeholder=" "
                     autocomplete="tel">
              <p class="field-hint">8-12 digits, numbers only.</p>
            </div>
          </div>

        </fieldset>

        <fieldset class="form-fieldset">
          <legend>Skills</legend>

          <div class="field-group">
            <div class="choice-group">
              <label class="choice-item"><input type="checkbox" name="skills[]" value="network-security"> Network security</label>
              <label class="choice-item"><input type="checkbox" name="skills[]" value="cloud-security"> Cloud security</label>
              <label class="choice-item"><input type="checkbox" name="skills[]" value="penetration-testing"> Penetration testing</label>
              <label class="choice-item"><input type="checkbox" name="skills[]" value="incident-response"> Incident response</label>
              <label class="choice-item"><input type="checkbox" name="skills[]" value="it-support"> IT support</label>
              <label class="choice-item"><input type="checkbox" name="skills[]" value="other-skills"> Other skills...</label>
            </div>
          </div>

          <div class="field-group">
            <label for="otherSkills">If "Other skills..." selected, describe here</label>
            <textarea id="otherSkills" name="otherSkills"
                      maxlength="500"
                      placeholder=" "></textarea>
          </div>

        </fieldset>

        <div class="form-actions">
          <button type="reset" class="btn btn-outline-dark">Clear form</button>
          <button type="submit" class="btn btn-cta">Submit application</button>
        </div>

        <p class="form-note">
          Submitting this form sends your application to our recruitment team. You will receive a confirmation within 5 business days.
        </p>

      </form>
    </div>
  </div>
</div>

<?php require_once('footer.inc'); ?>