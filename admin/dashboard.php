<?php
require_once '../includes/dashboard_header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

// Fetch admin data
$admin_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT first_name, last_name FROM users WHERE id = ?");
$stmt->execute([$admin_id]);
$admin = $stmt->fetch();

// Fetch recent activities or data relevant to admin
$stmt = $pdo->prepare("
    SELECT a.id, a.appointment_date, s.name as service_name, a.status, u.username as customer_name
    FROM appointments a
    JOIN services s ON a.service_id = s.id
    JOIN users u ON a.customer_id = u.id
    ORDER BY a.appointment_date DESC
    LIMIT 5
");
$stmt->execute();
$recent_appointments = $stmt->fetchAll();
?>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">
                            <i class="bi bi-house-door"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="appointment_reports.php">
                            <i class="bi bi-file-earmark-text"></i> Appointment Reports
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="edit_calendar.php">
                            <i class="bi bi-calendar"></i> Edit Calendar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="customer_feedback.php">
                            <i class="bi bi-chat-dots"></i> Customer Feedback
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_staff.php">
                            <i class="bi bi-people"></i> Manage Staff
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom mt-5">
                <h1 class="h2 mt-3">Admin Dashboard</h1>
            </div>

            <h2 class="h4 mb-3">Recent Appointments</h2>
            <?php if (empty($recent_appointments)): ?>
                <p>No recent appointments.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Service</th>
                                <th>Status</th>
                                <th>Customer</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_appointments as $appointment): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                                    <td><?php echo htmlspecialchars($appointment['service_name']); ?></td>
                                    <td><?php echo htmlspecialchars($appointment['status']); ?></td>
                                    <td><?php echo htmlspecialchars($appointment['customer_name']); ?></td>
                                    <td>
                                        <a href="view_appointment.php?id=<?php echo $appointment['id']; ?>" class="btn btn-sm btn-primary">View</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <div class="mt-4">
                <a href="appointment_reports.php" class="btn btn-primary">View All Appointments</a>
            </div>
        </main>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
