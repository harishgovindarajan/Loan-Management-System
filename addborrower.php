<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Borrower</title>
    <style>
        /* Your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            overflow-x: hidden; /* Prevent horizontal scrolling */
        }

        .container {
            text-align: center;
            margin-top: 50px;
            margin-bottom: 50px;
        }

        h1 {
            margin-top: 0;
            color: #333;
        }

        .application-form {
            max-width: 600px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="number"],
        input[type="date"],
        textarea {
            width: calc(100% - 22px);
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="checkbox"] {
            margin-right: 5px;
        }

        .error {
            border-color: red !important;
        }

        button#addBorrowerBtn {
            background-color: #ff0000; /* Red color */
            color: white;
            border: none;
            padding: 17px 40px; /* Increased padding for a bigger button */
            text-transform: uppercase;
            cursor: pointer;
            width: 100%;
            border-radius: 50px; /* Increased border radius for a rounded button */
            transition: background-color 0.3s ease;
            font-weight: bold; /* Bold text */
        }

        button#addBorrowerBtn:hover {
            background-color: #cc0000; /* Darker red color on hover */
        }

        button#addBorrowerBtn:focus {
            outline: none;
        }

        .success-message {
            color: green;
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add Borrower</h1>
        <div class="application-form">
            <form id="borrowerForm">
                <div class="input-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" placeholder="Enter your full name" required>
                </div>
                <div class="input-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <label for="phone">Phone:</label>
                    <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>
                </div>
                <div class="input-group">
                    <label for="loanAmount">Loan Amount:</label>
                    <input type="number" id="loanAmount" name="loanAmount" min="0" placeholder="Enter loan amount" required>
                </div>
                <div class="input-group">
                    <label for="period">Period (months):</label>
                    <input type="number" id="period" name="period" min="1" placeholder="Enter loan period" required>
                </div>
                <div class="input-group">
                    <label for="loanStartDate">Loan Start Date:</label>
                    <input type="date" id="loanStartDate" name="loanStartDate" required>
                </div>
                <div class="input-group">
                    <label for="interestRate">Interest Rate (%):</label>
                    <input type="number" id="interestRate" name="interestRate" min="0" step="0.01" placeholder="Enter interest rate" required>
                </div>
                <button class="button#addBorrowerBtn" type="button" id="addBorrowerBtn" onclick="addBorrower()">Add Borrower</button>
            </form>
            <div id="successMessage" class="success-message"></div>
        </div>
    </div>

    <script>
        function addBorrower() {
            // Retrieve form data
            var name = document.getElementById("name").value;
            var email = document.getElementById("email").value;
            var phone = document.getElementById("phone").value;
            var loanAmount = document.getElementById("loanAmount").value;
            var period = document.getElementById("period").value;
            var loanStartDate = document.getElementById("loanStartDate").value;
            var interestRate = document.getElementById("interestRate").value;

            // Validate that all fields are filled
            if (name == "" || email == "" || phone == "" || loanAmount == "" || period == "" || loanStartDate == "" || interestRate == "") {
                alert("Please fill in all fields");
                return;
            }

            // Create a new XMLHttpRequest object
            var xhr = new XMLHttpRequest();

            // Configure the request
            xhr.open("POST", "addborrower_process.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            // Define what happens on successful data submission
            xhr.onload = function () {
                if (xhr.status == 200) {
                    // Display success message
                    document.getElementById("successMessage").textContent = "Borrower added successfully!";
                } else {
                    console.error("Error:", xhr.statusText);
                }
            };

            // Define what happens in case of error
            xhr.onerror = function () {
                console.error("Error:", xhr.statusText);
            };

            // Send the request with the form data
            xhr.send("name=" + name + "&email=" + email + "&phone=" + phone + "&loanAmount=" + loanAmount + "&period=" + period + "&loanStartDate=" + loanStartDate + "&interestRate=" + interestRate);
        }
    </script>
</body>
</html>
