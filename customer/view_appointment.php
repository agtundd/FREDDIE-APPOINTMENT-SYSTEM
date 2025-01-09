<?php
require_once '../includes/dashboard_header.php';

// Ensure the user is a customer
if ($_SESSION['user_role'] !== 'customer') {
    header('Location: ../login.php');
    exit;
}

$appointment_id = $_GET['id'] ?? null;

if (!$appointment_id) {
    header('Location: appointments.php');
    exit;
}

// Fetch appointment details
$stmt = $pdo->prepare("
    SELECT a.*, s.name as service_name, s.price as service_price
    FROM appointments a
    JOIN services s ON a.service_id = s.id
    WHERE a.id = ? AND a.customer_id = ?
");
$stmt->execute([$appointment_id, $_SESSION['user_id']]);
$appointment = $stmt->fetch();

if (!$appointment) {
    header('Location: appointments.php');
    exit;
}
?>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">
                            <i class="bi bi-house-door"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="book_appointment.php">
                            <i class="bi bi-calendar-plus"></i> Book Appointment
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="appointments.php">
                            <i class="bi bi-calendar-check"></i> My Appointments
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Appointment Details</h1>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Appointment #<?php echo $appointment['id']; ?></h5>
                    <p class="card-text"><strong>Service:</strong> <?php echo htmlspecialchars($appointment['service_name']); ?></p>
                    <p class="card-text"><strong>Date:</strong> <?php echo htmlspecialchars($appointment['appointment_date']); ?></p>
                    <p class="card-text"><strong>Status:</strong> <?php echo htmlspecialchars($appointment['status']); ?></p>
                    <p class="card-text"><strong>Price:</strong> $<?php echo number_format($appointment['service_price'], 2); ?></p>
                </div>
            </div>

            <?php if ($appointment['status'] == 'completed'): ?>
                <div class="mt-3">
                    <a href="rate_service.php?id=<?php echo $appointment['id']; ?>" class="btn btn-primary">Rate Service</a>
                </div>
            <?php elseif ($appointment['status'] == 'scheduled'): ?>
                <div class="mt-3">
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                        Cancel Appointment
                    </button>
                </div>
            <?php endif; ?>

            <div class="mt-3">
                <a href="appointments.php" class="btn btn-secondary">Back to Appointments</a>
            </div>
        </main>

        <!-- Cancel Modal -->
        <div class="modal fade" id="cancelModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cancel Appointment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to cancel this appointment?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        <form action="cancel_appointment.php" method="POST">
                            <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                            <button type="submit" class="btn btn-danger">Yes, Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php require_once '../includes/footer.php'; ?>
    </div>
</div>
