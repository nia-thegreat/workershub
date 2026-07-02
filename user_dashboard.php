<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$userName = $_SESSION['user_name'];
$isLoggedIn = true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>WorkersHub - User Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #121212; /* dark background */
      color: #f9f9f9;
      display: flex;
      flex-direction: column;
      height: 100vh;
    }

    /* Header */
    header {
      background-color: #1e1e1e;
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #2a2a2a;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 1000;
      box-sizing: border-box;
    }

    .brand {
      font-size: 22px;
      font-weight: bold;
      color: #00e676;
    }

    nav {
      display: flex;
      align-items: center;
      gap: 20px;
    }

    nav a {
      color: #ddd;
      text-decoration: none;
      transition: color 0.2s ease;
      white-space: nowrap;
    }
    nav a:hover {
      color: #00e676;
    }

    /* Layout wrapper */
    .dashboard-wrapper {
      display: flex;
      flex: 1;
      margin-top: 70px; /* push below header */
    }

    /* Sidebar */
    .sidebar {
      background-color: #1e1e1e;
      width: 220px;
      padding: 30px 20px;
      display: flex;
      flex-direction: column;
      border-right: 1px solid #2a2a2a;
      box-sizing: border-box;
    }

    .menu-item {
      display: flex;
      align-items: center;
      padding: 10px 0;
      color: #ddd;
      text-decoration: none;
      font-size: 16px;
      transition: 0.2s ease;
    }

    .menu-item i {
      margin-right: 10px;
      color: #00e676;
    }

    .menu-item:hover,
    .menu-item.active {
      color: #00e676;
      font-weight: bold;
    }

    /* Main content */
    .main-content {
      flex-grow: 1;
      padding: 40px;
      background-color: #121212;
      color: #f9f9f9;
    }

    .main-content h1 {
      margin-top: 0;
      font-size: 26px;
      color: #00e676;
      padding-bottom: 20px;
      margin-bottom: 30px;
      border-bottom: 1px solid #2a2a2a;
    }

    .action-cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-top: 40px;
    }

    .card {
      background-color: #1e1e1e;
      border: 1px solid #2a2a2a;
      border-radius: 10px;
      padding: 30px;
      text-align: center;
      transition: 0.2s ease;
      cursor: pointer;
    }

    .card:hover {
      transform: translateY(-4px);
      box-shadow: 0 4px 10px rgba(0, 230, 118, 0.2);
    }

    .card i {
      font-size: 24px;
      color: #00e676;
      margin-bottom: 10px;
    }

    .card h3 {
      margin: 10px 0 0;
      font-size: 18px;
      color: #f9f9f9;
    }
  </style>
</head>
<body>

<!-- Header -->
<header>
  <div class="brand">WorkersHub</div>
  <nav>
    <a href="index.php">Home</a>
    <?php if ($isLoggedIn): ?>
      
      <a href="logout.php">Logout</a>
    <?php else: ?>
      <a href="login.php">Login</a>
      <a href="register.php">Sign Up</a>
    <?php endif; ?>
  </nav>
</header>

<div class="dashboard-wrapper">
  <!-- Sidebar -->
  <aside class="sidebar">
    <a href="index.php" class="menu-item active"><i class="fas fa-home"></i> Home</a>
    <a href="enquiry.php" class="menu-item"><i class="fas fa-envelope"></i> Submit Enquiry</a>
    <a href="myenquiries.php" class="menu-item"><i class="fas fa-folder-open"></i> My Enquiries</a>
    <a href="mypayments.php" class="menu-item"><i class="fas fa-credit-card"></i> Payment</a>
    <a href="logout.php" class="menu-item"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </aside>

  <!-- Main Content -->
  <main class="main-content">
    <h1>Welcome back, <?php echo htmlspecialchars($userName); ?>!</h1>

    <div class="action-cards">
      <div class="card" onclick="window.location.href='enquiry.php'">
        <i class="fas fa-envelope"></i>
        <h3>Submit Enquiry</h3>
      </div>
  
      <div class="card" onclick="window.location.href='myenquiries.php'">
        <i class="fas fa-folder-open"></i>
        <h3>My Enquiries</h3>
      </div>
    </div>
  </main>
</div>

</body>
</html>
