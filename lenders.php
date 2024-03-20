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

// Fetch all borrower details from the database
$sql = "SELECT * FROM lendersignup";
$result = $conn->query($sql);

// Store borrower details in an array
$lenders = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $lenders[] = $row;
    }
}

// Close the database connection
$conn->close();

// Function to delete a borrower from the database
function deleteLender($id) {
    // Re-establish database connection
    $conn = new mysqli("localhost", "root", "", "lms");
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement to delete borrower
    $sql = "DELETE FROM lendersignup WHERE id = $id";

    // Execute the SQL statement
    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}

// Check if the delete button was clicked
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    $id_to_delete = $_POST["delete"];
    deleteLender($id_to_delete);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lender Details</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        .edit-button, .delete-button {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 8px 12px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            margin: 2px 2px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<h2>Lender Details</h2>

<table>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Password</th>
        <th>Action</th>
    </tr>
    <?php foreach ($lenders as $lender): ?>
    <tr>
        <td><?php echo $lender['name']; ?></td>
        <td><?php echo $lender['email']; ?></td>
        <td><?php echo $lender['phone']; ?></td>
        <td><?php echo $lender['password']; ?></td>
        <td>
            <button class="edit-button" onclick="editLender(<?php echo $lender['id']; ?>)">Edit</button>
            <form method="post" style="display: inline;">
                <input type="hidden" name="delete" value="<?php echo $lender['id']; ?>">
                <button type="submit" class="delete-button">Delete</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<script>
    function editLender(id) {
        // Redirect to the edit page with borrower ID
        window.location.href = "edit_lender.php?id=" + id;
    }
</script>

</body>
</html>
