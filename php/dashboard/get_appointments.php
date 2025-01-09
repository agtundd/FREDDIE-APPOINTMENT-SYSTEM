<?php 
require_once '../conn.php';

// Temporary user_id set to 1
$user_id = 1;

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