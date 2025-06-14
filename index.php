<?php
session_start();
require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hiring Cafe - Find Your Dream Job</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Arial', sans-serif; }
        body { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        header { background: #2c3e50; color: white; padding: 20px; text-align: center; border-radius: 10px; }
        header h1 { font-size: 2.5em; }
        nav { display: flex; justify-content: center; gap: 20px; margin: 20px 0; }
        nav a { color: white; text-decoration: none; font-weight: bold; padding: 10px 20px; background: #3498db; border-radius: 5px; transition: background 0.3s; }
        nav a:hover { background: #2980b9; }
        .section { background: white; padding: 20px; margin: 20px 0; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .job-card, .candidate-card { border: 1px solid #ddd; padding: 15px; margin: 10px 0; border-radius: 5px; transition: transform 0.3s; }
        .job-card:hover, .candidate-card:hover { transform: scale(1.02); }
        .job-card h3, .candidate-card h3 { color: #2c3e50; }
        button { padding: 10px 20px; background: #e74c3c; color: white; border: none; border-radius: 5px; cursor: pointer; transition: background 0.3s; }
        button:hover { background: #c0392b; }
        @media (max-width: 768px) { .container { padding: 10px; } header h1 { font-size: 1.8em; } nav { flex-direction: column; } }
    </style>
</head>
<body>
    <header>
        <h1>Hiring Cafe</h1>
        <nav>
            <a href="javascript:navigate('index.php')">Home</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="javascript:navigate('profile.php')">Profile</a>
                <a href="javascript:navigate('logout.php')">Logout</a>
            <?php else: ?>
                <a href="javascript:navigate('signup.php')">Sign Up</a>
                <a href="javascript:navigate('login.php')">Login</a>
            <?php endif; ?>
        </nav>
    </header>
    <div class="container">
        <div class="section">
            <h2>Trending Jobs</h2>
            <?php
            $stmt = $conn->query("SELECT j.*, c.company_name FROM jobs j JOIN company_profiles c ON j.company_id = c.id ORDER BY posted_at DESC LIMIT 5");
            while ($job = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<div class='job-card'>";
                echo "<h3>" . htmlspecialchars($job['title']) . "</h3>";
                echo "<p>Company: " . htmlspecialchars($job['company_name']) . "</p>";
                echo "<p>" . htmlspecialchars($job['description']) . "</p>";
                echo "<button onclick=\"navigate('apply.php?job_id=" . $job['id'] . "')\">Apply Now</button>";
                echo "</div>";
            }
            ?>
        </div>
        <div class="section">
            <h2>Top Candidates</h2>
            <?php
            $stmt = $conn->query("SELECT * FROM candidate_profiles ORDER BY id DESC LIMIT 5");
            while ($candidate = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<div class='candidate-card'>";
                echo "<h3>" . htmlspecialchars($candidate['full_name']) . "</h3>";
                echo "<p>Skills: " . htmlspecialchars($candidate['skills']) . "</p>";
                echo "<button onclick=\"navigate('candidate.php?id=" . $candidate['id'] . "')\">View Profile</button>";
                echo "</div>";
            }
            ?>
        </div>
    </div>
    <script>
        function navigate(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>
