<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}
include 'db/db.php';

$user_id = $_SESSION['user_id'];

// Handle payment (simulated "Pay Now")
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['pay_now'])) {
    $payment_id = $_POST['payment_id'];
    $conn->query("UPDATE payments SET status='Paid' WHERE id=$payment_id AND user_id=$user_id");
}

$sql = "SELECT p.*, e.description AS enquiry_desc
        FROM payments p
        JOIN enquiries e ON p.enquiry_id = e.id
        WHERE p.user_id = ?
        ORDER BY FIELD(p.status, 'Pending','Paid'), p.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Payments - WorkersHub</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #111; color: #eee; padding: 40px; }
        h1 { color: #00e676; }
        table { width: 100%; border-collapse: collapse; margin-top: 30px; background: #222; }
        th, td { padding: 12px; border: 1px solid #333; }
        th { background: #00b16a; color: #fff; }
        button { padding: 6px 12px; border: none; border-radius: 5px; cursor: pointer; background: #00e676; }
        button:hover { background: #00c66c; }
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

    <h1>My Payments</h1>
    <table>
        <thead>
            <tr>
                <th>Payment ID</th>
                <th>Enquiry</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['enquiry_desc']) ?></td>
                <td><?= $row['amount'] ?: 'N/A' ?></td>
                <td><?= $row['status'] ?></td>
                <td><?= $row['created_at'] ?></td>
                <td>
                    <?php if ($row['status'] === 'Pending'): ?>
                        <form method="POST">
                            <input type="hidden" name="payment_id" value="<?= $row['id'] ?>">
                            <button type="submit" name="pay_now">Pay Now</button>
                        </form>
                    <?php else: ?>
                        Paid
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
