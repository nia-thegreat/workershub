<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'db/db.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT e.id, s.name AS service_name, e.description, e.preferred_date, e.status
        FROM enquiries e
        JOIN services s ON e.service_id = s.id
        WHERE e.user_id = ?
        ORDER BY e.id DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Enquiries - WorkersHub</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 20px;
    }

    h2 {
      text-align: center;
      color: #00b16a;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 30px;
      background: white;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    th, td {
      padding: 12px 15px;
      border-bottom: 1px solid #ccc;
      text-align: left;
    }

    th {
      background-color: #00b16a;
      color: white;
    }

    tr:hover {
      background-color: #f1f1f1;
    }

    .status {
      font-weight: bold;
      padding: 6px 10px;
      border-radius: 5px;
      display: inline-block;
    }

    .status.Pending {
      background-color: #ffcc00;
      color: #333;
    }

    .status.Assigned {
      background-color: #00c853;
      color: white;
    }

    .status.Completed {
      background-color: #2196F3;
      color: white;
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

  <h2>My Enquiries</h2>

  <table>
    <thead>
      <tr>
        <th>Service</th>
        <th>Description</th>
        <th>Preferred Date</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['service_name']) ?></td>
          <td><?= nl2br(htmlspecialchars($row['description'])) ?></td>
          <td><?= htmlspecialchars($row['preferred_date']) ?></td>
          <td><span class="status <?= htmlspecialchars($row['status']) ?>"><?= htmlspecialchars($row['status']) ?></span></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</body>
</html>
