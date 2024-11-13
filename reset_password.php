<?php
session_start();

// Database connection code here

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Verify the token and expiration
    $sql = "SELECT * FROM users WHERE reset_token='$token' AND reset_expires > " . date("U");
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Token is valid
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            // Update password
            $sql = "UPDATE users SET password='$new_password', reset_token=NULL, reset_expires=NULL WHERE reset_token='$token'";
            $conn->query($sql);

            $_SESSION['success_message'] = "Password reset successfully.";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['error_message'] = "Invalid or expired token.";
        header("Location: login.php");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>
<body>
    <h1>Reset Password</h1>
    <?php if (isset($_SESSION['error_message'])): ?>
        <p><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></p>
    <?php endif; ?>
    <form action="" method="POST">
        <label for="password">New Password:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
