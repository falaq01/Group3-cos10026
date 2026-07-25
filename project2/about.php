<?php
$pageTitle = "About Us | SecureGov";
$currentPage = "about";
include('settings.php'); 
include('header.inc');
?>

<div class="page-header">
  <div class="container">
    <span class="eyebrow-label">About the agency</span>
    <h1>A national mandate to keep government systems secure</h1>
    <p>SecureGov was established to give government departments a single, accountable partner for cybersecurity, infrastructure, and digital support.</p>
  </div>
</div>

<section class="section-white">
  <div class="container">
    <div class="grid-2">
      <div>
        <span class="eyebrow-label">Our mandate</span>
        <h2>Why SecureGov exists</h2>
        <p>Government departments manage sensitive data and deliver services that the public depends on daily. As these systems have become more connected, they have also become more exposed.</p>
        <p>SecureGov was formed to centralise that responsibility: one agency, accountable for the security and reliability of the technology behind public services.</p>
      </div>
      <div>
        <span class="eyebrow-label">How we work</span>
        <h2>Partnership, not just provision</h2>
        <p>We embed with department IT teams, run a 24/7 Security Operations Centre, and maintain shared infrastructure standards across government.</p>
        <p>Departments retain ownership and visibility over their own systems at all times.</p>
      </div>
    </div>
  </div>
</section>

<section class="section-light">
  <div class="container">
    <div class="section-head center">
      <span class="eyebrow-label">What guides us</span>
      <h2>Our principles</h2>
    </div>
    <div class="grid-3">
      <div class="card">
        <h3>Integrity</h3>
        <p>We hold ourselves to the same standards we expect departments to meet — transparent processes, clear accountability, and no shortcuts on security.</p>
      </div>
      <div class="card">
        <h3>Readiness</h3>
        <p>Threats don't keep business hours, so neither do we. Our teams are structured for continuous monitoring and rapid response.</p>
      </div>
      <div class="card">
        <h3>Capability building</h3>
        <p>We aim to strengthen department teams, not replace them — sharing knowledge and tools so security expertise grows across government.</p>
      </div>
    </div>
  </div>
</section>

<section class="section-white">
  <div class="container">
    <div class="section-head center">
      <span class="eyebrow-label">Fast facts</span>
      <h2>SecureGov by the numbers</h2>
    </div>
    <table>
      <caption>Key statistics about SecureGov operations</caption>
      <thead>
        <tr>
          <th scope="col">Metric</th>
          <th scope="col">Figure</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th scope="row">Departments protected</th>
          <td>14</td>
        </tr>
        <tr>
          <th scope="row">Threats neutralised (past year)</th>
          <td>1,200+</td>
        </tr>
        <tr>
          <th scope="row">Critical system uptime</th>
          <td>99.97%</td>
        </tr>
        <tr>
          <th scope="row">Average incident response time</th>
          <td>11 minutes</td>
        </tr>
        <tr>
          <th scope="row">Staff holding security clearance</th>
          <td>100%</td>
        </tr>
      </tbody>
    </table>
  </div>
</section>

<section class="section-light">
  <div class="container">
    <div class="section-head center">
      <span class="eyebrow-label">Project team</span>
      <h2>Group 3 — COS10026 Web Technology</h2>
      <p>This website was designed and built as a group project for the Applied Web Project.</p>
    </div>

    <figure class="group-figure">
      <img src="images/team-logo.png" alt="AI-generated avatars of Group 3 team members">
      <figcaption>Group 3, COS10026 Web Technology.</figcaption>
    </figure>

    <?php
    $query = "SELECT * FROM about";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo "<p class='db-error'>Error loading team data: " . mysqli_error($conn) . "</p>";
    } else {
        echo "<table class='team-table'>";
        echo "<caption>Group 3 members, student IDs and contributions</caption>";
        echo "<thead><tr>
                <th scope='col'>Name</th>
                <th scope='col'>Student ID</th>
                <th scope='col'>Hometown</th>
                <th scope='col'>Coding Snack</th>
                <th scope='col'>Contribution</th>
                <th scope='col'>Part 1 Work</th>
                <th scope='col'>Part 2 Work</th>
              </tr></thead>";
        echo "<tbody>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<th scope='row' data-label='Name'>" . htmlspecialchars($row['name']) . "</th>";
            echo "<td data-label='Student ID'>" . htmlspecialchars($row['studentId']) . "</td>";
            echo "<td data-label='Hometown'>" . htmlspecialchars($row['hometown']) . "</td>";
            echo "<td data-label='Coding Snack'>" . htmlspecialchars($row['codingSnack']) . "</td>";
            echo "<td data-label='Contribution'>" . htmlspecialchars($row['contribution']) . "</td>";
            echo "<td data-label='Part 1 Work'>" . htmlspecialchars($row['part1']) . "</td>";
            echo "<td data-label='Part 2 Work'>" . htmlspecialchars($row['part2']) . "</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    }
    ?>

  </div>
</section>

<section class="cta-band">
  <div class="container">
    <h2>Interested in joining our mission?</h2>
    <p>We are growing our cybersecurity, infrastructure, and support teams. See what is currently open.</p>
    <a href="jobs.php" class="btn btn-primary">View open roles</a>
  </div>
</section>

<?php include('footer.inc'); ?>