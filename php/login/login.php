<?php
// Include database connection
require_once '../conn.php';

// Set response type to JSON
header('Content-Type: application/json');

// Get input data
$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

// Check if username exists
$stmt = $conn->prepare('SELECT * FROM users WHERE username = ?');
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
    // Successful login
    echo json_encode([
        'success' => true,
        'user' => [
            'id' => $user['id'],
            'username' => $user['username'],
            'role' => $user['role'],
        ],
    ]);
} else {
    // Invalid credentials
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
}

// Close the connection
$stmt->close();
$conn->close();
?>
