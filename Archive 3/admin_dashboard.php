<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>WorkersHub - Admin Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #121212;
      color: #ffffff;
      display: flex;
      height: 100vh;
    }

    .sidebar {
      background-color: #1e1e1e;
      width: 220px;
      padding: 30px 20px;
      border-right: 1px solid #2a2a2a;
      display: flex;
      flex-direction: column;
    }

    .brand {
      font-size: 22px;
      font-weight: bold;
      color: #00e676;
      margin-bottom: 40px;
    }

    .menu-item {
      display: flex;
      align-items: center;
      padding: 10px 0;
      color: #ddd;
      text-decoration: none;
      font-size: 16px;
      transition: color 0.2s ease;
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

    .main-content {
      flex-grow: 1;
      padding: 40px;
      background-color: #121212;
    }

    .main-content h1 {
      margin-top: 0;
      font-size: 26px;
      color: #00e676;
      padding-bottom: 10px;
      margin-bottom: 30px;
      border-bottom: 1px solid #2a2a2a;
    }

    .admin-actions {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-top: 100px;
    }

    .card {
      background-color: #1e1e1e;
      border: 1px solid #2a2a2a;
      border-radius: 10px;
      padding: 30px;
      text-align: center;
      box-shadow: 0 2px 6px rgba(0,0,0,0.4);
      transition: 0.2s ease;
    }

    .card:hover {
      transform: translateY(-4px);
      box-shadow: 0 4px 10px rgba(0,0,0,0.6);
    }

    .card i {
      font-size: 24px;
      color: #00e676;
      margin-bottom: 10px;
    }

    .card h3 {
      margin: 10px 0 0;
      font-size: 18px;
      color: #fff;
    }
  </style>
</head>
<body>
  <aside class="sidebar">
    <div class="brand">WorkersHub</div>
    <a href="admin_dashboard.php" class="menu-item active"><i class="fas fa-home"></i> Dashboard</a>
    <a href="manage_workers.php" class="menu-item"><i class="fas fa-users"></i> Manage Workers</a>
    <a href="adminenquiry.php" class="menu-item"><i class="fas fa-clipboard-list"></i> View Enquiries</a>
    <a href="assignworker.php" class="menu-item"><i class="fas fa-user-cog"></i> Assign Workers</a>
    <a href="manage_services.php" class="menu-item"><i class="fas fa-tools"></i> Manage Services</a>
    <a href="adminpayments.php" class="menu-item"><i class="fas fa-credit-card"></i> Payment</a>
    <a href="logout.php" class="menu-item"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </aside>

  <main class="main-content">
    <h1>Welcome Admin</h1>

    <div class="admin-actions">
      <div class="card" onclick="window.location.href='manage_workers.php'">
        <i class="fas fa-users"></i>
        <h3>Manage Workers</h3>
      </div>
      <div class="card" onclick="window.location.href='adminenquiry.php'">
        <i class="fas fa-clipboard-list"></i>
        <h3>View Enquiries</h3>
      </div>
      <div class="card" onclick="window.location.href='assignworker.php'">
        <i class="fas fa-user-cog"></i>
        <h3>Assign Workers</h3>
      </div>
    </div>
  </main>
</body>
</html>
