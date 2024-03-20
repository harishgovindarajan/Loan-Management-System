<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        h2 {
            text-align: center;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 10px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        td a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }
        td a:hover {
            text-decoration: underline;
        }
        .status-accepted {
            color: #28a745;
            font-weight: bold;
        }
        .status-rejected {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2>Applications</h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Aadhaar Number</th>
            <th>PAN Number</th>
            <th>Amount</th>
            <th>Period</th>
            <th>Purpose</th>
            <th>Aadhaar Card</th>
            <th>PAN Card</th>
            <th>Transaction Slip</th>
            <th>Security Doc</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Connect to MySQL
        $mysqli = new mysqli("localhost", "root", "", "lms");

        // Check connection
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Function to output a link to download a file
        function downloadFile($fileName) {
            $filePath = 'uploads/' . $fileName; // Assuming files are stored in 'uploads' folder
            echo "<td><a href='" . $filePath . "' download='" . $fileName . "'>View</a></td>";
        }

        // Query to fetch data from the database, ordered by applied_date in descending order
        $sql = "SELECT id, name, email, phone, adhar, pan, amount, period, purpose, adhar_file, pan_file, tran_slip, security_doc, status, applied_date FROM applicationstatus ORDER BY applied_date DESC";
        $result = $mysqli->query($sql);

        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<tr id='row_" . $row['id'] . "'>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["name"] . "</td>";
            echo "<td>" . $row["email"] . "</td>";
            echo "<td>" . $row["phone"] . "</td>";
            echo "<td>" . $row["adhar"] . "</td>";
            echo "<td>" . $row["pan"] . "</td>";
            echo "<td>" . $row["amount"] . "</td>";
            echo "<td>" . $row["period"] . "</td>";
            echo "<td>" . $row["purpose"] . "</td>";
            downloadFile($row["adhar_file"]);
            downloadFile($row["pan_file"]);
            downloadFile($row["tran_slip"]);
            downloadFile($row["security_doc"]);
            $statusClass = strtolower($row["status"]) === 'accepted' ? 'status-accepted' : (strtolower($row["status"]) === 'rejected' ? 'status-rejected' : '');
            echo "<td class='" . $statusClass . "'>" . $row["status"] . "</td>"; // Adding status column with status-specific class
            echo "</tr>";
        }

        // Close connection
        $mysqli->close();
        ?>
    </tbody>
</table>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const rows = document.querySelectorAll('tr[id^="row_"]');
        rows.forEach(function(row) {
            const viewableCells = row.querySelectorAll('td:not(:nth-child(10), :nth-child(11), :nth-child(12), :nth-child(13))');
            viewableCells.forEach(function(cell) {
                cell.addEventListener('click', function () {
                    const status = row.cells[13].textContent.trim(); // Get the status from the row
                    const id = row.id.split('_')[1]; // Get the ID from the row ID

                    Swal.fire({
                        title: 'Select Action',
                        showDenyButton: true,
                        confirmButtonText: `Accept`,
                        denyButtonText: `Reject`,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            updateStatus(id, 'Accepted');
                        } else if (result.isDenied) {
                            updateStatus(id, 'Rejected');
                        }
                    });
                });
            });
        });
    });

    function updateStatus(id, status) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "viewapplication_process.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        document.getElementById('row_' + id).cells[13].textContent = status; // Update status in the table
                        const statusClass = status.toLowerCase() === 'accepted' ? 'status-accepted' : (status.toLowerCase() === 'rejected' ? 'status-rejected' : '');
                        document.getElementById('row_' + id).cells[13].classList = statusClass;
                        Swal.fire('Success', response.message, 'success');
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                } else {
                    Swal.fire('Error', 'Failed to update status (HTTP status: ' + xhr.status + ')', 'error');
                }
            }
        };
        xhr.onerror = function() {
            Swal.fire('Error', 'Failed to send request', 'error');
        };
        xhr.send("id=" + id + "&status=" + status);
    }
</script>

</body>
</html>
