<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="SecureGov — National IT and Cybersecurity Services Agency">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles/style.css">
  <title><?php echo $pageTitle ?? 'SecureGov'; ?></title>
</head>
<body>

  <div class="top-bar">
    <div class="container">
      <span>All systems operational</span>
      <span>14 departments protected</span>
      <span>24/7 monitoring</span>
      <span>Avg. incident response: 11 min</span>
    </div>
  </div>
   <header class="site-header">
    <input type="checkbox" id="navigation-toggle-checkbox">
    <div class="nav-row">
      <a href="index.php" class="brand">
        <img src="images/logo.png" alt="SecureGov logo" width="30" height="30">
        <span>SecureGov<span class="org-type">IT &amp; Cybersecurity Agency</span></span>
      </a>
      <label for="navigation-toggle-checkbox" class="navigation-toggle-label">&#9776;</label>
      <?php include 'nav.inc'; ?>
    </div>
  </header>