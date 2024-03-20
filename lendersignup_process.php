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
    $response['status'] = 'error';
    $response['message'] = 'Connection failed: ' . $conn->connect_error;
} else {
    // Connection is successful
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $rawPassword = $_POST['password'];

        // Check if email exists
        if (checkIfExists('email', $email)) {
            $response['status'] = 'error';
            $response['message'] = 'Email already exists. Please use a different email.';
        } elseif (checkIfExists('phone', $phone)) {
            // Check if phone number exists
            $response['status'] = 'error';
            $response['message'] = 'Phone number already exists. Please use a different phone number.';
        } else {
            // Hash the password
            $hashedPassword = password_hash($rawPassword, PASSWORD_DEFAULT);

            // Use prepared statements to prevent SQL injection
            $stmt = $conn->prepare("INSERT INTO lendersignup (name, email, phone, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $phone, $hashedPassword);

            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Registration successful';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Error: ' . $stmt->error;
            }

            // Close the prepared statement
            $stmt->close();
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Invalid request method';
    }
}

// Close the database connection
$conn->close();

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);

function checkIfExists($field, $value) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM lendersignup WHERE $field = ?");
    $stmt->bind_param("s", $value);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    return $result->num_rows > 0;
}
?>
