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
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];

    // SQL query to check if the email already exists in the database
    $check_query = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($check_query);

    if ($result->num_rows > 0) {
        // User already exists, display message and exit
        $_SESSION['error_message'] = "User already exists.";
        echo '<script>alert("User already exists."); window.location.href = "register.php";</script>';
        exit();
    } else {
        // Hash the password before storing it in the database for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query to insert user details into the database
        $insert_query = "INSERT INTO users (name, email, password, phone_number) VALUES ('$name', '$email', '$hashed_password', '$phone')";

        if ($conn->query($insert_query)) {
            // Registration successful
            $_SESSION['success_message'] = "Registration successful. Please login.";
            echo '<script>alert("Registration successful. Please login."); window.location.href = "login.php";</script>';
            exit();
        } else {
            // Error in inserting user details
            $_SESSION['error_message'] = "Error: " . $insert_query . "<br>" . $conn->error;
            header("Location: register.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Real Estate Website</title>
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
    </style>
</head>
<body>
    <header>
        <h1>Register</h1>
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
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <label for="phone">Phone Number:</label>
            <input type="text" id="phone" name="phone" required>
            <button type="submit">Register</button>
        </form>
    </main>
</body>
</html>
