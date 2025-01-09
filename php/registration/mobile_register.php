<?php
require '../conn.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$firstName = $_POST['first_name'] ?? '';
$lastName = $_POST['last_name'] ?? '';
$age = $_POST['age'] ?? '';
$email = $_POST['email'] ?? '';
$phoneNumber = $_POST['phone_number'] ?? '';
$role = 'customer'; // Automatically set the role to 'customer'

if (empty($username) || empty($password) || empty($firstName) || empty($lastName) || empty($age) || empty($email) || empty($phoneNumber)) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, password, first_name, last_name, age, email, phone_number, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("ssssisss", $username, $hashed_password, $firstName, $lastName, $age, $email, $phoneNumber, $role);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Registration successful']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Error preparing statement']);
}

$conn->close();
?>
