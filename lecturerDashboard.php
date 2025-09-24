<?php
session_start();

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'lecturer') {
    header("Location: login.php");
    exit();
}

$host = "localhost";
$username = "root";
$password = "";
$database = "note_exchange_db";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$lecturerID = $_SESSION['userID'];

$lecturerQuery = "SELECT l.*, u.role 
                  FROM Lecturer l 
                  JOIN User u ON l.userID = u.userID 
                  WHERE l.userID = ?";
$stmt = $conn->prepare($lecturerQuery);
$stmt->bind_param("s", $lecturerID);
$stmt->execute();
$lecturerResult = $stmt->get_result();
$lecturer = $lecturerResult->fetch_assoc();

$pendingNotesQuery = "SELECT n.*, s.studentFirstName, s.studentLastName 
                      FROM Note n 
                      JOIN Student s ON n.noteUploaderID = s.userID 
                      WHERE n.noteVerifiedStatus = 'pending' 
                      ORDER BY n.noteID DESC";
$pendingNotesResult = $conn->query($pendingNotesQuery);
$pendingNotesCount = $pendingNotesResult->num_rows;

// Get approved notes count for this lecturer's reviews
$approvedNotesQuery = "SELECT COUNT(*) as approved_count 
                       FROM Note 
                       WHERE noteVerifiedStatus = 'approved'";
$approvedResult = $conn->query($approvedNotesQuery);
$approvedCount = $approvedResult->fetch_assoc()['approved_count'];

// total feedback count
$feedbackQuery = "SELECT COUNT(*) as feedback_count FROM Feedback";
$feedbackResult = $conn->query($feedbackQuery);
$feedbackCount = $feedbackResult->fetch_assoc()['feedback_count'];

//  review history (approved and rejected notes)
$reviewHistoryQuery = "SELECT n.*, s.studentFirstName, s.studentLastName 
                       FROM Note n 
                       JOIN Student s ON n.noteUploaderID = s.userID 
                       WHERE n.noteVerifiedStatus IN ('approved', 'rejected') 
                       ORDER BY n.noteID DESC 
                       LIMIT 20";
$reviewHistoryResult = $conn->query($reviewHistoryQuery);

//  note verification
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && isset($_POST['noteID'])) {
        $noteID = $_POST['noteID'];
        $action = $_POST['action'];
        
        if ($action == 'approve') {
            $updateQuery = "UPDATE Note SET noteVerifiedStatus = 'approved' WHERE noteID = ?";
            $message = "Note approved successfully!";
        } elseif ($action == 'reject') {
            $updateQuery = "UPDATE Note SET noteVerifiedStatus = 'rejected' WHERE noteID = ?";
            $message = "Note rejected successfully!";
        } else {
            $errorMessage = "Invalid action!";
        }
        
        if (isset($updateQuery)) {
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("s", $noteID);
            if ($stmt->execute()) {
                $successMessage = $message;
                // Refresh data
                header("Refresh:0");
                exit();
            } else {
                $errorMessage = "Error updating note status!";
            }
        }
    }
}

// Set active tab
$activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'pending';
?>
