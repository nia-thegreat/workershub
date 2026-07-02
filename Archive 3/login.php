<?php
session_start();
$error = "";

// Capture redirect target (if user was sent here via ?redirect=...)
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '';

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login'])) {
    include 'db/db.php';

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $redirect = isset($_POST['redirect']) ? $_POST['redirect'] : '';

    // Try to find user in 'users' table
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if ($password === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['role'] = $user['role'];

                // Redirect user
                if (!empty($redirect)) {
                    header("Location: " . $redirect);
                } elseif ($user['role'] === 'admin') {
                    header("Location: admin_dashboard.php");
                } elseif ($user['role'] === 'user') {
                    header("Location: user_dashboard.php");
                } else {
                    $error = "❌ Unknown user role.";
                }
                exit();
            } else {
                $error = "❌ Incorrect password.";
            }
        } else {
            // User not found in 'users', try 'workers'
            $workerStmt = $conn->prepare("SELECT * FROM workers WHERE email = ?");
            $workerStmt->bind_param("s", $email);

            if ($workerStmt->execute()) {
                $workerResult = $workerStmt->get_result();
                if ($workerResult->num_rows === 1) {
                    $worker = $workerResult->fetch_assoc();
                    if ($password === $worker['password']) {
                        $_SESSION['user_id'] = $worker['id'];
                        $_SESSION['user_name'] = $worker['name'];
                        $_SESSION['role'] = 'worker';

                        if (!empty($redirect)) {
                            header("Location: " . $redirect);
                        } else {
                            header("Location: worker_dashboard.php");
                        }
                        exit();
                    } else {
                        $error = "❌ Incorrect password.";
                    }
                } else {
                    $error = "❌ No account found with that email.";
                }
            } else {
                $error = "❌ Something went wrong while checking workers.";
            }
            $workerStmt->close();
        }
    } else {
        $error = "❌ Something went wrong. Please try again.";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>WorkersHub - Log In</title>
  <link rel="stylesheet" href="css/style.css" />
  <style>
    body {
      background-color: #0a0a0a;
      color: #fff;
      font-family: 'Poppins', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .form-container {
      background: #1b1b1b;
      padding: 40px;
      border-radius: 12px;
      text-align: center;
      width: 350px;
      box-shadow: 0 0 10px rgba(0,0,0,0.5);
    }
    h1 {
      color: #00ff88;
      font-size: 28px;
      margin-bottom: 5px;
    }
    .subtitle {
      color: #aaa;
      margin-bottom: 25px;
    }
    input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: none;
      border-radius: 6px;
      background: #2b2b2b;
      color: #fff;
      font-size: 15px;
    }
    button {
      width: 100%;
      padding: 12px;
      background-color: #00ff88;
      border: none;
      border-radius: 6px;
      color: #000;
      font-weight: bold;
      font-size: 15px;
      cursor: pointer;
      margin-top: 10px;
    }
    button:hover {
      background-color: #00cc6a;
    }
    .login-link {
      margin-top: 20px;
      color: #aaa;
    }
    .login-link a {
      color: #00ff88;
      text-decoration: none;
    }
    .error-message {
      background-color: #ff4d4d;
      color: #fff;
      padding: 10px;
      border-radius: 6px;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h1>WorkersHub</h1>
    <p class="subtitle">Log In</p>

    <?php if (!empty($error)): ?>
      <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">
      <input type="email" name="email" placeholder="Email" required /><br>
      <input type="password" name="password" placeholder="Password" required /><br>
      <button type="submit" name="login">Log in to WorkersHub</button>
    </form>

    <p class="login-link">Don’t have an account? <a href="register.php">Sign up</a></p>
  </div>
</body>
</html>
