// create_user.php - Use this to create a test user
<?php
require_once 'config.php';

$name = "Test User";
$email = "test@example.com";
$password = password_hash("your_password", PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $password);

if ($stmt->execute()) {
    echo "User created successfully";
} else {
    echo "Error creating user: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
