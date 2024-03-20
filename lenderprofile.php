<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Password Change Form</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
    }

    .container {
      max-width: 500px;
      margin: 50px auto;
      padding: 20px;
      background-color: #fff;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .form-group {
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      font-size: 16px;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
    }

    button {
      padding: 10px 20px;
      font-size: 16px;
      background-color: #007bff;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    button:hover {
      background-color: #0056b3;
    }

    .error-message {
      color: #ff0000;
      font-size: 14px;
    }

    @media screen and (max-width: 600px) {
      .container {
        margin: 20px auto;
        padding: 10px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Password Change Form</h2>
    <form id="passwordForm" method="post">
      <div class="form-group">
        <label for="name">Name:</label>
        <?php
        session_start(); // Start the session
        // Database connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "lms";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch name from the database corresponding to the email
        $email = isset($_SESSION['lender_email']) ? $_SESSION['lender_email'] : '';
        $name = '';
        if (!empty($email)) {
            $sql = "SELECT name FROM lendersignup WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $name = $row['name'];
            }
        }
        ?>
        <input type="text" id="name" name="name" placeholder="Enter your full name" value="<?php echo $name; ?>" required readonly>
      </div>
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Email" value="<?php echo $email; ?>" required readonly>
      </div>
      <div class="form-group">
        <label for="oldPassword">Old Password:</label>
        <input type="password" id="oldPassword" name="oldPassword" required>
      </div>
      <div class="form-group">
        <label for="newPassword">New Password:</label>
        <input type="password" id="newPassword" name="newPassword" required>
      </div>
      <div class="error-message">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
          // Check if all fields are filled
          if (isset($_POST['oldPassword']) && isset($_POST['newPassword'])) {
              // Database connection
              $servername = "localhost";
              $username = "root";
              $password = "";
              $dbname = "lms";

              $conn = new mysqli($servername, $username, $password, $dbname);
              if ($conn->connect_error) {
                  die("Connection failed: " . $conn->connect_error);
              }

              // Fetch stored password from the database
              $email = isset($_SESSION['lender_email']) ? $_SESSION['lender_email'] : '';
              $storedPassword = '';
              if (!empty($email)) {
                  $sql = "SELECT password FROM lendersignup WHERE email = ?";
                  $stmt = $conn->prepare($sql);
                  $stmt->bind_param("s", $email);
                  $stmt->execute();
                  $result = $stmt->get_result();
                  if ($result->num_rows > 0) {
                      $row = $result->fetch_assoc();
                      $storedPassword = $row['password'];
                  }
              }

              // Check if old password matches the stored password
              $oldPassword = $_POST['oldPassword'];
              if (password_verify($oldPassword, $storedPassword)) {
                  // Old password matches, check new password criteria
                  $newPassword = $_POST['newPassword'];
                  if ($newPassword != $oldPassword && preg_match('/^(?=.*[A-Z])(?=.*[!@#$%^&*])(?=.*[0-9])(?=.{8,})/', $newPassword)) {
                      // Update with new password
                      $newPasswordHashed = password_hash($newPassword, PASSWORD_DEFAULT);
                      $updateSql = "UPDATE lendersignup SET password = ? WHERE email = ?";
                      $updateStmt = $conn->prepare($updateSql);
                      $updateStmt->bind_param("ss", $newPasswordHashed, $email);
                      if ($updateStmt->execute()) {
                          echo "Password updated successfully.";
                      } else {
                          echo "Error updating password: " . $conn->error;
                      }
                  } else {
                      // New password criteria not met
                      echo "New password does not meet the criteria. It must be at least 8 characters long and contain at least one uppercase letter, one special symbol, and one digit.";
                  }
              } else {
                  // Old password does not match
                  echo "Old password is incorrect.";
              }

              $conn->close();
          } else {
              echo "All fields are required.";
          }
        }
        ?>
      </div>
      <button type="submit">Change Password</button>
    </form>
  </div>
</body>
</html>
