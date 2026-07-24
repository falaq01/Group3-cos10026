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
  $query = "SELECT * FROM jobs ORDER BY closes ASC";
  $result = mysqli_query($conn, $query);

  if (!$result) {
      echo "<p class='db-error'>Error loading jobs: " . mysqli_error($conn) . "</p>";
  } elseif (mysqli_num_rows($result) === 0) {
      echo "<p>No open positions at this time.</p>";
  } else {
      while ($job = mysqli_fetch_assoc($result)) {
          echo "<article class='jobcard'>";
          echo "<div class='cardbadges'>";
          echo "<span class='badge badgeref'>" . htmlspecialchars($job['job_ref']) . "</span>";
          echo "<span class='badge badgetype'>" . htmlspecialchars($job['badge_type']) . "</span>";
          echo "</div>";
          echo "<h2>" . htmlspecialchars($job['title']) . "</h2>";
          echo "<h3>" . htmlspecialchars($job['classification']) . "</h3>";
          echo "<p class='shortdesc'>" . htmlspecialchars($job['description']) . "</p>";

          echo "<div class='jobinfostrip'>";
          echo "<div class='infocell'><dt>Salary</dt><dd>" . htmlspecialchars($job['salary_range']) . "</dd></div>";
          echo "<div class='infocell'><dt>Reports To</dt><dd>" . htmlspecialchars($job['reporting_to']) . "</dd></div>";
          echo "<div class='infocell'><dt>Closes</dt><dd>" . htmlspecialchars($job['closes']) . "</dd></div>";
          echo "<div class='infocell'><dt>Clearance</dt><dd>" . htmlspecialchars($job['badge_clearance']) . "</dd></div>";
          echo "</div>";

          echo "<h4>Overview</h4><p>" . nl2br(htmlspecialchars($job['overview'])) . "</p>";

          echo "<h4>Responsibilities</h4><ol>";
          foreach (explode('|', $job['responsibilities']) as $item) {
              echo "<li>" . htmlspecialchars(trim($item)) . "</li>";
          }
          echo "</ol>";

          echo "<h4>Essential Criteria</h4><ul>";
          foreach (explode('|', $job['essential']) as $item) {
              echo "<li>" . htmlspecialchars(trim($item)) . "</li>";
          }
          echo "</ul>";

          echo "<h4>Preferable Criteria</h4><ul>";
          foreach (explode('|', $job['preferable']) as $item) {
              echo "<li>" . htmlspecialchars(trim($item)) . "</li>";
          }
          echo "</ul>";

          echo "<a href='apply.php?ref=" . urlencode($job['job_ref']) . "' class='btnapply'>Apply for this role</a>";
          echo "</article>";
      }
  }
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