<?php
// ...existing code...

try {
    $stmt = $pdo->prepare("INSERT INTO appointments (customer_id, service_id, appointment_date, status) 
                          VALUES (?, ?, ?, 'pending')");
    $stmt->execute([
        $_SESSION['user_id'],
        $_POST['service_id'],
        $_POST['appointment_date']
    ]);

    $_SESSION['success'] = "Appointment booked successfully! Please wait for staff confirmation.";
    header("Location: appointments.php");
    exit;
    
} catch (PDOException $e) {
    $_SESSION['error'] = "Failed to book appointment.";
    header("Location: book_appointment.php");
    exit;
}