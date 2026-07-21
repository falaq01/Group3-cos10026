<?php
$pageTitle = "Careers | SecureGov";
$currentPage = "jobs";
require_once('settings.php');
require_once('header.inc');
require_once('nav.inc');
?>

<div class="page-layout">

  <aside aria-label="Application information">
    <h2>How to Apply</h2>
    <p>Submit applications through the portal before the closing time. Include a current CV and a 500-word pitch addressing the essential requirements.</p>
    <a href="apply.php">Apply via portal &rarr;</a>

    <h2>Security Clearances</h2>
    <p>All roles require a background check. Clearance applications begin after a conditional offer is made.</p>

    <h2>Working with Us</h2>
    <ul>
      <li>15.4% superannuation</li>
      <li>$3,000 annual learning budget</li>
      <li>16 weeks paid parental leave</li>
      <li>Flexible working arrangements</li>
    </ul>
  </aside>

  <main>
    <?php
    // Muhammad Ali will add DB query here to load jobs dynamically
    ?>
  </main>

</div>

<section class="cta-band">
  <div class="container">
    <h2>See what it's like to work here</h2>
    <p>Get a closer look at our team, our mission, and the work we do to protect Qatar's digital infrastructure.</p>
    <iframe width="560" height="315" src="https://www.youtube.com/embed/vHm40_VWJ-g" title="Working at SecureGov" frameborder="0" allowfullscreen></iframe>
  </div>
</section>

<?php require_once('footer.inc'); ?>