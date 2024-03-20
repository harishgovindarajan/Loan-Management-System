<?php
// Database connection details
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

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    // Check if a new password is provided
    if (!empty($password)) {
        // Hash the new password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        // SQL query to update borrower details including the password
        $sql = "UPDATE lendersignup SET name='$name', email='$email', phone='$phone', password='$hashedPassword' WHERE id=$id";
    } else {
        // SQL query to update borrower details excluding the password
        $sql = "UPDATE lendersignup SET name='$name', email='$email', phone='$phone' WHERE id=$id";
    }

    if ($conn->query($sql) === TRUE) {
        // Redirect to the display borrowers page after successful update
        header("Location: lenders.php");
        exit();
    } else {
        echo "Error updating lender details: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
