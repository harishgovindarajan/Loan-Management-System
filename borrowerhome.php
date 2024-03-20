<?php
// Start the session
session_start();

// Assuming you have a database connection established
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lms";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the user's name
$name = '';
if (isset($_SESSION['borrower_email'])) {
    $email = $_SESSION['borrower_email'];

    // Retrieve name from the database using email
    $sqlName = "SELECT name FROM borrowersignup WHERE email = '$email'";
    $resultName = $conn->query($sqlName);

    if ($resultName->num_rows > 0) {
        $rowName = $resultName->fetch_assoc();
        $name = $rowName['name'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrower Homepage</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }

        .topbar {
            background-color: #1b1b1b;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .sidebar-toggle {
            cursor: pointer;
            padding: 10px;
            color: #fff;
            font-size: 20px;
        }

        .profile-button {
            padding: 10px 20px;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
            color: #fff;
            background-color: #e74c3c;
            border: 2px solid #e74c3c;
            border-radius: 5px;
            cursor: pointer;
        }

        .profile-button:hover {
            background-color: #c0392b;
            border-color: #c0392b;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: -250px;
            bottom: 0;
            width: 250px;
            background-color: #1b1b1b;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 999;
            display: flex;
            flex-direction: column;
            justify-content:center;
            align-items: center;
            padding-top: 60px; /* Adjust top padding to make space for the top bar */
        }

        .button-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            width: 100%;
            padding: 20px;
            box-sizing: border-box;
        }

        .bbutton {
            padding: 15px 30px;
            font-size: 18px;
            text-align: center;
            text-decoration: none;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            overflow: hidden;
            position: relative;
        }

        .bbutton:before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #fff;
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .bbutton:hover:before,
        .bbutton.active:before {
            transform: scaleX(1);
        }

        .close-sidebar {
            cursor: pointer;
            padding: 10px;
            color: #1b1b1b;
            font-size: 20px;
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .close-sidebar:hover {
            color: #ccc;
        }

        .sidebar.active {
            left: 0;
        }

        .error {
            border: 2px solid red;
        }
        #dynamic-content {
            margin-top: 60px; /* Adjust top margin to make space for the top bar */
            margin-left: 250px; /* Adjust left margin to match sidebar width */
            padding: 20px; /* Add padding to ensure content is not too close to the sidebar */
            box-sizing: border-box; /* Include padding in width calculation */
        }
    </style>
</head>
<body>
    <!-- Top Bar -->
    <div class="topbar">
        <!-- Sidebar Toggle Button -->
        <div class="sidebar-toggle" onclick="toggleSidebar()">☰</div>

        <!-- Profile Button -->
        <?php if (!empty($name)) : ?>
            <a href="borrowerprofile.php" class="profile-button"><?= $name ?></a>
        <?php else : ?>
            <a href="borrowerprofile.php" class="profile-button">Profile</a>
        <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <div class="sidebar active" id="sidebar">
        <div class="button-container">
            <a class="close-sidebar" onclick="toggleSidebar()">X</a>
            <!-- Add a class to the sidebar buttons for easy selection -->
            <a class="bbutton" href="#" onclick="loadPage('application.php')">New Loan</a>
            <a class="bbutton" href="#" onclick="loadPage('activeloan.php')">Active Loans</a>
            <a class="bbutton" href="#" onclick="loadPage('paidloans.php')">Paid Loans</a>
            <a class="bbutton" href="#" onclick="loadPage('application_status.php')">Application Status</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <!-- Main content -->
        <div id="main-content" class="sidebar-content">
            <!-- Initial content -->
            Welcome to Access Loan
        </div>
    </div>

    <!-- Placeholder for dynamically loaded content -->
    <div id="dynamic-content"></div>

    <!-- Your JavaScript imports -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Function to toggle sidebar visibility
        function toggleSidebar() {
            var sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }

        // Function to load content dynamically
        function loadPage(url) {
            $.ajax({
                url: url,
                type: "GET",
                success: function(data) {
                    $("#dynamic-content").html(data);
                    // Reset the styles of sidebar buttons after loading content
                    resetSidebarButtonStyles();
                },
                error: function() {
                    $("#dynamic-content").html("Error loading content.");
                }
            });
        }

        // Function to reset sidebar button styles
        function resetSidebarButtonStyles() {
            // Remove any additional styles applied to sidebar buttons
            $(".sidebar-button").removeClass("active");
        }
    </script>
</body>
</html>
