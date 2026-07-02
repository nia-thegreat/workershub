<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
include 'db/db.php';

// Assign worker
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['assign_worker'])) {
    $enquiry_id = $_POST['enquiry_id'];
    $worker_id = $_POST['worker_id'];

    $assignSql = "UPDATE enquiries SET assigned_worker_id = ?, status = 'In Progress' WHERE id = ?";
    $assignStmt = $conn->prepare($assignSql);
    $assignStmt->bind_param("ii", $worker_id, $enquiry_id);
    $assignStmt->execute();

    // Update worker status to busy
    $conn->query("UPDATE workers SET availability = 'Unavailable' WHERE id = $worker_id");
}

// Update status
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_status'])) {
    $enquiry_id = $_POST['enquiry_id'];
    $status = $_POST['status'];

    $updateSql = "UPDATE enquiries SET status = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("si", $status, $enquiry_id);
    $updateStmt->execute();

    // If completed, mark worker available again
    if ($status === 'Completed') {
        $worker_id_res = $conn->query("SELECT assigned_worker_id FROM enquiries WHERE id = $enquiry_id")->fetch_assoc();
        if ($worker_id_res && $worker_id_res['assigned_worker_id']) {
            $conn->query("UPDATE workers SET availability = 'Available' WHERE id = " . $worker_id_res['assigned_worker_id']);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - WorkersHub</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            background-color: #222;
        }

        th, td {
            padding: 12px;
            border: 1px solid #333;
        }

        th {
            background-color: #00b16a;
            color: #fff;
        }

        select, button {
            padding: 6px;
            border-radius: 5px;
            font-size: 14px;
        }

        button {
            background-color: #00e676;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #00c66c;
        }

        form {
            margin: 0;
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


<h1>Admin Dashboard</h1>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Service</th>
            <th>Location</th>
            <th>Details</th>
            <th>Date</th>
            <th>Assigned Worker</th>
            <th>Status</th>
            <th>Assign</th>
            <th>Update Status</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $sql = "SELECT e.*, s.name AS service_name, w.name AS worker_name
            FROM enquiries e
            JOIN services s ON e.service_id = s.id
            LEFT JOIN workers w ON e.assigned_worker_id = w.id";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()):
        $serviceName = $row['service_name'];

        // Fetch available workers of this type, in the same location, not currently assigned to any 'In Progress' task
$availableWorkersStmt = $conn->prepare("
    SELECT id, name FROM workers 
    WHERE service_type = ? 
    AND availability = 'Available' 
    AND id NOT IN (
        SELECT assigned_worker_id FROM enquiries 
        WHERE status = 'In Progress' AND assigned_worker_id IS NOT NULL
    )
");
$availableWorkersStmt->bind_param("s", $serviceName);
$availableWorkersStmt->execute();
$workersResult = $availableWorkersStmt->get_result();


    
    ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['user_id'] ?></td>
            <td><?= htmlspecialchars($serviceName) ?></td>
            <td><?= htmlspecialchars($row['location']) ?></td>
            <td><?= htmlspecialchars($row['details']) ?></td>
            <td><?= $row['preferred_date'] ?></td>
            <td><?= $row['worker_name'] ?? 'Not Assigned' ?></td>
            <td><?= $row['status'] ?></td>
            <td>
                <?php if (!$row['assigned_worker_id']): ?>
                <form method="POST">
                    <input type="hidden" name="enquiry_id" value="<?= $row['id'] ?>">
                    <select name="worker_id" required>
                        <option value="">--Select--</option>
                        <?php while ($w = $workersResult->fetch_assoc()): ?>
                            <option value="<?= $w['id'] ?>"><?= htmlspecialchars($w['name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                    <button type="submit" name="assign_worker">Assign</button>
                </form>
                <?php else: ?>
                    Assigned
                <?php endif; ?>
            </td>
            <td>
                <form method="POST">
                    <input type="hidden" name="enquiry_id" value="<?= $row['id'] ?>">
                    <select name="status">
                        <option value="Pending" <?= $row['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="In Progress" <?= $row['status'] === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                        <option value="Completed" <?= $row['status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
                    </select>
                    <button type="submit" name="update_status">Update</button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
