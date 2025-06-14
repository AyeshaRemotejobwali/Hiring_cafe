<?php
session_start();
require_once 'db.php';

// Enable error reporting for debugging (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if candidate ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(404);
    echo "<h1>404 - Candidate Not Found</h1><p>Please check the URL or go back to the <a href='index.php'>homepage</a>.</p>";
    exit;
}

$candidate_id = (int)$_GET['id'];
$error = '';

try {
    // Fetch candidate details
    $stmt = $conn->prepare("SELECT cp.*, u.email FROM candidate_profiles cp JOIN users u ON cp.user_id = u.id WHERE cp.id = ?");
    $stmt->execute([$candidate_id]);
    $candidate = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$candidate) {
        http_response_code(404);
        echo "<h1>404 - Candidate Not Found</h1><p>The candidate profile does not exist. Go back to the <a href='index.php'>homepage</a>.</p>";
        exit;
    }
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Profile - Hiring Cafe</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Arial', sans-serif; }
        body { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); }
        .container { max-width: 800px; margin: 50px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #2c3e50; margin-bottom: 20px; }
        .profile-details { margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .profile-details p { margin: 10px 0; color: #34495e; }
        button { padding: 10px 20px; background: #3498db; color: white; border: none; border-radius: 5px; cursor: pointer; transition: background 0.3s; }
        button:hover { background: #2980b9; }
        video { max-width: 100%; border-radius: 5px; margin-top: 10px; }
        a { color: #3498db; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .error { color: #e74c3c; text-align: center; margin-bottom: 15px; }
        @media (max-width: 768px) { 
            .container { margin: 20px; padding: 10px; }
            h2 { font-size: 1.5em; }
            button { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Candidate Profile</h2>
        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php else: ?>
            <div class="profile-details">
                <h3><?php echo htmlspecialchars($candidate['full_name']); ?></h3>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($candidate['email']); ?></p>
                <p><strong>Skills:</strong> <?php echo htmlspecialchars($candidate['skills'] ?? 'Not specified'); ?></p>
                <p><strong>Experience:</strong> <?php echo htmlspecialchars($candidate['experience'] ?? 0); ?> years</p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($candidate['location'] ?? 'Not specified'); ?></p>
                <?php if ($candidate['resume']): ?>
                    <p><strong>Resume:</strong> <a href="<?php echo htmlspecialchars($candidate['resume']); ?>" target="_blank">Download Resume</a></p>
                <?php endif; ?>
                <?php if ($candidate['video_intro']): ?>
                    <p><strong>Video Intro:</strong></p>
                    <video controls>
                        <source src="<?php echo htmlspecialchars($candidate['video_intro']); ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                <?php endif; ?>
            </div>
            <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'recruiter'): ?>
                <button onclick="navigate('schedule_interview.php?candidate_id=<?php echo $candidate_id; ?>')">Schedule Interview</button>
            <?php endif; ?>
        <?php endif; ?>
        <p style="text-align: center; margin-top: 10px;">
            <a href="javascript:navigate('index.php')">Back to Home</a>
        </p>
    </div>
    <script>
        function navigate(url) {
            window.location.href = url;
        }
    </script>
</body>
</html>
