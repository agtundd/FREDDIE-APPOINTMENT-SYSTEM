<?php
require_once '../includes/dashboard_header.php';

if (!isset($_POST['appointment_id'])) {
    header('Location: appointments.php');
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE appointments SET status = 'cancelled' WHERE id = ? AND customer_id = ?");
    $stmt->execute([$_POST['appointment_id'], $_SESSION['user_id']]);
    
    $_SESSION['success'] = "Appointment cancelled successfully.";
} catch (PDOException $e) {
    $_SESSION['error'] = "Failed to cancel appointment.";
}

header('Location: appointments.php');
exit;
?>