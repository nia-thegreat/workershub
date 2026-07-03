<?php
$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['register'])) {
    include 'db/db.php';

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sss", $name, $email, $password);

        if ($stmt->execute()) {
            $success = "✅ Registration successful!";
        } else {
            $error = "❌ Error: " . htmlspecialchars($stmt->error);
        }

        $stmt->close();
    } else {
        $error = "❌ SQL prepare failed: " . htmlspecialchars($conn->error);
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>WorkersHub - Sign Up</title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
  <div class="form-container">
    <h1>WorkersHub</h1>
    <p class="subtitle">Sign Up</p>

    <?php if (!empty($success)): ?>
      <div class="success-message"><?= htmlspecialchars($success) ?></div>
    <?php elseif (!empty($error)): ?>
      <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <input type="text" name="name" placeholder="Name" required /><br><br>
      <input type="email" name="email" placeholder="Email" required /><br><br>
      <input type="password" name="password" placeholder="Password" required /><br><br>
      <button type="submit" name="register">Join WorkersHub</button>
    </form>
    <p class="login-link">Already have an account? <a href="login.php">Log in</a></p>
  </div>
</body>
</html>
