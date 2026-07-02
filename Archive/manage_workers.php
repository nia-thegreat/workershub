<?php
session_start();
include("db/db.php"); // ensure $conn is defined

// Fetch services for dropdown
$services = $conn->query("SELECT name FROM services");

// ADD WORKER
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_worker'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $service_type = $conn->real_escape_string($_POST['service_type']);
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']); // store plain text
    $availability = 'Available';

    $sql = "INSERT INTO workers (name, email, phone, service_type, availability, username, password)
            VALUES ('$name', '$email', '$phone', '$service_type', '$availability', '$username', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Worker added successfully!'); window.location.href='manage_workers.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}

// DELETE WORKER
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM workers WHERE id=$id");
    header("Location: manage_workers.php");
    exit();
}

// FETCH WORKERS
$result = $conn->query("SELECT * FROM workers");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Workers - WorkersHub</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #121212;
      color: #fff;
      margin: 0;
      padding: 20px;
    }
    h1 {
      color: #00e676;
      text-align: center;
    }
    .form-container, .table-container {
      background: #1e1e1e;
      padding: 20px;
      margin: 20px auto;
      border-radius: 10px;
      max-width: 600px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.4);
    }
    .form-container h2 {
      margin-bottom: 15px;
      color: #00e676;
    }
    input, select, button {
      padding: 10px;
      margin: 8px 0;
      border-radius: 5px;
      border: none;
      width: 100%;
      box-sizing: border-box;
    }
    input, select {
      background: #2a2a2a;
      color: #fff;
    }
    button {
      background: #00e676;
      color: #121212;
      font-weight: bold;
      cursor: pointer;
    }
    button:hover {
      background: #00c767;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }
    th, td {
      padding: 10px;
      text-align: left;
      border-bottom: 1px solid #2a2a2a;
    }
    th {
      background: #2a2a2a;
      color: #00e676;
    }
    a.delete-btn {
      color: #ff5252;
      text-decoration: none;
      font-weight: bold;
    }
    a.delete-btn:hover {
      text-decoration: underline;
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

  <h1>Manage Workers</h1>

  <div class="form-container">
    <h2>Add New Worker</h2>
    <form method="POST">
      <input type="text" name="name" placeholder="Full Name" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="text" name="phone" placeholder="Phone" required>
      
      <!-- Dropdown from services table -->
      <select name="service_type" required>
        <option value="">Select Service</option>
        <?php while($s = $services->fetch_assoc()): ?>
          <option value="<?php echo $s['name']; ?>">
            <?php echo $s['name']; ?>
          </option>
        <?php endwhile; ?>
      </select>

      <input type="text" name="username" placeholder="Username" required>
      <input type="text" name="password" placeholder="Password" required>
      <button type="submit" name="add_worker">Add Worker</button>
    </form>
  </div>

  <div class="table-container" style="max-width: 1000px;">
    <h2>Existing Workers</h2>
    <table>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Service Type</th>
        <th>Availability</th>
        <th>Username</th>
        <th>Password</th>
        <th>Action</th>
      </tr>
      <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo htmlspecialchars($row['name']); ?></td>
        <td><?php echo htmlspecialchars($row['email']); ?></td>
        <td><?php echo htmlspecialchars($row['phone']); ?></td>
        <td><?php echo htmlspecialchars($row['service_type']); ?></td>
        <td><?php echo $row['availability']; ?></td>
        <td><?php echo htmlspecialchars($row['username']); ?></td>
        <td><?php echo htmlspecialchars($row['password']); ?></td>
        <td><a class="delete-btn" href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a></td>
      </tr>
      <?php endwhile; ?>
    </table>
  </div>
</body>
</html>
