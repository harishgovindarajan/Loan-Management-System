<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Status</title>
    <style>
        /* Your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 50px 20px 20px; /* Added padding to the top */
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
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
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
    <h2>Application Status</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Amount</th>
                <th>Period</th>
                <?php if(isset($_SESSION['borrower_email'])): ?>
                    <th>Rate of Interest (%)</th> <!-- New column for Rate of Interest -->
                <?php endif; ?>
                <th>Loan Status</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            <?php
            session_start(); // Start the session

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

            // Fetch data corresponding to the email stored in the session
            if(isset($_SESSION['borrower_email'])) {
                $email = $_SESSION['borrower_email'];

                // Fetch data from the database table where email matches session email
                $sql = "SELECT a.name, a.email, a.amount, a.period, a.status, a.remarks";
                if (emailExistsInBorrowers($conn, $email)) {
                    $sql .= ", b.interest_rate";
                }
                $sql .= " FROM applicationstatus a";
                if (emailExistsInBorrowers($conn, $email)) {
                    $sql .= " INNER JOIN borrowers b ON a.email = b.email";
                }
                $sql .= " WHERE a.email = '$email'";
                $result = $conn->query($sql);

                // Check if any rows are returned
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["name"] . "</td>";
                        echo "<td>" . $row["email"] . "</td>";
                        echo "<td>" . $row["amount"] . "</td>";
                        echo "<td>" . $row["period"] . "</td>";
                        if (isset($row["interest_rate"])) {
                            echo "<td>" . $row["interest_rate"] . "</td>"; // Display rate of interest
                        }
                        echo "<td>" . $row["status"] . "</td>";
                        echo "<td>" . $row["remarks"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='no-results'>No results found.</td></tr>";
                }
            } else {
                echo "<tr><td colspan='6' class='no-results'>Email not found in session.</td></tr>";
            }

            // Close the database connection
            $conn->close();

            function emailExistsInBorrowers($conn, $email) {
                $sql = "SELECT * FROM borrowers WHERE email = '$email'";
                $result = $conn->query($sql);
                return $result->num_rows > 0;
            }
            ?>
        </tbody>
    </table>
</body>
</html>
