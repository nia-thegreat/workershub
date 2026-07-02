<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
include 'db/db.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - View Enquiries</title>
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

        /* ✅ Status color coding */
        .status-pending {
            color: #ff5252; /* red */
            font-weight: bold;
        }
        .status-inprogress {
            color: #ffeb3b; /* yellow */
            font-weight: bold;
        }
        .status-completed {
            color: #00e676; /* green */
            font-weight: bold;
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


<h1>All Enquiries</h1>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Service</th>
            <th>Location</th>
            <th>Description</th>
            <th>Date</th>
            <th>Assigned Worker</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    <?php
    // ✅ Order so that Pending/In Progress appear first, Completed goes to bottom
    $sql = "SELECT e.*, s.name AS service_name, w.name AS worker_name
            FROM enquiries e
            JOIN services s ON e.service_id = s.id
            LEFT JOIN workers w ON e.assigned_worker_id = w.id
            ORDER BY 
                CASE 
                    WHEN e.status = 'Pending' THEN 1
                    WHEN e.status = 'In Progress' THEN 2
                    WHEN e.status = 'Completed' THEN 3
                END, e.id ASC";

    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()):
        $statusClass = '';
        if ($row['status'] === 'Pending') $statusClass = 'status-pending';
        elseif ($row['status'] === 'In Progress') $statusClass = 'status-inprogress';
        elseif ($row['status'] === 'Completed') $statusClass = 'status-completed';
    ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['user_id'] ?></td>
            <td><?= htmlspecialchars($row['service_name']) ?></td>
            <td><?= htmlspecialchars($row['location']) ?></td>
            <td><?= htmlspecialchars($row['description']) ?></td>
            <td><?= $row['preferred_date'] ?></td>
            <td><?= $row['worker_name'] ?? 'Not Assigned' ?></td>
            <td class="<?= $statusClass ?>"><?= $row['status'] ?></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
