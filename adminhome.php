<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            margin: auto;
            text-align: center;
            padding-top: 50px;
        }
        .button {
            display: block;
            width: 200px;
            padding: 10px;
            margin: 10px auto;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Dashboard</h2>
    <a href="borrowers.php" class="button">Borrowers</a>
    <a href="lenders.php" class="button">Lenders</a>
    <a href="applications.php" class="button">Applications</a>
    <a href="active_loans.php" class="button">Active Loans</a>
    <a href="closed_records.php" class="button">Closed Records</a>
</div>

</body>
</html>
