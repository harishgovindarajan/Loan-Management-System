<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Application Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        .container {
            text-align: center;
            width: 100%;
            margin-top: 50px; /* Adjust as needed */
            margin-bottom: 50px;
        }

        h1 {
            margin-top: 0;
            color: #333; /* Ensure contrast with background */
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
            color: #333; /* Ensure contrast with background */
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="number"],
        input[type="file"],
        textarea,
        input[type="date"] {
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

        button[type="button"] {
            background-color: #3498db;
            color: #fff;
            border: none;
            padding: 15px;
            text-transform: uppercase;
            cursor: pointer;
            width: 100%;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        button[type="button"]:hover {
            background-color: #258cd1;
        }

        button[type="button"]:focus {
            outline: none;
        }
    </style>
</head>

<body onload="populateCurrentDate()">
    <div class="container">
        <h1>Loan Application Form</h1>
        <div class="application-form">
            <form id="loanApplicationForm" enctype="multipart/form-data">
                <div class="input-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" placeholder="Enter your full name" required>
                </div>
                <div class="input-group">
                    <label for="email">Email</label>
                    <!-- PHP code to fetch and display email from session -->
                    <?php
                    session_start(); // Start the session
                    $email = isset($_SESSION['borrower_email']) ? $_SESSION['borrower_email'] : '';
                    ?>
                    <input type="email" id="email" placeholder="Email" value="<?php echo $email; ?>" required readonly>
                </div>
                <div class="input-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" placeholder="Enter your phone number" required>
                </div>
                <div class="input-group">
                    <label for="adhar">Aadhar Card Number</label>
                    <input type="text" id="adhar" placeholder="Enter your Aadhar card number" required>
                </div>
                <div class="input-group">
                    <label for="pan">PAN Card Number</label>
                    <input type="text" id="pan" placeholder="Enter your PAN card number" required>
                </div>
                <div class="input-group">
                    <label for="amount">Loan Amount</label>
                    <input type="number" id="amount" placeholder="Enter loan amount" required>
                </div>
                <div class="input-group">
                    <label for="period">Loan Period (in months)</label>
                    <input type="number" id="period" placeholder="Enter loan period" required>
                </div>
                <div class="input-group">
                    <label for="purpose">Purpose of Loan</label>
                    <textarea id="purpose" placeholder="Enter the purpose of your loan (minimum 100 words)" required></textarea>
                </div>
                <div class="input-group">
                    <label for="date">Date</label>
                    <?php
                    $date = date("Y-m-d");
                    ?>
                    <input type="date" id="date" value="<?php echo $date; ?>" readonly>
                </div>
                <div class="input-group">
                    <label for="file1">Aadhar Card (PDF)</label>
                    <input type="file" id="file1" name="file1" accept=".pdf" required>
                </div>
                <div class="input-group">
                    <label for="file2">PAN Card (PDF)</label>
                    <input type="file" id="file2" name="file2" accept=".pdf" required>
                </div>
                <div class="input-group">
                    <label for="file3">Bank Transaction Slip (PDF)</label>
                    <input type="file" id="file3" name="file3" accept=".pdf" required>
                </div>
                <div class="input-group">
                    <label for="file4">Security Document (PDF)</label>
                    <input type="file" id="file4" name="file4" accept=".pdf" required>
                </div>
                <div class="input-group">
                    <label for="termsCheckbox">
                        <input type="checkbox" id="termsCheckbox" required>
                        I have read and accept all the terms and conditions
                    </label>
                </div>
                <button type="button" onclick="submitApplication()">Submit Application</button>
            </form>
        </div>
    </div>

    <script>
        function submitApplication() {
            var name = document.getElementById('name');
            var email = document.getElementById('email');
            var phone = document.getElementById('phone');
            var adhar = document.getElementById('adhar');
            var pan = document.getElementById('pan');
            var amount = document.getElementById('amount');
            var period = document.getElementById('period');
            var purpose = document.getElementById('purpose');
            var date = document.getElementById('date');
            var file1 = document.getElementById('file1');
            var file2 = document.getElementById('file2');
            var file3 = document.getElementById('file3');
            var file4 = document.getElementById('file4');
            var termsCheckbox = document.getElementById('termsCheckbox');

            var requiredFields = [name, email, phone, adhar, pan, amount, period, purpose, file1, file2, file3, file4];

            // Check if all fields are filled
            var allFieldsFilled = true;
            requiredFields.forEach(function (field) {
                if (!field.value) {
                    field.classList.add('error');
                    allFieldsFilled = false;
                } else {
                    field.classList.remove('error');
                }
            });

            // Check if the checkbox is checked
            if (!termsCheckbox.checked) {
                termsCheckbox.parentNode.classList.add('error');
                allFieldsFilled = false;
            } else {
                termsCheckbox.parentNode.classList.remove('error');
            }

            // If all required fields are filled, proceed with form submission
            if (allFieldsFilled) {
                // Display a confirmation message
                if (confirm(`Are you sure you want to submit the application for ${email.value}?`)) {
                    // Create a FormData object to send the form data
                    var formData = new FormData();
                    formData.append('name', name.value);
                    formData.append('email', email.value);
                    formData.append('phone', phone.value);
                    formData.append('adhar', adhar.value);
                    formData.append('pan', pan.value);
                    formData.append('amount', amount.value);
                    formData.append('period', period.value);
                    formData.append('purpose', purpose.value);
                    formData.append('date', date.value);
                    formData.append('file1', file1.files[0]);
                    formData.append('file2', file2.files[0]);
                    formData.append('file3', file3.files[0]);
                    formData.append('file4', file4.files[0]);

                    // Create an XMLHttpRequest object
                    var xhr = new XMLHttpRequest();

                    // Configure the AJAX request
                    xhr.open('POST', 'borrowerapplication.php', true);

                    // Define the callback function for when the request is complete
                    xhr.onload = function () {
                        if (xhr.status === 200) {
                            // If the request was successful, display a success message
                            alert(xhr.responseText);
                            // Reset the form after submission
                            document.getElementById('loanApplicationForm').reset();
                        } else {
                            // If there was an error, display an error message
                            alert('Error: ' + xhr.statusText);
                        }
                    };

                    // Send the request with the form data
                    xhr.send(formData);
                }
            } else {
                // Alert the user to fill in all required fields
                alert('Please fill in all required fields.');
            }
        }
    </script>
</body>

</html>
