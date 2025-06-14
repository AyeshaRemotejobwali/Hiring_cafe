<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'recruiter') {
    header("Location: login.php");
    exit;
}
$candidate_id = $_GET['candidate_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $job_id = $_POST['job_id'];
    $interview_time = $_POST['interview_time'];
    $interview_type = $_POST['interview_type'];
    $stmt = $conn->prepare("SELECT id FROM company_profiles WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $company = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt = $conn->prepare("INSERT INTO interviews (job_id, candidate_id, recruiter_id, interview_time, interview_type) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$job_id, $candidate_id, $company['id'], $interview_time, $interview_type]);
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Interview - Hiring Cafe</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Arial', sans-serif; }
        body { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); }
        .container { max-width: 600px; margin: 50px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #2c3e50; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #34495e; }
        input, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        button { width: 100%; padding: 10px; background: #3498db; color: white; border: none; border-radius: 5px; cursor: pointer; transition: background 0.3s; }
        button:hover { background: #2980b9; }
        @media (max-width: 768px) { .container { margin: 20px; padding: 10px; } }
    </style>
</head>
<body>
    <div class="container">
        <h2>Schedule Interview</h2>
        <form method="POST">
            <div class="form-group">
                <label>Job</label>
                <select name="job_id" required>
                    <?php
                    $stmt = $conn->prepare("SELECT j.id, j.title FROM jobs j JOIN company_profiles c ON j.company_id = c.id WHERE c.user_id = ?");
                    $stmt->execute([$_SESSION['user_id']]);
                    while ($job = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='" . $job['id'] . "'>" . htmlspecialchars($job['title']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Interview Time</label>
                <input type="datetime-local" name="interview_time" required>
            </div>
            <div class="form-group">
                <label>Interview Type</label>
                <select name="interview_type" required>
                    <option value="video">Video</option>
                    <option value="in-person">In-Person</option>
                </select>
            </div>
            <button type="submit">Schedule</button>
        </form>
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
