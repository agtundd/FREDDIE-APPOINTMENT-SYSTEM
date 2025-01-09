<?php
require_once '../includes/dashboard_header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$filter = $_GET['filter'] ?? 'today';

switch ($filter) {
    case 'today':
        $date_condition = "DATE(a.appointment_date) = CURDATE()";
        break;
    case 'this_week':
        $date_condition = "YEARWEEK(a.appointment_date, 1) = YEARWEEK(CURDATE(), 1)";
        break;
    case 'this_month':
        $date_condition = "YEAR(a.appointment_date) = YEAR(CURDATE()) AND MONTH(a.appointment_date) = MONTH(CURDATE())";
        break;
    case 'this_year':
        $date_condition = "YEAR(a.appointment_date) = YEAR(CURDATE())";
        break;
    default:
        $date_condition = "DATE(a.appointment_date) = CURDATE()";
}

$stmt = $pdo->prepare("
    SELECT a.id, a.appointment_date, a.status, s.name as service, c.first_name as customer_first_name, 
           c.last_name as customer_last_name, st.first_name as staff_first_name
    FROM appointments a
    JOIN services s ON a.service_id = s.id
    JOIN users c ON a.customer_id = c.id
    LEFT JOIN users st ON a.staff_id = st.id
    WHERE $date_condition
    ORDER BY a.appointment_date DESC
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
                        <a class="nav-link active" href="appointment_reports.php">
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
                <h1 class="h2 mt-3">Appointment Reports</h1>
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        Filter: <?php echo ucfirst(str_replace('_', ' ', $filter)); ?>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="?filter=today">Today</a></li>
                        <li><a class="dropdown-item" href="?filter=this_week">This Week</a></li>
                        <li><a class="dropdown-item" href="?filter=this_month">This Month</a></li>
                        <li><a class="dropdown-item" href="?filter=this_year">This Year</a></li>
                    </ul>
                </div>
            </div>

            <h2 class="h4 mb-3">Filtered Appointments</h2>
            <?php if (empty($appointments)): ?>
                <p>No appointments found for the selected filter.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Service</th>
                                <th>Status</th>
                                <th>Customer</th>
                                <th>Staff</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($appointments as $appointment): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                                    <td><?php echo htmlspecialchars($appointment['service']); ?></td>
                                    <td><?php echo htmlspecialchars($appointment['status']); ?></td>
                                    <td><?php echo htmlspecialchars($appointment['customer_first_name'] . ' ' . $appointment['customer_last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($appointment['staff_first_name']); ?></td>
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
                <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
            </div>
        </main>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
