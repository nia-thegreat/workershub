<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>WorkersHub - Sign Up</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="form-section">
            <h1>WorkersHub</h1>
            <p class="subtitle">Sign Up</p>

            <form method="POST" action="">
                <input type="text" name="name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>

                <button type="submit" name="register">Join WorkersHub</button>

                <p class="login-link">
                    Already have an account? <a href="login.php">Log in</a>
                </p>
            </form>
        </div>

        <div class="image-section">
            <img src="images/your-image.jpg" alt="WorkersHub" />
        </div>
    </div>
</body>
</html>
