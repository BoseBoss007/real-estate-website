<?php
session_start();

// Establish database connection
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$database = "home_db1"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input fields
    $email = $_POST['email'];
    $password = $_POST['password'];

    // SQL query to check if user with given email exists
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User found, verify password
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Password correct, set session variables and redirect to home.php
            $_SESSION['user_email'] = $email;
            $_SESSION['user_name'] = $row['name']; // Store user's name in session
            header("Location: home.php");
            exit();
        } else {
            // Password incorrect
            $_SESSION['error_message'] = "Invalid email or password.";
            header("Location: login.php");
            exit();
        }
    } else {
        // User not found
        $_SESSION['error_message'] = "Invalid email or password.";
        header("Location: login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Real Estate Website</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }
        nav {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }
        nav button {
            margin: 0 10px;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            background-color: #555;
            color: #fff;
            cursor: pointer;
        }
        nav button:hover {
            background-color: #444;
        }
        main {
            padding: 20px;
            text-align: center;
        }
        h1 {
            margin-bottom: 20px;
            color: #FFFFFF;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            max-width: 300px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        label {
            margin-bottom: 8px;
            color: #333;
        }
        input {
            margin-bottom: 16px;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
            width: 100%;
        }
        button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            background-color: #4caf50;
            color: #fff;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .link {
            text-decoration: none;
            color: #4caf50;
            font-size: 14px;
        }
        .link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <h1>Login</h1>
        <nav>
            <button onclick="location.href='home.php'">Home</button>
        </nav>
    </header>
    <main>
        <?php
        if (isset($_SESSION['error_message'])) {
            echo '<div class="error">' . $_SESSION['error_message'] . '</div>';
            unset($_SESSION['error_message']);
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Login</button>
        </form>
        <p><a class="link" href="forgot_password.php">Forgot Password?</a></p>
        <p>New User? <a class="link" href="register.php">Register Here</a></p>
    </main>
</body>
</html>
