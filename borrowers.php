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
$sql = "SELECT * FROM borrowersignup";
$result = $conn->query($sql);

// Store borrower details in an array
$borrowers = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $borrowers[] = $row;
    }
}

// Close the database connection
$conn->close();

// Function to delete a borrower from the database
function deleteBorrower($id) {
    // Re-establish database connection
    $conn = new mysqli("localhost", "root", "", "lms");
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement to delete borrower
    $sql = "DELETE FROM borrowersignup WHERE id = $id";

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
    deleteBorrower($id_to_delete);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrower Details</title>
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

<h2>Borrower Details</h2>

<table>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Password</th>
        <th>Action</th>
    </tr>
    <?php foreach ($borrowers as $borrower): ?>
    <tr>
        <td><?php echo $borrower['name']; ?></td>
        <td><?php echo $borrower['email']; ?></td>
        <td><?php echo $borrower['phone']; ?></td>
        <td><?php echo $borrower['password']; ?></td>
        <td>
            <button class="edit-button" onclick="editBorrower(<?php echo $borrower['id']; ?>)">Edit</button>
            <form method="post" style="display: inline;">
                <input type="hidden" name="delete" value="<?php echo $borrower['id']; ?>">
                <button type="submit" class="delete-button">Delete</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<script>
    function editBorrower(id) {
        // Redirect to the edit page with borrower ID
        window.location.href = "edit_borrower.php?id=" + id;
    }
</script>

</body>
</html>
