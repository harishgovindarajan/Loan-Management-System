<?php
// Assuming you already have a database connection established

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if ID, status, and remarks parameters are set
    if (isset($_POST["id"]) && isset($_POST["status"]) && isset($_POST["remarks"])) {
        // Sanitize the inputs
        $id = intval($_POST["id"]);
        $status = $_POST["status"];
        $remarks = $_POST["remarks"];

        // Log received parameters (for debugging purposes)
        error_log("ID: " . $id);
        error_log("Status: " . $status);
        error_log("Remarks: " . $remarks);

        // Update the status, accept_date, and remarks in the database
        $mysqli = new mysqli("localhost", "root", "", "lms");
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Prepare the SQL statement based on the status
        if ($status == "Accepted") {
            $sql = "UPDATE applicationstatus SET status = ?, accept_date = CURRENT_TIMESTAMP(), remarks = ? WHERE id = ?";
        } else {
            $sql = "UPDATE applicationstatus SET status = ?, remarks = ? WHERE id = ?";
        }

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ssi", $status, $remarks, $id);

        if ($stmt->execute()) {
            // Return success message
            echo json_encode(array("success" => true, "message" => "Status and remarks updated successfully"));
        } else {
            // Log error and return error message
            error_log("Error: " . $mysqli->error);
            echo json_encode(array("success" => false, "message" => "Failed to update status and remarks"));
        }

        // Close connection
        $mysqli->close();
    } else {
        // Return error message if parameters are missing
        echo json_encode(array("success" => false, "message" => "Missing parameters"));
    }
} else {
    // Return error message if request method is not POST
    echo json_encode(array("success" => false, "message" => "Invalid request method"));
}
?>
