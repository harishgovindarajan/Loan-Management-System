<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HISTORY</title>
    <style>
        /* Your CSS styles here */
        /* CSS Reset */
        html, body {
            margin: 0;
            padding: 0;
        }

        /* Your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px; /* Adjust padding as needed */
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            table-layout: auto;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            word-wrap: break-word; /* Added property */
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .no-results {
            text-align: center;
            padding: 20px;
        }
    </style>
</head>
<body>
<h2>HISTORY</h2>
<table>
    <thead>
    <tr>
        <th>Loan ID</th>
        <th>Name</th>
        <th>Mail ID</th>
        <th>Phone</th>
        <th>Loan Start Date</th>
        <th>Loan Amount</th>
        <th>Period (Months)</th>
        <th>Interest Rate</th>
        <th>Payable Amount</th>
        <th>Loan closed on</th>
        <th>Loan Status</th>
    </tr>
    </thead>
    <tbody>
    <?php
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

    // Fetch data from the database where status is 'Accepted'
    $sql = "SELECT id, name, email, phone, loan_start_date, loan_amount, period, interest_rate, payable_amount, payment_date, loan_status FROM borrowers WHERE loan_status = 'Paid'";
    $result = $conn->query($sql);

    // Check if any rows are returned
    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["name"] . "</td>";
            echo "<td>" . $row["email"] . "</td>";
            echo "<td>" . $row["phone"] . "</td>";
            echo "<td>" . $row["loan_start_date"] . "</td>";
            echo "<td>" . $row["loan_amount"] . "</td>";
            echo "<td>" . $row["period"] . "</td>";
            echo "<td>" . $row["interest_rate"] . "</td>";
            echo "<td>" . $row["payable_amount"] . "</td>";
            echo "<td>" . $row["payment_date"] . "</td>";
            echo "<td>" . $row["loan_status"] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6' class='no-results'>No results found.</td></tr>";
    }

    // Close the database connection
    $conn->close();
    ?>
    </tbody>
</table>
</body>
</html>
