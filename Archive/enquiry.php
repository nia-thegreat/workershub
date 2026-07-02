<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);




session_start();

include 'db/db.php';

$success = "";
$error = "";

// Fetch services for dropdown
$services = [];
$service_query = "SELECT id, name FROM services";
$service_result = $conn->query($service_query);
if ($service_result && $service_result->num_rows > 0) {
    while ($row = $service_result->fetch_assoc()) {
        $services[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit_enquiry'])) {
    // If user not logged in, save form data in session and redirect to login
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['pending_enquiry'] = [
            'service_id' => $_POST['service_id'],
            'description' => $_POST['description'],
            'location' => $_POST['location'],
            'preferred_date' => $_POST['preferred_date']
        ];
        header('Location: login.php');
        exit();
    }

    // Otherwise, process enquiry normally
    $user_id = $_SESSION['user_id'];
    $service_id = intval($_POST['service_id']);
    $description = trim($_POST['description']);
    $location = trim($_POST['location']);
    $preferred_date = trim($_POST['preferred_date']);

    $sql = "INSERT INTO enquiries (user_id, service_id, description, location, preferred_date) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisss", $user_id, $service_id, $description, $location, $preferred_date);

    if ($stmt->execute()) {
        $enquiry_id = $conn->insert_id;
        $amount = 500.0;
        $status = 'Pending';

        $conn->query("INSERT INTO payments (user_id, enquiry_id, amount, status) 
                      VALUES ('$user_id', '$enquiry_id', '$amount', '$status')");

        header("Location: user_dashboard.php");
        exit();
    } else {
        $error = "❌ Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Submit Enquiry - WorkersHub</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #000;
      color: #fff;
    }

    .enquiry-form {
      max-width: 600px;
      margin: 80px auto;
      background: rgba(0, 0, 0, 0.6);
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0, 255, 128, 0.4); /* green glow */
      backdrop-filter: blur(10px);
    }

    .enquiry-form h2 {
      margin-bottom: 25px;
      color: #00e676;
      text-align: center;
    }

    .enquiry-form label {
      display: block;
      margin-bottom: 6px;
      font-size: 15px;
      color: #ddd;
    }

    .enquiry-form input,
    .enquiry-form select,
    .enquiry-form textarea {
      width: 100%;
      padding: 12px;
      margin-bottom: 20px;
      border: none;
      border-radius: 6px;
      font-size: 15px;
      background-color: rgba(255, 255, 255, 0.9);
      color: #000;
    }

    .enquiry-form input::placeholder,
    .enquiry-form textarea::placeholder {
      color: #555;
    }

    .enquiry-form button {
      background-color: #00e676;
      color: #000;
      border: none;
      padding: 12px;
      font-size: 16px;
      border-radius: 6px;
      cursor: pointer;
      width: 100%;
      font-weight: bold;
      transition: 0.3s ease;
    }

    .enquiry-form button:hover {
      background-color: #00c66c;
    }

    .message {
      margin-bottom: 20px;
      font-size: 15px;
      text-align: center;
    }

    .message.error {
      color: red;
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


<div class="enquiry-form">
  <h2>Submit Enquiry</h2>

  <?php if ($error): ?>
    <div class="message error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST" action="">
    <label for="service_id">Service</label>
    <select name="service_id" id="service_id" required>
      <option value="">-- Select Service --</option>
      <?php foreach ($services as $service): ?>
        <option value="<?= $service['id'] ?>"><?= htmlspecialchars($service['name']) ?></option>
      <?php endforeach; ?>
    </select>

    <label for="location">Your Location</label>
    <input type="text" name="location" id="location" placeholder="e.g. Edapally, near Metro" required>

    <label for="description">Description</label>
    <textarea name="description" id="description" rows="4" placeholder="Describe your request..." required></textarea>

    <label for="preferred_date">Preferred Date</label>
    <input type="date" name="preferred_date" id="preferred_date" required>

    <button type="submit" name="submit_enquiry">Submit Enquiry</button>
  </form>
</div>

</body>
</html>
