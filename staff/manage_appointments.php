<?php
require_once '../includes/dashboard_header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'staff') {
    header('Location: ../login.php');
    exit;
}

$staff_id = $_SESSION['user_id'];

// Handle appointment status updates
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['appointment_id']) && isset($_POST['new_status'])) {
    $appointment_id = $_POST['appointment_id'];
    $new_status = $_POST['new_status'];
    $stmt = $pdo->prepare("UPDATE appointments SET status = ?, staff_id = ? WHERE id = ?");
    $stmt->execute([$new_status, $staff_id, $appointment_id]);
}

// Fetch appointments
$stmt = $pdo->prepare("
    SELECT a.id, a.appointment_date, a.status, s.name as service, u.first_name, u.last_name 
    FROM appointments a
    JOIN services s ON a.service_id = s.id
    JOIN users u ON a.customer_id = u.id
    ORDER BY a.appointment_date DESC, a.id DESC
");
$stmt->execute();
$appointments = $stmt->fetchAll();
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
                        <a class="nav-link active" href="manage_appointments.php">
                            <i class="bi bi-calendar-check"></i> Manage Appointments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="booking_summary.php">
                            <i class="bi bi-file-text"></i> Booking Summary
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-5">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2 mt-2">Manage Appointments</h1>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Service</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($appointments as $appointment): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['first_name'] . ' ' . $appointment['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['service']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['status']); ?></td>
                                <td>
                                    <form method="post" action="" class="d-inline">
                                        <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                                        <select name="new_status" onchange="this.form.submit()" class="form-select form-select-sm">
                                            <option value="">Update Status</option>
                                            <option value="on-going">On-going</option>
                                            <option value="completed">Completed</option>
                                            <option value="cancelled">Cancelled</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
