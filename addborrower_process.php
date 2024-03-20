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

// Retrieve form data and sanitize
$name = sanitize($_POST["name"]);
$email = sanitize($_POST["email"]);
$phone = sanitize($_POST["phone"]);
$loanAmount = floatval(sanitize($_POST["loanAmount"]));
$period = intval(sanitize($_POST["period"]));
$loanStartDate = sanitize($_POST["loanStartDate"]);
$interestRate = floatval(sanitize($_POST["interestRate"]));

// Calculate EMI and payable amount
$monthlyRate = $interestRate / (12 * 100);
$emi = (($loanAmount + (($interestRate / 100) * $loanAmount))) / $period;
$payableAmount = $emi * $period;

// Insert borrower data into database
$sql = "INSERT INTO borrowers (name, email, phone, loan_amount, period, loan_start_date, interest_rate, emi, payable_amount) 
        VALUES ('$name', '$email', '$phone', '$loanAmount', '$period', '$loanStartDate', '$interestRate', '$emi', '$payableAmount')";

if ($conn->query($sql) === TRUE) {
    // Borrower added successfully
    echo "Borrower added successfully!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close database connection
if (isset($conn)) {
    $conn->close();
}
ob_end_flush(); // Flush the output buffer

// Function to sanitize input data
function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
?>
