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
    // Check if files were uploaded
    if (!empty($_FILES['property_images']['name'][0])) {
        $target_dir = "uploads/";
        $uploadOk = 1;
        $property_id = $_POST['property_id'];

        // Loop through each uploaded file
        foreach ($_FILES['property_images']['tmp_name'] as $key => $tmp_name) {
            $image_name = $_FILES['property_images']['name'][$key];
            $image_tmp = $_FILES['property_images']['tmp_name'][$key];
            $target_file = $target_dir . basename($image_name);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check if file is an actual image or fake image
            $check = getimagesize($image_tmp);
            if ($check === false) {
                $_SESSION['error_message'] = "File '{$image_name}' is not an image.";
                $uploadOk = 0;
            }

            // Check file size
            if ($_FILES["property_images"]["size"][$key] > 5000000) { // 5MB
                $_SESSION['error_message'] = "Sorry, '{$image_name}' is too large.";
                $uploadOk = 0;
            }

            // Allow only certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif") {
                $_SESSION['error_message'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                $_SESSION['error_message'] .= " '{$image_name}' was not uploaded.";
            } else {
                // Attempt to upload the file
                if (move_uploaded_file($image_tmp, $target_file)) {
                    // File uploaded successfully, now insert image details into database
                    $sql = "INSERT INTO property_images (property_id, image_path) VALUES ('$property_id', '$target_file')";
                    if ($conn->query($sql) === TRUE) {
                        $_SESSION['success_message'] = "Image uploaded successfully.";
                    } else {
                        $_SESSION['error_message'] = "Error uploading image: " . $conn->error;
                    }
                } else {
                    $_SESSION['error_message'] .= "Sorry, there was an error uploading '{$image_name}'.";
                }
            }
        }

        header("Location: upload.php");
        exit();
    } else {
        $_SESSION['error_message'] = "No files selected.";
        header("Location: upload.php");
        exit();
    }
} else {
    header("Location: upload.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Property Images</title>
</head>
<body>
    <h2>Upload Property Images</h2>
    <?php 
    if(isset($_SESSION['error_message'])) {
        echo "<p>{$_SESSION['error_message']}</p>";
        unset($_SESSION['error_message']);
    } 
    if(isset($_SESSION['success_message'])) {
        echo "<p>{$_SESSION['success_message']}</p>";
        unset($_SESSION['success_message']);
    }
    ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="property_id" value="<?php echo $_POST['property_id']; ?>">
        <label for="property_images">Property Images:</label><br>
        <input type="file" name="property_images[]" id="property_images" multiple required><br><br>
        <input type="submit" value="Upload Images" name="submit">
    </form>
</body>
</html>
