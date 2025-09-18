// Simple navigate handler
function navigate(page) {
  alert("Navigate to: " + page);
  // In a real app: window.location.href = page + ".html";
}

// Features data
const features = [
  { icon: "🔗", title: "Share Notes", description: "Upload and share your study notes with fellow students across different courses and subjects." },
  { icon: "💬", title: "Get Feedback", description: "Receive valuable feedback from lecturers and peers to improve your note-taking skills." },
  { icon: "🏆", title: "Leaderboards", description: "Climb the rankings by contributing quality notes and helping your academic community." },
  { icon: "👥", title: "Collaborate", description: "Exchange notes with classmates and build a stronger learning network together." },
  { icon: "📖", title: "Organize", description: "Keep all your notes organized by subject, course, and semester for easy access." },
  { icon: "⭐", title: "Quality Assured", description: "All notes go through our review process to ensure high-quality educational content." }
];

// Render features dynamically
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
