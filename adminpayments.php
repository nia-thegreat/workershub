<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}


include 'db/db.php';

// Fetch all payments with user info
$sql = "SELECT p.id, p.amount, p.status, p.created_at, u.name, e.description 
        FROM payments p
        JOIN users u ON p.user_id = u.id
        JOIN enquiries e ON p.enquiry_id = e.id
        ORDER BY p.created_at DESC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Payments</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #121212;
      color: #fff;
    }

    h2 {
      text-align: center;
      margin: 20px 0;
      color: #00e676;
    }

    table {
      width: 90%;
      margin: 20px auto;
      border-collapse: collapse;
      background: #1e1e1e;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 0 15px rgba(0, 255, 128, 0.3);
    }

    th, td {
      padding: 14px;
      border-bottom: 1px solid #333;
      text-align: left;
    }

    th {
      background: #00e676;
      color: #000;
    }

    tr:hover {
      background: rgba(0, 230, 118, 0.1);
    }
  </style>
</head>
<body>

<!-- Back Arrow -->
<div class="back-arrow" onclick="window.history.back()">
  <i class="fas fa-arrow-left"></i>
</div>

<!-- FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
.back-arrow {
  position: fixed;
  top: 20px;
  left: 20px;
  background-color: #111;
  color: #00ff88;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  box-shadow: 0 2px 5px rgba(0,0,0,0.4);
  transition: 0.3s ease;
  z-index: 999;
}

.back-arrow:hover {
  background-color: #00ff88;
  color: #111;
  transform: scale(1.05);
}
</style>


<h2>Payments Overview</h2>

<table>
  <tr>
    <th>ID</th>
    <th>User</th>
    <th>Enquiry</th>
    <th>Amount</th>
    <th>Status</th>
    <th>Date</th>
  </tr>
  <?php if ($result && $result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['id']) ?></td>
       <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['description']) ?></td>
        <td>₹<?= htmlspecialchars($row['amount']) ?></td>
        <td><?= htmlspecialchars($row['status']) ?></td>
        <td><?= htmlspecialchars($row['created_at']) ?></td>
      </tr>
    <?php endwhile; ?>
  <?php else: ?>
    <tr><td colspan="6" style="text-align:center;">No payments found</td></tr>
  <?php endif; ?>
</table>

</body>
</html>
