<?php
$host = 'localhost';
$user = 'uxgukysg8xcbd';
$password = '6imcip8yfmic';
$dbname = 'dbxibuntgeoz2w';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
