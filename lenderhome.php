<?php
ob_start(); // Start output buffering
session_start(); // Start PHP session

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lms";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $loanAmount = $_POST["loanAmount"];
    $period = $_POST["period"];
    $loanStartDate = $_POST["loanStartDate"];
    $interestRate = $_POST["interestRate"];

    // Insert borrower data into database
    $sql = "INSERT INTO borrowers (name, email, phone, loan_amount, period, loan_start_date, interest_rate) 
            VALUES ('$name', '$email', '$phone', '$loanAmount', '$period', '$loanStartDate', '$interestRate')";
    if ($conn->query($sql) === TRUE) {
        // Borrower added successfully
        echo '<script>alert("Borrower added successfully!");</script>';
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lender Homepage</title>
    <style>
        /* Global styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9; /* Default background color */
            color: #333;
        }

        /* Header styles */
        header {
            background-color: #000; /* Black header background */
            color: #fff; /* White text color */
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            position:fixed;
            top:0;


            /*margin-bottom: 20px;*/
        }

        .profile-button {
            background-color: #e74c3c; /* Red profile button background */
            color: #fff;
            border: none;
            padding: 10px 20px;
            text-transform: uppercase;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
            margin-right: 20px; /* Add right margin to separate from the edge */
            text-decoration: none;
        }

        .profile-button:hover {
            background-color: #c0392b; /* Darker red on hover */
        }

        /* Sidebar styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 250px; /* Fixed width for the sidebar */
            background-color: #000;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 999;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            transition: left 0.3s;
        }

        .sidebar.active {
            left: 0;
        }

        .sidebar-toggle {
            cursor: pointer;
            padding: 10px;
            color: #fff;
            font-size: 20px;
        }

        .close-sidebar {
            cursor: pointer;
            padding: 10px;
            color: #1b1b1b;
            font-size: 20px;
            position: absolute;
            top: 10px;
            right: 10px;
            transition: color 0.3s; /* Add transition for smooth animation */
        }

        .close-sidebar {
            color: #ccc; /* Change color on hover */
        }

        /* Container styles */
        .container {
            margin: 20px auto;
            margin-left: 250px;
            margin-top: 0;
            text-align: center;
            overflow: auto; /* Allow the container to expand naturally */
        }


        /* Button container styles */
        .button-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px; /* Adjust padding as needed */
            /*overflow: auto; /* Enable scrolling if content overflows */
        }

        /* Button styles */
        .button {
            margin: 10px;
            padding: 15px 30px;
            font-size: 18px;
            text-align: center;
            text-decoration: none;
            color: #fff;
            /*background-color: #2ecc71; /* Green button background */
            border: none; /* Remove border */
            border-radius: 5px;
            cursor: pointer;
            width: 100%; /* Full width button */
            box-sizing: border-box; /* Include padding in button width */
            position: relative; /* Relative positioning for pseudo-element */
        }

        .button.underlined::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 2px;
            background-color: #fff;
            animation: underline 0.3s forwards;
        }

        @keyframes underline {
            from {
                width: 0;
            }
            to {
                width: 100%;
            }
        }

        /* Button hover styles */
        .button:hover::after {
            width: 100%;
        }

        /* Responsive styles */
        @media screen and (max-width: 600px) {
            .button {
                font-size: 16px; /* Decrease font size on smaller screens */
            }
        }
    </style>
</head>

<body>
    <header>
        <!-- Sidebar Toggle Button -->
        <div class="sidebar-toggle" onclick="toggleSidebar()">☰</div>

        <!-- Profile Button -->
        <?php
        if (isset($_SESSION['lender_email'])) {
            $email = $_SESSION['lender_email'];
            $sql = "SELECT name FROM lendersignup WHERE email = '$email'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $name = $row['name'];
                echo '<a href="lenderprofile.php" class="profile-button">' . $name . '</a>';
            } else {
                echo '<a href="lenderprofile.php" class="profile-button">Profile</a>';
            }
        } else {
            echo '<a href="lenderprofile.php" class="profile-button">Profile</a>';
        }
        ?>
    </header>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <a class="close-sidebar" onclick="toggleSidebar()">X</a>
        <div class="button-container">
            <a href="#" class="button" onclick="handleButtonClick(this, 'viewapplication.php')">View Application</a>
            <a href="#" class="button" onclick="handleButtonClick(this, 'activeborrowers.php')">Active Borrowers</a>
            <a href="#" class="button" onclick="handleButtonClick(this, 'addborrower.php')">Add Borrower</a>
            <a href="#" class="button" onclick="handleButtonClick(this, 'history.php')">History</a>
        </div>
    </div>
    <!-- Main content -->
<div class="container" id="main-content">
    <!-- Initial content -->
    <h2>Welcome to the Access Loan</h2>
    <p>This is the lender homepage. Please use the sidebar to navigate.</p>
</div>



    <!-- JavaScript for toggling sidebar and managing underlined buttons -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function toggleSidebar() {
            var sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }

        function handleButtonClick(button, url) {
            var buttons = document.querySelectorAll('.button');
            buttons.forEach(function(btn) {
                btn.classList.remove('underlined');
            });
            button.classList.add('underlined'); // Corrected syntax
            
            // Fetch content using AJAX
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("main-content").innerHTML = this.responseText;
                    enableRowActions(); // Call the function to enable row actions after content is loaded
                }
            };
            xhr.open("GET", url, true);
            xhr.send();
        }

        function enableRowActions() {
            const rows = document.querySelectorAll('tr[id^="row_"]');
            rows.forEach(function(row) {
                const viewableCells = row.querySelectorAll('td:not(:nth-child(10), :nth-child(11), :nth-child(12), :nth-child(13))');
                viewableCells.forEach(function(cell) {
                    cell.addEventListener('click', function () {
                        const status = row.cells[13].textContent.trim(); // Get the status from the row
                        const id = row.id.split('_')[1]; // Get the ID from the row ID

                        Swal.fire({
                            title: 'Select Action',
                            showDenyButton: true,
                            confirmButtonText: `Accept`,
                            denyButtonText: `Reject`,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                updateStatus(id, 'Accepted');
                            } else if (result.isDenied) {
                                updateStatus(id, 'Rejected');
                            }
                        });
                    });
                });
            });
        }

        function updateStatus(id, status) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "viewapplication_process.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            document.getElementById('row_' + id).cells[13].textContent = status; // Update status in the table
                            const statusClass = status.toLowerCase() === 'accepted' ? 'status-accepted' : (status.toLowerCase() === 'rejected' ? 'status-rejected' : '');
                            document.getElementById('row_' + id).cells[13].classList = statusClass;
                            Swal.fire('Success', response.message, 'success');
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    } else {
                        Swal.fire('Error', 'Failed to update status (HTTP status: ' + xhr.status + ')', 'error');
                    }
                }
            };
            xhr.onerror = function() {
                Swal.fire('Error', 'Failed to send request', 'error');
            };
            xhr.send("id=" + id + "&status=" + status);
        }
        function addBorrower() {
            // Retrieve form data
            var name = document.getElementById("name").value;
            var email = document.getElementById("email").value;
            var phone = document.getElementById("phone").value;
            var loanAmount = document.getElementById("loanAmount").value;
            var period = document.getElementById("period").value;
            var loanStartDate = document.getElementById("loanStartDate").value;
            var interestRate = document.getElementById("interestRate").value;

            // Validate that all fields are filled
            if (name == "" || email == "" || phone == "" || loanAmount == "" || period == "" || loanStartDate == "" || interestRate == "") {
                alert("Please fill in all fields");
                return;
            }

            // Create a new XMLHttpRequest object
            var xhr = new XMLHttpRequest();

            // Configure the request
            xhr.open("POST", "addborrower_process.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            // Define what happens on successful data submission
            xhr.onload = function () {
                if (xhr.status == 200) {
                    // Display success message
                    document.getElementById("successMessage").textContent = "Borrower added successfully!";
                } else {
                    console.error("Error:", xhr.statusText);
                }
            };

            // Define what happens in case of error
            xhr.onerror = function () {
                console.error("Error:", xhr.statusText);
            };

            // Send the request with the form data
            xhr.send("name=" + name + "&email=" + email + "&phone=" + phone + "&loanAmount=" + loanAmount + "&period=" + period + "&loanStartDate=" + loanStartDate + "&interestRate=" + interestRate);
        }
    </script>
</body>

</html>

<?php
// Close database connection
if(isset($conn)) {
    $conn->close();
}
ob_end_flush(); // Flush the output buffer
?>
