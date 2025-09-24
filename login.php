<?php
session_start();
include("dbConnect.php");
$conn = OpenCon();

$errorMsg = '';
//Check the form via POST method 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userID = trim($_POST['userID']);
    $password = trim($_POST['password']); 

    //makesure the fields like userID and password are filled in, 
    //if empty then will propmpt error message 
    if (empty($userID) || empty($password)) {
        $errorMsg = "Please fill in your ID and Password.";
    } else {
        //not empty then will check from user database 
        $userSQL = "SELECT role FROM User WHERE userID = '$userID'";
        $userResult = mysqli_query($conn, $userSQL);
 //if userID not found then will prompt error message 
        if (mysqli_num_rows($userResult) == 0) {
            $errorMsg = "User ID not found. Please sign up first or contact the admin.";
        } else {
            //if userID found then can proceed to check the ID and password 
            $row = mysqli_fetch_assoc($userResult);
            $role = $row['role'];
// Determine which table to query based on the user type 
// Check the user type is correct or  not 
            if ($role == 'student') {
                $loginSQL = "SELECT * FROM Student WHERE userID = '$userID'";
                $passwordField = 'studentPassword';
                $idField = 'studentID';
            } elseif ($role == 'lecturer') {
                $loginSQL = "SELECT * FROM Lecturer WHERE userID = '$userID'";
                $passwordField = 'lecturerPassword';
                $idField = 'lecturerID';
            } elseif ($role == 'admin') {
                $loginSQL = "SELECT * FROM Admin WHERE userID = '$userID'";
                $passwordField = 'adminPassword';
                $idField = 'adminID';
            } else {
                //if not correct, then will prompt error message 
                $errorMsg = "Invalid user type.";
                $loginSQL = null;
            }
 // If a valid login query was generated, proceed to authenticate
            if ($loginSQL) {
                $loginResult = mysqli_query($conn, $loginSQL);
                if (!$loginResult) {
                    die("Error in query: " . mysqli_error($conn));
                }
                // Check if user exists in the specific role table 
                if (mysqli_num_rows($loginResult) > 0) {
                    $user = mysqli_fetch_assoc($loginResult);
                    // Check the password (hashed)
                    if ($password === $user[$passwordField]) { // Replace with password_verify for hashed passwords
                        $_SESSION['role'] = $role;
                        $_SESSION['userID'] = $userID;
                        $_SESSION['roleID'] = $user[$idField];
  //redirect the user to their own dashboard if they success to login  
                        if ($role == 'student') {
                            header("Location: studentDashboard.php");
                        } elseif ($role == 'lecturer') {
                            header("Location: lecturerDashboard.php");
                        } elseif ($role == 'admin') {
                            header("Location: adminDashboard.php");
                        }
                        exit();
                    } else {
                        // Incorrect password error message
                        $errorMsg = "Invalid password.";
                    }
                } else {
                       //if user does  not exist then will prompt error message 
                    $errorMsg = "User not found in the $role table.";
                }
            }
        }
    }
}

CloseCon($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Auth Forms</title>
  <link rel="stylesheet" href="login.css">
      <script>
        function validateForm(event) {
            const userID = document.getElementById('userID').value.trim();
            const password = document.getElementById('password').value.trim();

            if (!userID || !password) {
                alert('Please fill in both User ID and Password.');
                event.preventDefault();
            }
        }
    </script>
</head>

<body class="auth-body">
  <div class="auth-container">

    <!-- Header -->
    <div class="auth-header">
      <h2 class="auth-title">Welcome to NoteShare</h2>
      <p class="auth-subtitle">Sign in to your account or create a new one</p>
    </div>

    <!-- Card -->
    <div class="auth-card">
      <div class="auth-card-header">
        <h3 class="auth-card-title">Authentication</h3>
        <p class="auth-card-desc">Enter your credentials to access your account</p>
      </div>
      <div class="auth-card-body">

        <!-- Tabs header -->
        <div class="auth-tabs">
          <button class="tab tab-active">Login</button>
          <button class="tab">Register</button>
        </div>

        <!-- Login Form -->
        <form class="auth-form" action="login.php" method="POST" onsubmit="validateForm(event)">
          <div class="form-group">
            <label for="userID" class="form-label">User ID</label>
            <input id="userID" name="userID" type="text" class="form-input" placeholder="Enter your username" required />          </div>
          <div class="form-group">
            <label for="login-password" class="form-label">Password</label>
            <input id="password" name="password" type="password" class="form-input" placeholder="Enter your password" required />
          </div>
          <button type="submit" class="btn-primary">Sign In</button>
        </form>

      </div>
    </div>
  </div>
</body>
</html>
