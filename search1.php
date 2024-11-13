<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Property</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            color: white;
            padding: 20px;
            text-align: center;
        }
        nav {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }
        .nav-btn {
            margin: 0 10px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #555;
            color: white;
            cursor: pointer;
        }
        .nav-btn:hover {
            background-color: #444;
        }
        #home-btn {
            width: 7%; /* Set the width of the Home button to 7% */
            padding: 10px; /* Adjust padding for consistency */
        }
        form {
            background-color: white;
            padding: 20px;
            margin: 20px auto;
            max-width: 600px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        label {
            font-size: 16px;
            margin-bottom: 10px;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #333;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #555;
        }
        .error-message {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <h1>Search for Properties</h1>
        
        <!-- Navigation buttons under the heading -->
        <nav>
            <button class="nav-btn" id="home-btn" onclick="location.href='home.php'">Home</button>
            <?php if(isset($_SESSION['user_email'])): ?>
                <!-- <button class="nav-btn" onclick="location.href='upload_property.php'">Upload Property</button> -->
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <div class="content">
            <?php
            if (isset($_SESSION['error_message'])) {
                echo '<p class="error-message">' . $_SESSION['error_message'] . '</p>';
                unset($_SESSION['error_message']);
            }
            ?>
            <form action="search_result.php" method="GET">
                <label for="location">Location:</label>
                <input type="text" id="location" name="location" required>

                <label for="property_type">Property Type:</label>
                <select id="property_type" name="property_type" required>
                    <option value="flat">Flat</option>
                    <option value="house">House</option>
                    <option value="shop">Shop</option>
                </select>

                <label for="rooms">Rooms (BHK):</label>
                <select id="rooms" name="rooms" required>
                    <option value="1">1 BHK</option>
                    <option value="2">2 BHK</option>
                    <option value="3">3 BHK</option>
                    <option value="4">4 BHK</option>
                    <option value="5">5 BHK</option>
                </select>

                <label for="min_budget">Minimum Budget:</label>
                <select id="min_budget" name="min_budget" required>
                    <?php for ($min = 500000; $min <= 50000000; $min += 1000000) : ?>
                        <option value="<?= $min ?>"><?= number_format($min) ?></option>
                    <?php endfor; ?>
                </select>

                <label for="max_budget">Maximum Budget:</label>
                <select id="max_budget" name="max_budget" required>
                    <?php for ($max = 500000; $max <= 50000000; $max += 1000000) : ?>
                        <option value="<?= $max ?>"><?= number_format($max) ?></option>
                    <?php endfor; ?>
                </select>

                <button type="submit">Search</button>
            </form>
        </div>
    </main>
</body>
</html>
