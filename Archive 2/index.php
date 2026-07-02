<?php
session_start();
$isLoggedIn = isset($_SESSION['user_name']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>WorkersHub - Home</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #121212;
      color: #ffffff;
    }
    header {
      background-color: #1e1e1e;
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #2a2a2a;
    }
    .brand {
      font-size: 24px;
      font-weight: bold;
      color: #00e676;
    }
    nav a {
      color: #ddd;
      text-decoration: none;
      margin-left: 20px;
      transition: color 0.2s ease;
    }
    nav a:hover {
      color: #00e676;
    }
    .hero {
      text-align: center;
      padding: 60px 20px;
    }
    .hero h1 {
      font-size: 36px;
      margin-bottom: 15px;
      color: #00e676;
    }
    .hero p {
      font-size: 18px;
      color: #ccc;
      margin-bottom: 30px;
    }
    .btn {
      background-color: #00e676;
      color: #121212;
      padding: 12px 25px;
      font-size: 16px;
      border: none;
      border-radius: 5px;
      text-decoration: none;
      cursor: pointer;
    }
    .services, .how-it-works {
      padding: 40px 20px;
      max-width: 1200px;
      margin: auto;
    }
    .section-title {
      text-align: center;
      font-size: 28px;
      color: #00e676;
      margin-bottom: 30px;
    }
    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
    }
    .card {
      background-color: #1e1e1e;
      padding: 20px;
      border-radius: 10px;
      text-align: center;
      border: 1px solid #2a2a2a;
      transition: transform 0.2s ease;
    }
    .card:hover {
      transform: translateY(-4px);
    }
    .card i {
      font-size: 28px;
      color: #00e676;
      margin-bottom: 10px;
    }
    .card h3 {
      margin: 10px 0;
      color: #fff;
    }
    .card p {
      color: #ccc;
      font-size: 14px;
    }
    footer {
      background-color: #1e1e1e;
      text-align: center;
      padding: 15px;
      color: #ccc;
      margin-top: 30px;
      border-top: 1px solid #2a2a2a;
    }
  </style>
</head>
<body>

<header>
  <div class="brand">WorkersHub</div>
  <nav>
    
    <?php if ($isLoggedIn): ?>
      <a href="user_dashboard.php">Dashboard</a>
      <a href="logout.php">Logout</a>
    <?php else: ?>
      <a href="login.php">Login</a>
      <a href="register.php">Sign Up</a>
    <?php endif; ?>
  </nav>
</header>

<section class="hero">
  <h1>Find Trusted Professionals for Any Job</h1>
  <p>From home repairs to skilled services, WorkersHub connects you with the right experts.</p>
  <?php if ($isLoggedIn): ?>
    <a href="user_dashboard.php" class="btn">Go to Dashboard</a>
  <?php else: ?>
    <a href="register.php" class="btn">Get Started</a>
  <?php endif; ?>
</section>


<section class="services">
  <h2 class="section-title">Our Services</h2>
  <div class="cards">
    <?php
      $services = [
        ["Plumbing", "fas fa-wrench", "Fix leaks, install pipes, and more."],
        ["Electrical", "fas fa-bolt", "Professional electrical repairs and installations."],
        ["Painting", "fas fa-paint-roller", "Interior and exterior painting services."],
        ["Cleaning", "fas fa-broom", "Home and office cleaning services."],
        ["AC Repair", "fas fa-snowflake", "Cooling system repair and maintenance."],
        ["Carpentry", "fas fa-hammer", "Custom furniture and woodwork."],
        ["Welding", "fas fa-tools", "Metal fabrication and welding services."],
        ["Furniture Assembly", "fas fa-couch", "Quick and reliable furniture setup."],
        ["Gardening", "fas fa-leaf", "Lawn care, landscaping, and more."],
        ["Bathroom Renovation", "fas fa-shower", "Upgrade and modernize your bathroom."]
      ];

      foreach ($services as $service) {
        $serviceName = urlencode($service[0]);
        $icon = $service[1];
        $desc = $service[2];

        if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'user') {
          $link = "enquiry.php?service=$serviceName";
        } else {
          $link = "login.php?redirect=enquiry.php?service=$serviceName";
        }

        echo "
          <div class='card' onclick=\"window.location.href='$link'\">
            <i class='$icon'></i>
            <h3>{$service[0]}</h3>
            <p>$desc</p>
          </div>
        ";
      }
    ?>
  </div>
</section>




<section class="how-it-works">
  <h2 class="section-title">How It Works</h2>
  <div class="cards">
    <div class="card"><i class="fas fa-edit"></i><h3>Post Your Job</h3><p>Describe the service you need in detail.</p></div>
    <div class="card"><i class="fas fa-user-check"></i><h3>Choose a Professional</h3><p>Select from vetted workers for your task.</p></div>
    <div class="card"><i class="fas fa-handshake"></i><h3>Get It Done</h3><p>Relax while your professional completes the job.</p></div>
  </div>
</section>

<footer>
  &copy; <?php echo date('Y'); ?> WorkersHub. All Rights Reserved.
</footer>

</body>
</html>
