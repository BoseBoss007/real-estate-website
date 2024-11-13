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

// Logout logic
if (isset($_POST['logout'])) {
    $_SESSION = array();
    session_destroy();
    header("Location: home.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real Estate Website</title>
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
            position: relative;
        }
        .welcome-message {
            position: absolute;
            top: 10px;
            right: 20px;
            color: #fff;
            font-size: 16px;
        }
        nav {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }
        .nav-btn {
            margin: 0 5px;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            background-color: #555;
            color: #fff;
            cursor: pointer;
        }
        .nav-btn:hover {
            background-color: #444;
        }
        main {
            padding: 20px;
            text-align: center;
        }
        section {
            margin: 20px 0;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: left;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        /* Flexbox for displaying images in two rows */
        .image-gallery {
            display: flex;
            flex-wrap: wrap; /* Allow items to wrap to the next line */
            justify-content: space-between; /* Distribute space evenly */
            gap: 10px; /* Space between images */
        }
        .image-gallery img {
            width: 48%; /* Make each image take up almost half the container width */
            border-radius: 8px;
            max-width: 400px; /* Ensures images are not stretched */
        }
    </style>
    <script>
        function handleUploadClick() {
            <?php if(isset($_SESSION['user_email'])): ?>
                window.location.href = 'upload.php';
            <?php else: ?>
                alert('Please login to upload property');
            <?php endif; ?>
        }

        // Function to handle search button click
        function handleSearchClick() {
            window.location.href = 'search1.php';  // Navigate to search1.php when search button is clicked
        }
    </script>
</head>
<body>
    <header>
        <h1>Real Estate Website</h1>
        <!-- Welcome message -->
        <?php if(isset($_SESSION['user_name'])): ?>
            <div class="welcome-message">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></div>
        <?php endif; ?>
        <nav>
            <button class="nav-btn" onclick="location.href='home.php'">Home</button>
            <?php if(isset($_SESSION['user_email'])): ?>
                <form class="logout-form" action="home.php" method="post" style="display: inline;">
                    <button class="nav-btn" type="submit" name="logout">Logout</button>
                </form>
            <?php else: ?>
                <button class="nav-btn" onclick="location.href='login.php'">Login/Register</button>
            <?php endif; ?>
            <button class="nav-btn" onclick="handleSearchClick()">Search</button> <!-- Updated Search button -->
            <button class="nav-btn" onclick="handleUploadClick()">Upload Property</button>
        </nav>
    </header>
    <main>
        <section>
            <h2>About Us</h2>
            <p>Welcome to our real estate website, where we help you find your dream home. Our team is committed to connecting you with properties that meet your preferences and budget. With years of experience in the industry, we offer professional and personalized services.</p>
        </section>

        <section>
            <h2>Blog Posts</h2>
            <ul>
                <li><a href="https://www.bankrate.com/mortgages/tips-for-first-time-home-buyers/">Top 10 Tips for First-Time Homebuyers</a></li>
                <li><a href="https://www.magicbricks.com/blog/how-to-choose-the-right-location-for-your-property/84272.html">How to Choose the Right Location</a></li>
                <li><a href="https://www.adanirealty.com/blogs/top-real-estate-trends-that-will-rule-the-market?srsltid=AfmBOopG5w9Ov_YxHGpzT2RWJASWoTdAlKYgFcHD6sgVoCqz_S6uwX5Y">Understanding the Real Estate Market in 2024</a></li>
            </ul>
        </section>

        <section>
            <h2>Featured Properties</h2>
            <!-- Use Flexbox to arrange images in two rows -->
            <div class="image-gallery">
                <img src="images/house-img-2.webp" alt="Featured Property 1">
                <img src="images/hall-img-2.webp" alt="Featured Property 2">
                <img src="images/kitchen-img-4.webp" alt="Featured Property 3">
                <img src="images/bathroom-img-3.webp" alt="Featured Property 4">
            </div>
        </section>

        <section>
            <h2>User Ratings</h2>
            <p>"Great experience finding my new home!" - Jane Doe</p>
            <p>"The team was incredibly helpful." - John Smith</p>
        </section>
    </main>
</body>
</html>
