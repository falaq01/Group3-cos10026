<?php
$pageTitle = "Home | SecureGov";
$currentPage = "home";
require_once('settings.php');
require_once('header.inc');
require_once('nav.inc');
?>

<div class="page-header">
  <div class="container">
    <span class="eyebrow-label">National Cybersecurity Infrastructure</span>
    <h1>Protecting the systems government runs on.</h1>
    <p>SecureGov is the national agency responsible for securing networks, infrastructure, and digital services across government departments so the public can trust that the systems behind essential services stay safe, available, and resilient.</p>
    <div class="hero-actions">
      <a href="about.php" class="btn btn-outline">About the agency</a>
      <a href="jobs.php" class="btn btn-primary">View open roles</a>
    </div>
  </div>
</div>

<div class="section-navy">
  <div class="container">
    <div class="stat-row">
      <div class="stat">
        <span class="num">14</span>
        <span class="label">Departments protected</span>
      </div>
      <div class="stat">
        <span class="num">1,200+</span>
        <span class="label">Threats neutralised</span>
      </div>
      <div class="stat">
        <span class="num">99.97%</span>
        <span class="label">System uptime</span>
      </div>
      <div class="stat">
        <span class="num">11 min</span>
        <span class="label">Avg. incident response</span>
      </div>
    </div>
  </div>
</div>

<section class="section-light">
  <div class="container">
    <div class="section-head center">
      <span class="eyebrow-label">What we do</span>
      <h2>Securing government at every layer</h2>
    </div>
    <div class="grid-3">
      <div class="card">
        <img src="images/shield-icon.png" alt="Network security icon" width="40">
        <h3>Network Security</h3>
        <p>24/7 monitoring, threat detection, and rapid incident response across all connected government systems.</p>
      </div>
      <div class="card">
        <img src="images/icon-infrastructure.png" alt="Infrastructure icon" width="40">
        <h3>IT Infrastructure</h3>
        <p>Data centre management, workstation deployment, and system maintenance for government departments.</p>
      </div>
      <div class="card">
        <img src="images/icon-support.png" alt="Support icon" width="40">
        <h3>Digital Support</h3>
        <p>Help desk services, staff technology training, and incident escalation across the public sector.</p>
      </div>
    </div>
  </div>
</section>

<section class="section-white">
  <div class="container">
    <div class="grid-2">
      <div>
        <span class="eyebrow-label">Our mandate</span>
        <h2>Why SecureGov exists</h2>
        <p>Government departments manage sensitive data and deliver services the public depends on daily. As these systems become more connected, they become more exposed. SecureGov was formed to centralise that responsibility — one agency, accountable for the security and reliability of technology behind public services.</p>
        <a href="about.php" class="btn btn-outline">Learn more about us</a>
      </div>
      <div>
        <img src="images/network-diagram.png" alt="Diagram of a secure government network" class="network-diagram">
      </div>
    </div>
  </div>
</section>

<section class="cta-band">
  <div class="container">
    <h2>Interested in joining our mission?</h2>
    <p>We are growing our cybersecurity, infrastructure, and support teams. See what is currently open.</p>
    <a href="jobs.php" class="btn btn-primary">View open roles</a>
  </div>
</section>

<?php require_once('footer.inc'); ?>