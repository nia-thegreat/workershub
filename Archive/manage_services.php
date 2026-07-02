<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
include 'db/db.php';

// Add Service
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_service'])) {
    $name = $_POST['name'];

    $stmt = $conn->prepare("INSERT INTO services (name) VALUES (?)");
    $stmt->bind_param("s", $name);
    $stmt->execute();
}

// Delete Service
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_service'])) {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM services WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Fetch Services
$services = $conn->query("SELECT * FROM services ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Services - WorkersHub</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #111;
            color: #eee;
            padding: 40px;
        }

        h1 {
            color: #00e676;
        }

        form {
            margin-bottom: 20px;
        }

        input, button {
            padding: 8px;
            margin: 5px;
            border-radius: 5px;
            border: none;
            font-size: 14px;
        }

        button {
            background-color: #00e676;
            cursor: pointer;
        }

        button:hover {
            background-color: #00c66c;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #222;
            margin-top: 30px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #333;
        }

        th {
            background-color: #00b16a;
            color: #fff;
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


<h1>Manage Services</h1>

<!-- Add Service Form -->
<form method="POST">
    <input type="text" name="name" placeholder="Service Name" required>
    <button type="submit" name="add_service">Add Service</button>
</form>

<!-- Services Table -->
<table>
    <tr>
        <th>ID</th>
        <th>Service Name</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $services->fetch_assoc()): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td>
            <form method="POST" style="display:inline">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit" name="delete_service">Delete</button>
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>

