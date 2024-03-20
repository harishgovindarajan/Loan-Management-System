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

// Retrieve data from the AJAX request
$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'];
$status = $data['status'];

// SQL query to update status in the database
$sql = "UPDATE applicationstatus SET status = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $status, $id);

$response = array();

if ($stmt->execute()) {
    $response['status'] = 'success';
    $response['message'] = 'Status updated successfully!';
} else {
    $response['status'] = 'error';
    $response['message'] = 'Error updating status: ' . $stmt->error;
}

// Close the prepared statement
$stmt->close();

// Close the database connection
$conn->close();

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
