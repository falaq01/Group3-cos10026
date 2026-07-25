<header class="site-header">
    <input type="checkbox" id="navigation-toggle-checkbox">
    <div class="nav-row">
      <a href="index.php" class="brand">
        <img src="images/logo.png" alt="SecureGov logo" width="30" height="30">
        <span>SecureGov<span class="org-type">IT &amp; Cybersecurity Agency</span></span>
      </a>
      <label for="navigation-toggle-checkbox" class="navigation-toggle-label">&#9776;</label>
      <nav class="main-nav">
        <a href="index.php" <?php echo ($currentPage == 'home') ? 'aria-current="page"' : ''; ?>>Home</a>
        <a href="jobs.php" <?php echo ($currentPage == 'jobs') ? 'aria-current="page"' : ''; ?>>Careers</a>
        <a href="about.php" <?php echo ($currentPage == 'about') ? 'aria-current="page"' : ''; ?>>About</a>
        <a href="apply.php" class="nav-cta" <?php echo ($currentPage == 'apply') ? 'aria-current="page"' : ''; ?>>Apply Now</a>
        <a href="login.php" <?php echo ($currentPage == 'login' || $currentPage == 'manage') ? 'aria-current="page"' : ''; ?>>Staff Login</a>
      </nav>
    </div>
  </header>