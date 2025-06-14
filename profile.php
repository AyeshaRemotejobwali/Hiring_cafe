<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($user_type === 'candidate') {
        $full_name = $_POST['full_name'];
        $skills = $_POST['skills'];
        $experience = $_POST['experience'];
        $location = $_POST['location'];
        // Handle file uploads
        $resume = $_FILES['resume']['name'] ? 'uploads/' . basename($_FILES['resume']['name']) : null;
        $video_intro = $_FILES['video_intro']['name'] ? 'uploads/' . basename($_FILES['video_intro']['name']) : null;
        if ($resume) move_uploaded_file($_FILES['resume']['tmp_name'], $resume);
        if ($video_intro) move_uploaded_file($_FILES['video_intro']['tmp_name'], $video_intro);
        $stmt = $conn->prepare("INSERT INTO candidate_profiles (user_id, full_name, skills, resume, video_intro, experience, location) VALUES (?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE full_name = ?, skills = ?, resume = ?, video_intro = ?, experience = ?, location = ?");
        $stmt->execute([$user_id, $full_name, $skills, $resume, $video_intro, $experience, $location, $full_name, $skills, $resume, $video_intro, $experience, $location]);
    } else {
        $company_name = $_POST['company_name'];
        $company_description = $_POST['company_description'];
        $location = $_POST['location'];
        $logo = $_FILES['logo']['name'] ? 'uploads/' . basename($_FILES['logo']['name']) : null;
        if ($logo) move_uploaded_file($_FILES['logo']['tmp_name'], $logo);
        $stmt = $conn->prepare("INSERT INTO company_profiles (user_id, company_name, company_description, logo, location) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE company_name = ?, company_description = ?, logo = ?, location = ?");
        $stmt->execute([$user_id, $company_name, $company_description, $logo, $location, $company_name, $company_description, $logo, $location]);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Hiring Cafe</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Arial', sans-serif; }
        body { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); }
        .container { max-width: 800px; margin: 50px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #2c3e50; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #34495e; }
        input, textarea, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        button { width: 100%; padding: 10px; background: #3498db; color: white; border: none; border-radius: 5px; cursor: pointer; transition: background 0.3s; }
        button:hover { background: #2980b9; }
        @media (max-width: 768px) { .container { margin: 20px; padding: 10px; } }
    </style>
</head>
<body>
    <div class="container">
        <h2><?php echo $_SESSION['user_type'] === 'candidate' ? 'Candidate Profile' : 'Company Profile'; ?></h2>
        <form method="POST" enctype="multipart/form-data">
            <?php if ($_SESSION['user_type'] === 'candidate'): ?>
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name" required>
                </div>
                <div class="form-group">
                    <label>Skills</label>
                    <textarea name="skills" required></textarea>
                </div>
                <div class="form-group">
                    <label>Resume</label>
                    <input type="file" name="resume" accept=".pdf">
                </div>
                <div class="form-group">
                    <label>Video Intro</label>
                    <input type="file" name="video_intro" accept="video/*">
                </div>
                <div class="form-group">
                    <label>Experience (Years)</label>
                    <input type="number" name="experience" required>
                </div>
                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location" required>
                </div>
            <?php else: ?>
                <div class="form-group">
                    <label>Company Name</label>
                    <input type="text" name="company_name" required>
                </div>
                <div class="form-group">
                    <label>Company Description</label>
                    <textarea name="company_description" required></textarea>
                </div>
                <div class="form-group">
                    <label>Logo</label>
                    <input type="file" name="logo" accept="image/*">
                </div>
                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location" required>
                </div>
            <?php endif; ?>
            <button type="submit">Save Profile</button>
        </form>
        <?php if ($_SESSION['user_type'] === 'recruiter'): ?>
            <p style="text-align: center; margin-top: 10px;">
                <a href="javascript:navigate('post_job.php')">Post a Job</a>
            </p>
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
