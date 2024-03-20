<?php
session_start(); // Start the session

// Check if borrower email is set in session
if (isset($_SESSION['borrower_email'])) {
    $email = $_SESSION['borrower_email'];

    // Database connection
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

    // Fetch loan details from the database using the email from session
    $sql = "SELECT loan_start_date, period, loan_amount, interest_rate, paid_amount, payment_date, payable_amount, emi FROM borrowers WHERE email='$email' AND loan_status='inProgress'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $loanStartDate = $row["loan_start_date"];
        $period = $row["period"];
        $loanAmount = $row["loan_amount"];
        $interestRate = $row["interest_rate"];
        $paidAmount = $row["paid_amount"];
        $lastPaymentDate = $row["payment_date"];
        $payableAmount=$row["payable_amount"];
        $emi=$row["emi"];

        $loanEndDate = date('Y-m-d', strtotime($loanStartDate . ' + ' . $period . ' months')); // Calculate loan end date

        // If last payment date is not found, set it to loan start date
        if (empty($lastPaymentDate)) {
            $lastPaymentDate = $loanStartDate;
        }
        // Calculate balance amount
        $balanceAmount = $payableAmount - $paidAmount;

        // Calculate next billing date
        $nextBillingDate = date('Y-m-d', strtotime($lastPaymentDate . ' +1 month'));
    } else {
        // If no loan details found, set defaults
        $loanStartDate = date("Y-m-d"); // If no loan start date found, default to today's date
        $loanEndDate = date('Y-m-d'); // Default end date
        $emi = 0; // Default EMI
        $payableAmount = 0; // Default payable amount
        $balanceAmount = 0; // Default balance amount
        $lastPaymentDate = ""; // Default last payment date
        $nextBillingDate = ""; // Default next billing date
    }

    $conn->close();
} else {
    // If borrower email is not set in session, redirect to login page or handle as appropriate
    header("Location: login.php");
    exit(); // Stop script execution after redirection
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title></title>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }
    .container {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
    }
    .message {
        text-align: center;
        font-size: 18px;
        margin-top: 20px;
    }
    .loan-detail {
        margin-bottom: 20px;
    }
    label {
        display: block;
        margin-bottom: 10px;
    }
    #pie-chart {
        width: 400px;
        height: 400px;
        margin: 20px auto;
    }
    .button {
        display: inline-block;
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    .button:hover {
        background-color: #0056b3;
    }

    /* Scoped styles for loaded content */
    .loaded-content label {
        display: block;
        margin-bottom: 10px;
    }

    .loaded-content .loan-detail {
        margin-bottom: 20px;
    }

    /* Add more scoped styles here as needed */
</style>
</head>
<body>

<div class="container">
    <h2></h2>
    
    <?php if ($balanceAmount > 0) : ?>
        <!-- Display loan details if balance amount is greater than zero -->
        <div class="loan-detail">
            <label for="startDate">Loan Start Date:</label>
            <input type="date" id="startDate" name="startDate" value="<?php echo $loanStartDate; ?>" readonly>
        </div>
        <div class="loan-detail">
            <label for="endDate">Loan End Date:</label>
            <input type="date" id="endDate" name="endDate" value="<?php echo $loanEndDate; ?>" readonly>
        </div>
        <div class="loan-detail">
            <label for="loanAmount">Loan Amount:</label>
            <input type="text" id="loanAmount" name="loanAmount" value="<?php echo $loanAmount; ?>" readonly>
        </div>
        <div class="loan-detail">
            <label for="period">Period (months):</label>
            <input type="text" id="period" name="period" value="<?php echo $period; ?>" readonly>
        </div>
        <div class="loan-detail">
            <label for="interestRate">Interest Rate (%):</label>
            <input type="text" id="interestRate" name="interestRate" value="<?php echo $interestRate; ?>" readonly>
        </div>
        <div class="loan-detail">
            <label for="payableAmount">Payable Amount:</label>
            <input type="text" id="payableAmount" name="payableAmount" value="<?php echo $payableAmount; ?>" readonly>
        </div>
        <div class="loan-detail">
            <label for="lastPaymentDate">Last Payment Date:</label>
            <input type="date" id="lastPaymentDate" name="lastPaymentDate" value="<?php echo $lastPaymentDate; ?>" readonly>
        </div>
        <div class="loan-detail">
            <label for="nextBillingDate">Next Billing Date:</label>
            <input type="date" id="nextBillingDate" name="nextBillingDate" value="<?php echo $nextBillingDate; ?>" readonly>
        </div>
        <div class="loan-detail">
            <label for="emi">EMI:</label>
            <input type="text" id="emi" name="emi" value="<?php echo $emi; ?>" readonly>
            <button class="button" onclick="payEMI(<?php echo $emi * 100; ?>)">Pay EMI</button>
        </div>
    <?php else : ?>
        <!-- Display message if there are no active loans -->
        <div class="message">
            <p>No active loans</p>
        </div>
    <?php endif; ?>
</div>

<!-- Pie Chart -->
<canvas id="pie-chart"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    // Get the loan amount and paid amount
    var paidAmount = <?php echo $paidAmount; ?>;

    // Calculate payable amount
    var emi = <?php echo $emi; ?>;
    var period = <?php echo $period; ?>;
    var payableAmount = emi * period;

    // Calculate balance amount
    var balanceAmount = payableAmount - paidAmount;

    // Update the pie chart
    function updatePieChart() {
        var ctx = document.getElementById('pie-chart').getContext('2d');
        
        // Destroy existing chart if it exists
        if(window.pieChart) {
            window.pieChart.destroy();
        }

        var data = {
            labels: ['Paid Amount', 'Balance Amount'],
            datasets: [{
                data: [paidAmount, balanceAmount],
                backgroundColor: [
                    'rgb(75, 192, 192)',
                    'rgb(255, 99, 132)'
                ]
            }]
        };
        var options = {};
        window.pieChart = new Chart(ctx, {
            type: 'pie',
            data: data,
            options: options
        });
    }

    // Initial update
    updatePieChart();

    // Example: Pay EMI (Call this function when the user clicks "Pay EMI" button)
    function payEMI(amount) {
        var options = {
            "key": "rzp_test_njASQFoOfJeCOx",
            "amount": amount, // Amount in paise
            "currency": "INR",
            "name": "Loan EMI",
            "description": "Payment for EMI",
            "image": "",
            "handler": function (response){
                // Redirect to success page or handle success response
                alert("Payment successful");
                // Update paid_amount in database
                updatePaidAmountInDB();
            },
            "prefill": {
                "name": "",
                "email": ""
            },
            "theme": {
                "color": "#007bff"
            }
        };
        var rzp = new Razorpay(options);
        rzp.open();
    }

    // Function to update paid_amount in the database
    function updatePaidAmountInDB() {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Update the paidAmount variable
                paidAmount += emi; // Increase paid amount by EMI
                // Recalculate balance amount and update pie chart
                balanceAmount = payableAmount - paidAmount;
                updatePieChart();
            }
        };
        xhttp.open("GET", "update_paid_amount.php?emi=" + emi, true);
        xhttp.send();
    }
</script>

</body>
</html>
