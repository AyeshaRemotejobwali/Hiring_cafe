<?php
session_start();
require_once 'db.php';

// Enable error reporting for debugging (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if user is logged in and is a recruiter
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'recruiter') {
    echo "<script>alert('Access denied. Please log in as a recruiter.'); navigate('login.php');</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate form inputs
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $requirements = trim($_POST['requirements'] ?? '');
        $salary_range = trim($_POST['salary_range'] ?? '');
        $job_type = $_POST['job_type'] ?? '';
        $location = trim($_POST['location'] ?? '');

        if (empty($title) || empty($description) || empty($requirements) || empty($salary_range) || empty($job_type) || empty($location)) {
            $error = "All fields are required.";
        } elseif (!in_array($job_type, ['full-time', 'part-time', 'remote'])) {
            $error = "Invalid job type selected.";
        } else {
            // Get company ID
            $stmt = $conn->prepare("SELECT id FROM company_profiles WHERE user_id = ?");
            $stmt->execute([$user_id]);
            $company = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$company) {
                $error = "Please create a company profile before posting a job.";
            } else {
                // Insert job
                $stmt = $conn->prepare("
                    INSERT INTO jobs (company_id, title, description, requirements, salary_range, job_type, location)
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$company['id'], $title, $description, $requirements, $salary_range, $job_type, $location]);
                $success = "Job posted successfully!";
            }
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Job - Hiring Cafe</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Arial', sans-serif; }
        body { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); }
        .container { max-width: 800px; margin: 50px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #2c3e50; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #34495e; font-weight: bold; }
        input, textarea, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; }
        textarea { resize: vertical; min-height: 100px; }
        button { width: 100%; padding: 12px; background: #3498db; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; transition: background 0.3s; }
        button:hover { background: #2980b9; }
        .error { color: #e74c3c; text-align: center; margin-bottom: 15px; }
        .success { color: #2ecc71; text-align: center; margin-bottom: 15px; }
        a { color: #3498db; text-decoration: none; }
        a:hover { text-decoration: underline; }
        @media (max-width: 768px) {
            .container { margin: 20px; padding: 15px; }
            h2 { font-size: 1.5em; }
            button { font-size: 14px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Post a Job</h2>
        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="success"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Job Title</label>
                <input type="text" name="title" value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
            </div>
            <div class="form-group">
                <label>Requirements</label>
                <textarea name="requirements" required><?php echo isset($_POST['requirements']) ? htmlspecialchars($_POST['requirements']) : ''; ?></textarea>
            </div>
            <div class="form-group">
                <label>Salary Range</label>
                <input type="text" name="salary_range" value="<?php echo isset($_POST['salary_range']) ? htmlspecialchars($_POST['salary_range']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label>Job Type</label>
                <select name="job_type" required>
                    <option value="">Select Job Type</option>
                    <option value="full-time" <?php echo (isset($_POST['job_type']) && $_POST['job_type'] === 'full-time') ? 'selected' : ''; ?>>Full-Time</option>
                    <option value="part-time" <?php echo (isset($_POST['job_type']) && $_POST['job_type'] === 'part-time') ? 'selected' : ''; ?>>Part-Time</option>
                    <option value="remote" <?php echo (isset($_POST['job_type']) && $_POST['job_type'] === 'remote') ? 'selected' : ''; ?>>Remote</option>
                </select>
            </div>
            <div class="form-group">
                <label>Location</label>
                <input type="text" name="location" value="<?php echo isset($_POST['location']) ? htmlspecialchars($_POST['location']) : ''; ?>" required>
            </div>
            <button type="submit">Post Job</button>
        </form>
        <p style="text-align: center; margin-top: 15px;">
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
