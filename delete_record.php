<?php
// Check if the ID parameter is set
if (isset($_POST['id'])) {
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

    // Prepare a delete statement
    $sql = "DELETE FROM borrowers WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $id);

        // Set parameters
        $id = $_POST['id'];

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Record deleted successfully
            echo json_encode(array("success" => true, "message" => "Record deleted successfully."));
        } else {
            // Error executing the statement
            echo json_encode(array("success" => false, "message" => "Error deleting record: " . $stmt->error));
        }

        // Close statement
        $stmt->close();
    } else {
        // Error preparing the statement
        echo json_encode(array("success" => false, "message" => "Error preparing statement."));
    }

    // Close connection
    $conn->close();
} else {
    // ID parameter not set
    echo json_encode(array("success" => false, "message" => "ID parameter not set."));
}
?>
