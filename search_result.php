<?php
session_start();

// MySQL configuration
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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Retrieve search parameters
    $location = $_GET['location'];
    $property_type = $_GET['property_type'];
    $rooms = $_GET['rooms'];
    $min_budget = $_GET['min_budget'];
    $max_budget = $_GET['max_budget'];

    // Construct SQL query
    $sql = "SELECT properties.*, users.name AS user_name, users.email AS user_email, users.phone_number AS user_phone 
            FROM properties 
            LEFT JOIN users ON properties.user_id = users.id 
            WHERE location LIKE '%$location%' 
            AND property_type = '$property_type' 
            AND bhk = '$rooms' 
            AND price BETWEEN '$min_budget' AND '$max_budget'";
    
    // Execute query
    $result = $conn->query($sql);
}
?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Listings</title>
    <style>
        /* Reset body margin and padding */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        .header {
            background-color: #333;
            color: #fff;
            padding: 10px 0;
            position: fixed;
            top: 0;
            width: 100%;
            text-align: center;
            z-index: 1000;
        }

        .content {
            width: 100%;
            max-width: 2000px;
            margin-top: 80px; /* Space to avoid overlap with fixed header */
            text-align: center;
        }

        h1 {
            color: #fff;
            margin: 0;
        }

        a {
            text-decoration: none;
            color: #fff;
        }

        button {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            background-color: #555;
            color: #fff;
            cursor: pointer;
            margin-top: 15px;
        }

        button:hover {
            background-color: #444;
        }

        .property {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            max-width: 1200px;
        }

        .property img {
            width: 400px;
            height: 250px;
            border-radius: 8px;
            margin-right: 20px;
            object-fit: cover;
        }

        .property-details {
            flex: 1;
            text-align: left;
        }

        .property h2 {
            margin-bottom: 10px;
            color: #333;
        }

        .property p {
            margin-bottom: 5px;
            color: #555;
        }

        .no-result {
            text-align: center;
            margin-top: 20px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Property Listings</h1>
        <!-- Centered home button -->
        <a href="home.php"><button>Home</button></a>
    </div>
    
    <div class="content">
        <?php
        // Check if there are properties found
        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<div class='property'>";
                
                // Display property image
                $property_id = $row['id'];
                $sql_images = "SELECT image_data FROM property_images WHERE property_id = '$property_id' LIMIT 1";
                $result_images = $conn->query($sql_images);
                
                if ($result_images->num_rows > 0) {
                    $row_image = $result_images->fetch_assoc();
                    echo "<img src='" . $row_image['image_data'] . "' alt='Property Image'>";
                } else {
                    echo "<img src='placeholder.jpg' alt='No Image Available'>"; // Placeholder image if none exists
                }
                
                // Display property details
                echo "<div class='property-details'>";
                echo "<h2>" . $row['name'] . "</h2>";
                echo "<p><strong>Location:</strong> " . $row['location'] . "</p>";
                echo "<p><strong>Facilities:</strong> " . $row['facilities'] . "</p>";
                echo "<p><strong>Price:</strong> ₹" . $row['price'] . "</p>";
                echo "<p><strong>BHK:</strong> " . $row['bhk'] . "</p>";
                echo "<p><strong>Carpet Area:</strong> " . $row['carpet_area'] . " sq ft</p>";
                echo "<p><strong>Posted By:</strong> " . $row['user_name'] . "</p>";
                echo "<p><strong>Email:</strong> " . $row['user_email'] . "</p>";
                echo "<p><strong>Phone Number:</strong> " . $row['user_phone'] . "</p>";
                echo "</div>"; // Close property-details div
                echo "</div>"; // Close property div
            }
        } else {
            echo "<div class='no-result'>No properties found matching your search criteria.</div>";
        }
        ?>
    </div>
</body>
</html>
