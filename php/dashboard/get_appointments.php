<?php 
require_once '../conn.php';

// Retrieve user_id from SharedPreferences or POST data
// Assuming the user_id is sent via POST method or fetched from a session or other secure method
if (isset($_POST['id'])) {
    $user_id = intval($_POST['id']);  // Ensure it's an integer
} else {
    $user_id = 1;  // Default to 1 or handle error
}

$sql = "
    SELECT 
        appointments.appointment_date AS date, 
        services.name AS service_name, 
        appointments.status 
    FROM 
        appointments 
    INNER JOIN 
        services ON appointments.service_id = services.id 
    WHERE 
        appointments.customer_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$appointments = [];
while ($row = $result->fetch_assoc()) {
    $appointments[] = $row;
}

echo json_encode([
    'success' => true,
    'appointments' => $appointments,
]);

$stmt->close();
$conn->close();
?>
