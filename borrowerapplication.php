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

// Define an array to store missing fields
$missingFields = array();

// Check for missing or empty fields
$fields = array('name', 'email', 'phone', 'adhar', 'pan', 'amount', 'period','purpose');
foreach ($fields as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        $missingFields[] = $field;
    }
}

// If any fields are missing, return an error response
if (!empty($missingFields)) {
    $response['status'] = 'error';
    $response['message'] = 'Required field(s) missing: ' . implode(', ', $missingFields);
} else {
    // Retrieve data from the POST request
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $adhar = $_POST['adhar'];
    $pan = $_POST['pan'];
    $amount = $_POST['amount'];
    $period = $_POST['period'];
    $purpose = $_POST['purpose'];
    $applied_date = date('Y-m-d'); // Get current date

    // File upload handling for Aadhar and PAN cards
    $adharFileName = isset($_FILES['file1']['name']) ? $_FILES['file1']['name'] : '';
    $adharTempName = isset($_FILES['file1']['tmp_name']) ? $_FILES['file1']['tmp_name'] : '';
    $panFileName = isset($_FILES['file2']['name']) ? $_FILES['file2']['name'] : '';
    $panTempName = isset($_FILES['file2']['tmp_name']) ? $_FILES['file2']['tmp_name'] : '';
    $tranSlipName = isset($_FILES['file3']['name']) ? $_FILES['file3']['name'] : '';
    $transTempName = isset($_FILES['file3']['tmp_name']) ? $_FILES['file3']['tmp_name'] : '';
    $securityFileName = isset($_FILES['file4']['name']) ? $_FILES['file4']['name'] : '';
    $securityTempName = isset($_FILES['file4']['tmp_name']) ? $_FILES['file4']['tmp_name'] : '';

    // Move uploaded files to desired directory (you should change this path to your desired directory)
    $adharDestination = 'uploads/' . $adharFileName;
    $panDestination = 'uploads/' . $panFileName;
    $transDestination = 'uploads/' . $tranSlipName;
    $securityDestination = 'uploads/' . $tranSlipName;

    // Move the uploaded files to the destination directory
    move_uploaded_file($adharTempName, $adharDestination);
    move_uploaded_file($panTempName, $panDestination);
    move_uploaded_file($transTempName, $transDestination);
    move_uploaded_file($securityTempName, $securityDestination);


    // SQL query to insert data into borrowerapplication table
    $sql1 = "INSERT INTO borrowerapplication (name, email, phone, adhar, pan, amount, period, purpose, applied_date, adhar_file, pan_file, tran_slip, security_doc) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bind_param("sssssssssssss", $name, $email, $phone, $adhar, $pan, $amount, $period, $purpose, $applied_date, $adharFileName, $panFileName, $tranSlipName, $securityFileName);

    // SQL query to insert data into applicationstatus table
    $status = 'Pending'; // Default status
    $sql2 = "INSERT INTO applicationstatus (name, email, phone, adhar, pan, amount, period, purpose, applied_date, adhar_file, pan_file, tran_slip, security_doc, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("ssssssssssssss", $name, $email, $phone, $adhar, $pan, $amount, $period, $purpose, $applied_date, $adharFileName, $panFileName, $tranSlipName, $securityFileName, $status);

    $response = array();

    // Execute both queries
    if ($stmt1->execute() && $stmt2->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'Loan application submitted successfully!';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error: ' . $conn->error;
    }

    // Close the prepared statements
    $stmt1->close();
    $stmt2->close();
}

// Close the database connection
$conn->close();

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
