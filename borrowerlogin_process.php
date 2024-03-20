<?php
// Set session cookie parameters to expire when the browser is closed
session_set_cookie_params(0);

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

// Retrieve email and password from the login form
$email = $_POST['email'];
$passwordAttempt = $_POST['password'];

// Example query to retrieve the hashed password from the database
$sql = "SELECT * FROM borrowersignup WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // User found, check the password
    $row = $result->fetch_assoc();
    $storedHashedPassword = $row['password'];

    // Verify the entered password against the stored hashed password
    if (password_verify($passwordAttempt, $storedHashedPassword)) {
        // Successful login
        // Store email in session
        $_SESSION['borrower_email'] = $email;
        // Redirect to the home page
        header("Location: borrowerhome.php");
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
