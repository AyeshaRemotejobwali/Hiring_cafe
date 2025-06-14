<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'candidate') {
    header("Location: login.php");
    exit;
}
$job_id = $_GET['job_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("SELECT id FROM candidate_profiles WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $candidate = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt = $conn->prepare("INSERT INTO applications (job_id, candidate_id) VALUES (?, ?)");
    $stmt->execute([$job_id, $candidate['id']]);
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Job - Hiring Cafe</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Arial', sans-serif; }
        body { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); }
        .container { max-width: 800px; margin: 50px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #2c3e50; margin-bottom: 20px; }
        .job-details { margin-bottom: 20px; }
        button { width: 100%; padding: 10px; background: #e74c3c; color: white; border: none; border-radius: 5px; cursor: pointer; transition: background 0.3s; }
        button:hover { background: #c0392b; }
        @media (max-width: 768px) { .container { margin: 20px; padding: 10px; } }
    </style>
</head>
<body>
    <div class="container">
        <h2>Apply for Job</h2>
        <div class="job-details">
            <?php
            $stmt = $conn->prepare("SELECT j.*, c.company_name FROM jobs j JOIN company_profiles c ON j.company_id = c.id WHERE j.id = ?");
            $stmt->execute([$job_id]);
            $job = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "<h3>" . htmlspecialchars($job['title']) . "</h3>";
            echo "<p>Company: " . htmlspecialchars($job['company_name']) . "</p>";
            echo "<p>" . htmlspecialchars($job['description']) . "</p>";
            ?>
        </div>
        <form method="POST">
            <button type="submit">Apply Now</button>
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
</html><?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'candidate') {
    header("Location: login.php");
    exit;
}
$job_id = $_GET['job_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("SELECT id FROM candidate_profiles WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $candidate = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt = $conn->prepare("INSERT INTO applications (job_id, candidate_id) VALUES (?, ?)");
    $stmt->execute([$job_id, $candidate['id']]);
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Job - Hiring Cafe</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Arial', sans-serif; }
        body { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); }
        .container { max-width: 800px; margin: 50px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #2c3e50; margin-bottom: 20px; }
        .job-details { margin-bottom: 20px; }
        button { width: 100%; padding: 10px; background: #e74c3c; color: white; border: none; border-radius: 5px; cursor: pointer; transition: background 0.3s; }
        button:hover { background: #c0392b; }
        @media (max-width: 768px) { .container { margin: 20px; padding: 10px; } }
    </style>
</head>
<body>
    <div class="container">
        <h2>Apply for Job</h2>
        <div class="job-details">
            <?php
            $stmt = $conn->prepare("SELECT j.*, c.company_name FROM jobs j JOIN company_profiles c ON j.company_id = c.id WHERE j.id = ?");
            $stmt->execute([$job_id]);
            $job = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "<h3>" . htmlspecialchars($job['title']) . "</h3>";
            echo "<p>Company: " . htmlspecialchars($job['company_name']) . "</p>";
            echo "<p>" . htmlspecialchars($job['description']) . "</p>";
            ?>
        </div>
        <form method="POST">
            <button type="submit">Apply Now</button>
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
