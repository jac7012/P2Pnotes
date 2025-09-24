<?php
session_start();

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "note_exchange_db";

$conn = new mysqli($host, $username, $password, $database);

$stats = [
    'active_students' => 0,
    'notes_shared' => 0,
    'universities' => 500, //Static
    'satisfaction_rate' => 97.5 //Static 
];

if (!$conn->connect_error) {
    // Get active students count
    $studentsQuery = "SELECT COUNT(*) as count FROM Student WHERE studentStatus = 'active'";
    $result = $conn->query($studentsQuery);
    if ($result) {
        $stats['active_students'] = $result->fetch_assoc()['count'];
    }
    
    // Get total notes shared (approved notes)
    $notesQuery = "SELECT COUNT(*) as count FROM Note WHERE noteVerifiedStatus = 'approved'";
    $result = $conn->query($notesQuery);
    if ($result) {
        $stats['notes_shared'] = $result->fetch_assoc()['count'];
    }
    
    $conn->close();
}

// Check if user is logged in 
$isLoggedIn = isset($_SESSION['userID']);
$userRole = $isLoggedIn ? $_SESSION['role'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Peer-to-Peer Notes Exchange</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      line-height: 1.6;
      color: #333;
    }

    h1, h2 {
      color: #1a237e;
    }

    .btn {
      display: inline-block;
      padding: 12px 24px;
      margin: 5px;
      border-radius: 8px;
      border: 2px solid #1a237e;
      font-size: 16px;
      cursor: pointer;
      transition: 0.3s;
      text-decoration: none;
    }
    .btn-primary {
      background: #1a237e;
      color: white;
    }
    .btn-primary:hover {
      background: #0d1757;
    }
    .btn-outline {
      background: white;
      color: #1a237e;
    }
    .btn-outline:hover {
      background: #f0f0f0;
    }

    .hero {
      background: linear-gradient(to bottom, #e3f2fd, #fff);
      padding: 60px 20px;
      text-align: center;
    }
    .hero img {
      max-width: 100%;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .features, .stats, .cta {
      padding: 60px 20px;
      text-align: center;
    }

    .features-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      grid-template-rows: repeat(2, 1fr);
      gap: 20px;
      margin-top: 40px;
      max-width: 900px;
      margin-left: auto;
      margin-right: auto;
    }
    .feature-card {
      border: 1px solid #ddd;
      border-radius: 12px;
      padding: 20px;
      transition: 0.3s;
    }
    .feature-card:hover {
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }
    .feature-icon {
      font-size: 30px;
      margin-bottom: 10px;
      color: #1e88e5;
    }

    .stats {
      background: #e3f2fd;
    }
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      gap: 20px;
    }
    .stat-number {
      font-size: 24px;
      font-weight: bold;
      color: #1a237e;
    }

    .user-info {
      position: absolute;
      top: 20px;
      right: 20px;
      background: rgba(255, 255, 255, 0.9);
      padding: 10px 20px;
      border-radius: 20px;
      font-size: 14px;
    }

    .nav-buttons {
      margin-top: 20px;
    }

    @media (max-width: 768px) {
      .features-grid {
        grid-template-columns: 1fr;
      }
      
      .user-info {
        position: relative;
        top: 0;
        right: 0;
        margin-bottom: 20px;
      }
    }
  </style>
</head>
<body>

  <?php if ($isLoggedIn): ?>
    <div class="user-info">
      Welcome, <?php echo htmlspecialchars($userRole); ?>! 
      <a href="dashboard.php" class="btn" style="padding: 5px 10px; font-size: 12px; margin-left: 10px;">Dashboard</a>
      <a href="logout.php" class="btn" style="padding: 5px 10px; font-size: 12px; margin-left: 5px;">Logout</a>
    </div>
  <?php endif; ?>

  <section class="hero">
    <h1>P2P Notes Exchange<br><span style="color:#1e88e5;">Platform</span></h1>
    <p>Join thousands of students sharing knowledge, 
    and building a stronger academic community together.</p>
    
    <div class="nav-buttons">
      <?php if (!$isLoggedIn): ?>
        <a href="register.php" class="btn btn-primary">Get Started</a>
        <a href="login.php" class="btn btn-outline">Login</a>
      <?php else: ?>
        <a href="dashboard.php" class="btn btn-primary">Go to Dashboard</a>
        <a href="browse_notes.php" class="btn btn-outline">Browse Notes</a>
      <?php endif; ?>
    </div>
    
    <div style="margin-top:30px;">
      <img src="https://learningwithangie.com/wp-content/uploads/2023/03/best-aesthetic-note-taking-app-1.png" 
           alt="Note Sharing Illustration"
           style="max-width: 1000px; width: 100%; height: auto;">
    </div>
  </section>
  
  <section class="features">
    <h2>Everything You Need to Succeed</h2>
    <p>Our platform provides all the tools necessary for effective note sharing 
    and academic collaboration.</p>
    <div class="features-grid" id="features"></div>
  </section>

  <section class="stats">
    <div class="stats-grid">
      <div>
        <div class="stat-number"><?php echo number_format($stats['active_students']); ?>+</div>
        <p>Active Students</p>
      </div>
      <div>
        <div class="stat-number"><?php echo number_format($stats['notes_shared']); ?>+</div>
        <p>Notes Shared</p>
      </div>
      <div>
        <div class="stat-number"><?php echo number_format($stats['universities']); ?>+</div>
        <p>Universities</p>
      </div>
      <div>
        <div class="stat-number"><?php echo $stats['satisfaction_rate']; ?>%</div>
        <p>Satisfaction Rate</p>
      </div>
    </div>
  </section>

  <section class="cta">
    <h2>Ready to Start Sharing?</h2>
    <p>Join our community today and start benefiting from collaborative learning.</p>
    <?php if (!$isLoggedIn): ?>
      <a href="register.php" class="btn btn-primary">Create Your Account</a>
    <?php else: ?>
      <a href="browse_notes.php" class="btn btn-primary">Browse Notes</a>
      <a href="upload_note.php" class="btn btn-outline">Upload Notes</a>
    <?php endif; ?>
  </section>

  <script>
    const features = [
      { icon: "🔗", title: "Share Notes", description: "Upload and share your study notes with fellow students across different courses and subjects." },
      { icon: "💬", title: "Get Feedback", description: "Receive valuable feedback from lecturers and peers to improve your note-taking skills." },
      { icon: "🏆", title: "Leaderboards", description: "Climb the rankings by contributing quality notes and helping your academic community." },
      { icon: "👥", title: "Collaborate", description: "Exchange notes with classmates and build a stronger learning network together." },
      { icon: "📖", title: "Organise", description: "Keep all your notes organised by subject, course, and semester for easy access." },
      { icon: "⭐", title: "Quality Assured", description: "All notes go through our review process to ensure high-quality educational content." }
    ];

    const featuresContainer = document.getElementById("features");
    features.forEach(f => {
      const card = document.createElement("div");
      card.className = "feature-card";
      card.innerHTML = `
        <div class="feature-icon">${f.icon}</div>
        <h3>${f.title}</h3>
        <p>${f.description}</p>
      `;
      featuresContainer.appendChild(card);
    });

    function navigate(page) {
      if (page === 'register') {
        window.location.href = 'register.php';
      } else if (page === 'login') {
        window.location.href = 'login.php';
      }
    }
  </script>
</body>
</html>
