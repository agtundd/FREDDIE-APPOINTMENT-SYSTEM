<?php
require_once 'includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_SESSION['user_id'];
    $service_id = $_POST['service'];
    $appointment_date = $_POST['selectedDate'];

    $stmt = $pdo->prepare("INSERT INTO appointments (customer_id, service_id, appointment_date, status) VALUES (?, ?, ?, 'scheduled')");
    if ($stmt->execute([$customer_id, $service_id, $appointment_date])) {
        $success = "Appointment booked successfully!";
    } else {
        $error = "An error occurred while booking the appointment.";
    }
}
?>

<h2 class="text-center mb-4">Appointment Submission</h2>

<?php if (isset($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
    <p>Your appointment has been scheduled. You can view its status on the <a href="service_status.php">Service Status</a> page.</p>
<?php elseif (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<a href="book_appointment.php" class="btn btn-primary">Book Another Appointment</a>

<?php include 'includes/footer.php'; ?>

