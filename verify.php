<?php
require_once 'config.php';

if (!isset($_GET['token'])) {
    die('No verification token provided');
}

$token = $_GET['token'];

$stmt = $conn->prepare("UPDATE users SET is_verified = 1, verification_token = NULL WHERE verification_token = ?");
$stmt->bind_param("s", $token);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    header('Location: login.php?verified=1');
} else {
    die('Invalid or expired verification token');
}

$stmt->close();
$conn->close();
?>
