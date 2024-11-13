<?php
session_start();

// MySQL configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "home_db1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize message variables
$message = "";
$error = "";

// Handle password reset
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($email) || empty($new_password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if the email exists in the database
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Email exists, update the password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
            $update_stmt->bind_param("ss", $hashed_password, $email);

            if ($update_stmt->execute()) {
                $message = "Password successfully updated.";
            } else {
                $error = "Failed to update password. Please try again.";
            }

            $update_stmt->close();
        } else {
            $error = "No account found with that email address.";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Real Estate Website</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }
        nav {
            display: flex;
            justify-content: center; /* Center the buttons */
            margin-top: 10px;
        }
        .nav-btn {
            margin: 0 5px; /* Adjust margin for spacing between buttons */
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            background-color: #555;
            color: #fff;
            cursor: pointer;
            font-size: 16px; /* Ensure font size matches home.php */
            width: 150px; /* Reduce button width */
            text-align: center;
        }
        .nav-btn:hover {
            background-color: #444;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            margin: 40px auto;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
        }
        input[type="email"], input[type="password"], button {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #333;
            color: #fff;
            border: none;
            cursor: pointer;
            padding: 10px;
        }
        button:hover {
            background-color: #555;
        }
        .message {
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <header>
        <h1>Real Estate Website</h1>
        <nav>
            <button class="nav-btn" onclick="location.href='home.php'">Home</button>
            <?php if (isset($_SESSION['user_email'])): ?>
                <form class="logout-form" action="home.php" method="post" style="display:inline;">
                    <button class="nav-btn" type="submit" name="logout">Logout</button>
                </form>
            <?php else: ?>
                <button class="nav-btn" onclick="location.href='login.php'">Login / Register</button>
            <?php endif; ?>
            <!-- <button class="nav-btn" onclick="location.href='search1.php'">Search</button>
            <button class="nav-btn" onclick="location.href='upload.php'">Upload Property</button> -->
        </nav>
    </header>

    <div class="container">
        <h2>Reset Your Password</h2>
        
        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="email" name="email" placeholder="Enter your email" required>
            <input type="password" name="new_password" placeholder="Enter new password" required>
            <input type="password" name="confirm_password" placeholder="Confirm new password" required>
            <button type="submit">Update Password</button>
        </form>
    </div>
</body>
</html>
