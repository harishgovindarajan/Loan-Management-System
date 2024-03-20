<?php
// Start the session
session_start();

// Database connection
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

// Fetch current paid amount and payable amount from the database
if (isset($_SESSION['borrower_email'])) {
    $email = $_SESSION['borrower_email'];

    // Fetch current paid amount and payable amount
    $sql = "SELECT paid_amount, payable_amount FROM borrowers WHERE email='$email' AND loan_status='inProgress'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $paidAmount = $row["paid_amount"];
        $payableAmount = $row["payable_amount"];

        // Check if emi is set and not empty
        if (isset($_GET['emi']) && !empty($_GET['emi'])) {
            // Get the value of emi
            $emi = $_GET['emi'];
            // Echo the value of emi for debugging purposes
            echo "EMI Value: " . $emi . "<br>";
            // Increase paid amount by EMI
            $paidAmount = (int)$paidAmount + (int)$emi;
        } else {
            // If emi is not set or empty, display an error message
            echo "Error: EMI value not provided.";
            exit; // Exit script if EMI value is not provided
        }

        // Get current date
        $currentDate = date("Y-m-d");

        // Update paid amount and date in the database
        $update_sql = "UPDATE borrowers SET paid_amount = $paidAmount, payment_date = '$currentDate' WHERE email='$email' AND loan_status='inProgress'";

        // Debugging statement to echo the SQL query
        echo "SQL Query: " . $update_sql . "<br>";

        if ($conn->query($update_sql) === TRUE) {
            echo "Record updated successfully";

            // Check if payable amount equals paid amount
            if ($payableAmount == $paidAmount) {
                // Update loan status to "Paid"
                $update_status_sql = "UPDATE borrowers SET loan_status = 'Paid' WHERE email='$email' AND loan_status='inProgress'";
                if ($conn->query($update_status_sql) === TRUE) {
                    echo "Loan status updated to Paid";
                } else {
                    echo "Error updating loan status: " . $conn->error;
                }
            }
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "No records found for the user.";
    }
} else {
    echo "Session variable 'borrower_email' not set.";
}

$conn->close();
?>
