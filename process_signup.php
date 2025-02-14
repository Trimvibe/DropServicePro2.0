<?php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get and sanitize input
$fullName = filter_var($_POST['fullName'], FILTER_SANITIZE_STRING);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$password = $_POST['password'];
$confirmPassword = $_POST['confirmPassword'];

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format']);
    exit;
}

// Validate password
if (strlen($password) < 8) {
    echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters long']);
    exit;
}

if ($password !== $confirmPassword) {
    echo json_encode(['success' => false, 'message' => 'Passwords do not match']);
    exit;
}

// Check if email already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Email already registered']);
    exit;
}

// Generate verification token
$verificationToken = bin2hex(random_bytes(32));

// Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert new user
$stmt = $conn->prepare("INSERT INTO users (name, email, password, verification_token, is_verified) VALUES (?, ?, ?, ?, 0)");
$stmt->bind_param("ssss", $fullName, $email, $hashedPassword, $verificationToken);

if ($stmt->execute()) {
    // Send verification email
    $verificationLink = "https://yourwebsite.com/verify.php?token=" . $verificationToken;
    
    $to = $email;
    $subject = "Verify Your Email";
    $message = "Hi $fullName,\n\n";
    $message .= "Please click the following link to verify your email:\n";
    $message .= $verificationLink;
    
    $headers = "From: noreply@yourwebsite.com";
    
    mail($to, $subject, $message, $headers);

    echo json_encode([
        'success' => true,
        'redirect' => 'verification_sent.php',
        'message' => 'Account created successfully. Please check your email to verify your account.'
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error creating account']);
}

$stmt->close();
$conn->close();
?>
