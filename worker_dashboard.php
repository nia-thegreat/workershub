<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'worker') {
    header("Location: login.php");
    exit();
}

include 'db/db.php';

$worker_id = $_SESSION['user_id'];

// Fetch worker name
$workerRes = $conn->query("SELECT name FROM workers WHERE id = $worker_id");
$workerName = $workerRes->fetch_assoc()['name'] ?? 'Worker';

// Handle task completion
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['complete_task'])) {
    $enquiry_id = $_POST['enquiry_id'];

    // Mark task completed
    $conn->query("UPDATE enquiries SET status = 'Completed' WHERE id = $enquiry_id");

    // Make worker available again
    $conn->query("UPDATE workers SET availability = 'Available' WHERE id = $worker_id");
}

// Fetch assigned tasks
$sql = "SELECT e.*, s.name AS service_name FROM enquiries e 
        JOIN services s ON e.service_id = s.id 
        WHERE e.assigned_worker_id = $worker_id";
$tasks = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Worker Dashboard - WorkersHub</title>
    <style>
        body {
            background-color: #111;
            color: #eee;
            font-family: 'Segoe UI', sans-serif;
            padding: 40px;
        }
        h1 {
            color: #00e676;
        }
        table {
            width: 100%;
            background-color: #222;
            border-collapse: collapse;
            margin-top: 30px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #333;
        }
        th {
            background-color: #00b16a;
            color: white;
        }
        button {
            background-color: #00e676;
            border: none;
            padding: 6px 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: #00c66c;
        }
        .logout {
            color: #ccc;
            float: right;
            text-decoration: none;
        }
    </style>
</head>
<body>

<a class="logout" href="logout.php">Logout</a>
<h1>Welcome, <?= htmlspecialchars($workerName) ?>!</h1>
<h3>Your Assigned Tasks</h3>

<table>
    <thead>
        <tr>
            <th>Enquiry ID</th>
            <th>User ID</th>
            <th>Service</th>
            <th>Location</th>
            <th>Details</th>
            <th>Preferred Date</th>
            <th>Status</th>
            <th>Complete</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($tasks->num_rows > 0): ?>
            <?php while ($row = $tasks->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['user_id'] ?></td>
                    <td><?= htmlspecialchars($row['service_name']) ?></td>
                    <td><?= htmlspecialchars($row['location']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td><?= $row['preferred_date'] ?></td>
                    <td><?= $row['status'] ?></td>
                    <td>
                        <?php if ($row['status'] !== 'Completed'): ?>
                            <form method="POST">
                                <input type="hidden" name="enquiry_id" value="<?= $row['id'] ?>">
                                <button type="submit" name="complete_task">Mark Completed</button>
                            </form>
                        <?php else: ?>
                            ✅
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="8">No tasks assigned.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
