<?php
session_start();

// Update these variables with your MySQL configuration
$servername = "localhost";
$username = "root";
$password = ""; // If you have set a password for MySQL, enter it here
$dbname = "home_db1";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user ID from session
    $user_email = $_SESSION['user_email'];
    $sql_user_id = "SELECT id FROM users WHERE email = '$user_email'";
    $result_user_id = $conn->query($sql_user_id);
    if ($result_user_id->num_rows > 0) {
        $row_user_id = $result_user_id->fetch_assoc();
        $user_id = $row_user_id['id'];
    } else {
        $_SESSION['error_message'] = "Failed to retrieve user ID.";
        header("Location: upload.php");
        exit();
    }

    // Retrieve property details from form submission
    $name = $_POST['name'];
    $location = $_POST['location'];
    $facilities = $_POST['facilities'];
    $price = $_POST['price'];
    $rooms = $_POST['rooms'];
    $carpet_area = $_POST['carpet_area'];
    $property_type = $_POST['property_type'];

    // Escape special characters to prevent SQL injection
    $name = $conn->real_escape_string($name);
    $location = $conn->real_escape_string($location);
    $facilities = $conn->real_escape_string($facilities);

    // Construct SQL query to insert property details
    $sql = "INSERT INTO properties (user_id, name, location, facilities, price, bhk, carpet_area, property_type) 
            VALUES ('$user_id', '$name', '$location', '$facilities', '$price', '$rooms', '$carpet_area', '$property_type')";

    if ($conn->query($sql) === TRUE) {
        // Get the ID of the last inserted property
        $property_id = $conn->insert_id;
        
        // Handle image upload
        if(isset($_FILES['images'])){
            $errors = array();
            foreach($_FILES['images']['tmp_name'] as $key => $tmp_name ){
                $file_name = $_FILES['images']['name'][$key];
                $file_size = $_FILES['images']['size'][$key];
                $file_tmp = $_FILES['images']['tmp_name'][$key];
                $file_type = $_FILES['images']['type'][$key];
                if($file_size > 2097152){
                    $errors[] = 'File size must be less than 2 MB';
                }
                $new_file_name = "property_".$property_id."_".$file_name;
                $upload_path = "uploads/".$new_file_name;
                if(empty($errors) == true){
                    move_uploaded_file($file_tmp, $upload_path);
                    // Insert image data into property_images table
                    $sql = "INSERT INTO property_images (property_id, image_data) VALUES ('$property_id', '$upload_path')";
                    $conn->query($sql);
                } else {
                    print_r($errors);
                }
            }
        }
        
        // Set success message in session
        $_SESSION['success_message'] = "Property Posted Successfully.";
    } else {
        $_SESSION['error_message'] = "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Property</title>
    <style>
        /* Styling for the page */
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

        h2, p {
            text-align: center;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
            margin-bottom: 16px;
        }

        input[type="file"] {
            margin-bottom: 16px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            background-color: #4caf50;
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .message {
            text-align: center;
            color: green;
            font-weight: bold;
            margin-top: 10px;
        }

        .error {
            text-align: center;
            color: red;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Upload Property</h1>
        <nav>
            <button onclick="location.href='home.php'">Home</button>
        </nav>
    </header>
    <?php 
    if(isset($_SESSION['error_message'])) {
        echo "<p class='error'>{$_SESSION['error_message']}</p>";
        unset($_SESSION['error_message']);
    } 
    if(isset($_SESSION['success_message'])) {
        echo "<p class='message'>{$_SESSION['success_message']}</p>";
        unset($_SESSION['success_message']);
    }
    ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <label for="name">Name of Property:</label><br>
        <input type="text" name="name" id="name" required><br>
        <label for="property_type">Property Type:</label><br>
        <select name="property_type" id="property_type" required>
            <option value="Flat">Flat</option>
            <option value="House">House</option>
            <option value="Shop">Shop</option>
        </select><br><br>
        <label for="location">Location:</label><br>
        <input type="text" name="location" id="location" required><br>
        <label for="facilities">Facilities:</label><br>
        <input type="text" name="facilities" id="facilities" required><br>
        <label for="price">Price:</label><br>
        <input type="number" name="price" id="price" required><br>
        <label for="rooms">Number of Rooms (BHK):</label><br>
        <input type="number" name="rooms" id="rooms" required><br>
        <label for="carpet_area">Carpet Area (in square feet):</label><br>
        <input type="number" name="carpet_area" id="carpet_area" required><br><br>
        <label for="images">Upload Images:</label><br>
        <input type="file" name="images[]" multiple required><br><br>
        <input type="submit" value="Post" name="submit">
    </form>
</body>
</html>
