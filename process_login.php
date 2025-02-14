<?php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get and sanitize input
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$password = $_POST['password'];
$remember = isset($_POST['remember']) ? $_POST['remember'] : false;

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format']);
    exit;
}

// Prepare SQL statement to prevent SQL injection
$stmt = $conn->prepare("SELECT id, email, password, name FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    
    // Verify password
    if (password_verify($password, $user['password'])) {
        // Start session and set user data
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['name'];

        // Handle "Remember Me"
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $expiry = date('Y-m-d H:i:s', strtotime('+30 days'));
            
            // Store token in database
            $stmt = $conn->prepare("INSERT INTO remember_tokens (user_id, token, expiry) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $user['id'], $token, $expiry);
            $stmt->execute();
            
            // Set cookie
            setcookie('remember_token', $token, time() + (86400 * 30), "/", "", true, true);
        }

        echo json_encode([
            'success' => true,
            'redirect' => 'dashboard.php',
            'message' => 'Login successful'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
}

$stmt->close();
$conn->close();
?>
