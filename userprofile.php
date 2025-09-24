<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - NoteShare</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            min-height: 100vh;
            padding: 20px;
        }

        .profile-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .profile-header {
            background: linear-gradient(135deg, #ffffff 0%, #ffffff 100%);
            padding: 30px;
            color: rgb(0, 0, 0);
            text-align: center;
            position: relative;
        }

        .profile-title {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .profile-subtitle {
            font-size: 14px;
            opacity: 0.9;
        }

        .profile-info {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 30px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 20px;
            backdrop-filter: blur(10px);
        }

        .user-avatar {
            width: 80px;
            height: 80px;
            background: #20c997;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
            color: white;
            margin-right: 20px;
        }

        .user-details h2 {
            font-size: 24px;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .user-role {
            font-size: 14px;
            opacity: 0.9;
        }

        .user-handle {
            font-size: 12px;
            opacity: 0.7;
            margin-top: 2px;
        }

        .edit-btn {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: rgb(0, 0, 0);
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .edit-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-1px);
        }

        .profile-content {
            padding: 30px;
        }

        .section {
            margin-bottom: 40px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .info-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        .info-label {
            font-size: 12px;
            font-weight: 500;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 14px;
            color: #333;
            font-weight: 500;
        }

        .personal-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .stat-card {
            background: white;
            border: 2px solid #f0f0f0;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            border-color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
        }

        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card.notes .stat-number { color: #007bff; }
        .stat-card.downloads .stat-number { color: #28a745; }
        .stat-card.rating .stat-number { color: #ffc107; }
        .stat-card.rank .stat-number { color: #dc3545; }

        .back-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            background: white;
            border: 1px solid #ddd;
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            color: #333;
            text-decoration: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: #f8f9fa;
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .personal-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }
            
            .profile-info {
                flex-direction: column;
                text-align: center;
            }
            
            .user-avatar {
                margin-right: 0;
                margin-bottom: 15px;
            }
        }

        .password-hidden {
            font-size: 18px;
            letter-spacing: 2px;
        }
    </style>
<?php
// Start the session to access the user ID
session_start();

// Check if the user is logged in. If not, redirect to the login page.
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

// Database connection details (use the same ones from login.php)
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

// Get the user's ID from the session
$user_id = $_SESSION['user_id'];

// Fetch user data from the database using the stored ID
$stmt = $conn->prepare("SELECT id, username, email, phone, dob, role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    // This should not happen if the session ID is valid
    die("User not found.");
}

$stmt->close();
$conn->close();

// We need to fetch stats from other tables if they exist
// This is an example, you need to write queries to get these values
// For now, we'll use placeholder values.
$notesShared = 42;
$downloads = 156;
$avgRating = 3.8;
$rank = 15;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    </head>
<body>
    <a href="#" class="back-btn" onclick="goBack()">← Back</a>

    <div class="profile-container">
        <div class="profile-header">
            <h1 class="profile-title">User Profile</h1>
            <p class="profile-subtitle">Manage your account settings and preferences</p>
            
            <div class="profile-info">
                <div style="display: flex; align-items: center;">
                    <div class="user-avatar" id="userAvatar"><?php echo strtoupper(substr($user['username'], 0, 2)); ?></div>
                    <div class="user-details">
                        <h2 id="userName"><?php echo htmlspecialchars($user['username']); ?></h2>
                        <div class="user-role" id="userRole"><?php echo htmlspecialchars($user['role']); ?></div>
                        <div class="user-handle" id="userHandle">@<?php echo htmlspecialchars($user['username']); ?></div>
                    </div>
                </div>
                <button class="edit-btn" onclick="editProfile()">
                    <span>✏️</span> Edit Profile
                </button>
            </div>
        </div>

        <div class="profile-content">
            <div class="section">
                <h3 class="section-title">Account Information (Read-only)</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">User ID</div>
                        <div class="info-value" id="userId"><?php echo htmlspecialchars($user['id']); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Username</div>
                        <div class="info-value" id="username"><?php echo htmlspecialchars($user['username']); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Birth Date</div>
                        <div class="info-value" id="birthDate"><?php echo htmlspecialchars($user['dob']); ?></div>
                    </div>
                </div>
            </div>

            <div class="section">
                <h3 class="section-title">Personal Information</h3>
                <div class="personal-grid">
                    <div class="info-item">
                        <div class="info-label">Email Address</div>
                        <div class="info-value" id="emailAddress"><?php echo htmlspecialchars($user['email']); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Phone Number</div>
                        <div class="info-value" id="phoneNumber"><?php echo htmlspecialchars($user['phone']); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Password</div>
                        <div class="info-value password-hidden">••••••••</div>
                    </div>
                </div>
            </div>

            <div class="section">
                <h3 class="section-title">Account Statistics</h3>
                <div class="stats-grid">
                    <div class="stat-card notes">
                        <div class="stat-number" id="notesShared"><?php echo $notesShared; ?></div>
                        <div class="stat-label">Notes Shared</div>
                    </div>
                    <div class="stat-card downloads">
                        <div class="stat-number" id="downloads"><?php echo $downloads; ?></div>
                        <div class="stat-label">Downloads</div>
                    </div>
                    <div class="stat-card rating">
                        <div class="stat-number" id="avgRating"><?php echo $avgRating; ?></div>
                        <div class="stat-label">Avg Rating</div>
                    </div>
                    <div class="stat-card rank">
                        <div class="stat-number" id="rank"><?php echo $rank; ?></div>
                        <div class="stat-label">Rank</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Go back function can stay as it is or be simplified
        function goBack() {
            window.history.back();
        }

        // Edit profile function
        function editProfile() {
            alert('Edit Profile functionality would open a form to modify personal information.');
        }

        // The JavaScript part that loads static data is now removed because PHP handles it
    </script>
</body>
</html>
