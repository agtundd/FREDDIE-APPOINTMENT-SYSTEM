<?php
// Include database connection
require_once '../conn.php';

// Set response type to JSON
header('Content-Type: application/json');

// Get the input data sent via POST
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Debugging: Log input values (for development purposes only; remove in production)
error_log("Email: $email, Password: $password");

// Check if email exists in the database
$stmt = $conn->prepare('SELECT * FROM users WHERE email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Validate the password
if ($user && password_verify($password, $user['password'])) {
    // Successful login
    echo json_encode([
        'success' => true,
        'user' => [
            'id' => $user['id'],
            'email' => $user['email'], // Using email instead of username
        ],
    ]);
} else {
    // Invalid email or password
    http_response_code(401); // Unauthorized
    echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
}

// Close the connection
$stmt->close();
$conn->close();
?>
x