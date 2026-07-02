<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']); // check if user is logged in
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>WorkersHub - Dashboard</title>
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
    .dashboard-content {
      padding: 40px 20px;
      max-width: 1200px;
      margin: auto;
    }
    h1 {
      color: #00e676;
    }
  </style>
</head>
<body>

<header>
  <div class="brand">WorkersHub</div>
  <nav>
    <a href="index.php">Home</a>
    <?php if ($isLoggedIn): ?>
      <a href="user_dashboard.php">Dashboard</a>
      <a href="logout.php">Logout</a>
    <?php else: ?>
      <a href="login.php">Login</a>
      <a href="register.php">Sign Up</a>
    <?php endif; ?>
  </nav>
</header>


