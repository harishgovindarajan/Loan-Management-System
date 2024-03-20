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

// Check if borrower ID is provided in the URL
if(isset($_GET['id'])) {
    $borrower_id = $_GET['id'];

    // Fetch borrower details based on the ID
    $sql = "SELECT * FROM borrowersignup WHERE id = $borrower_id";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $borrower = $result->fetch_assoc();
    } else {
        // If borrower ID is not found, redirect to the display borrowers page
        header("Location: borrowers.php");
        exit();
    }
} else {
    // If borrower ID is not provided, redirect to the display borrowers page
    header("Location: borrowers.php");
    exit();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Borrower</title>
    <style>
        .container {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="text"], input[type="email"], input[type="tel"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Borrower</h2>
    <form action="update_borrower.php" method="post">
        <input type="hidden" name="id" value="<?php echo $borrower['id']; ?>">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo $borrower['name']; ?>" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $borrower['email']; ?>" required>
        <label for="phone">Phone:</label>
        <input type="tel" id="phone" name="phone" value="<?php echo $borrower['phone']; ?>" required>
        <label for="password">New Password:</label>
        <input type="password" id="password" name="password">
        <input type="submit" value="Update Borrower">
    </form>
</div>

</body>
</html>
