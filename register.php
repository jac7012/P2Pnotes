<?php
// Start a session
session_start();

// Database connection details (replace with your actual credentials)
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "P2Pnote.sql";

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from the POST request
$username = $_POST['username'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$dob = $_POST['dob'];
$password = $_POST['password'];
$confirmPassword = $_POST['confirmPassword'];
$accountType = $_POST['accountType'];

// **Validation**
// 1. Check if passwords match
if ($password !== $confirmPassword) {
    die("Passwords do not match!");
}

// 2. Hash the password for security
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// 3. Check if the username or email already exists in the database
$stmt_check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
$stmt_check->bind_param("ss", $username, $email);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    die("Username or Email already exists!");
}
$stmt_check->close();

// Insert the new user into the database
$stmt_insert = $conn->prepare("INSERT INTO users (username, email, phone, dob, password, role) VALUES (?, ?, ?, ?, ?, ?)");
$stmt_insert->bind_param("ssssss", $username, $email, $phone, $dob, $hashed_password, $accountType);

if ($stmt_insert->execute()) {
    echo "Account created successfully!";
    // Optional: Redirect to a success page or the login page
    header("Location: index.html?success=registration");
} else {
    echo "Error: " . $stmt_insert->error;
}

$stmt_insert->close();
$conn->close();
?>
