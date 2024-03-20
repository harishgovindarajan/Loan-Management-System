<?php
session_start(); // Start the session

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

// Retrieve email and password from the login form
$email = $_POST['email'];
$passwordAttempt = $_POST['password'];

// Example query to retrieve the hashed password from the database
$sql = "SELECT * FROM admin WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // User found, check the password
    $row = $result->fetch_assoc();
    $storedPassword = $row['password']; // Assuming password is stored in plain text

    // Verify the entered password against the stored plain text password
    if ($passwordAttempt === $storedPassword) {
        // Successful login
        // Store email in session
        $_SESSION['admin_email'] = $email;
        // Redirect to the home page
        header("Location: adminhome.php");
        exit();
    } else {
        // Invalid password
        echo "Invalid password. Please try again.";
    }
} else {
    // User not found
    echo "Invalid email or password. Please try again.";
}


$conn->close();
?>
