<?php
require_once '../includes/dashboard_header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'staff') {
    header('Location: ../login.php');
    exit;
}

// Get staff ID
$staff_id = $_SESSION['user_id'];

// Get filter from URL parameter
$filter = $_GET['filter'] ?? 'today';

// Set date condition based on filter
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

try {
    // Fetch bookings
    $stmt = $pdo->prepare("
        SELECT 
            a.id,
            a.appointment_date,
            a.status,
            s.name as service_name,
            u.first_name,
            u.last_name
        FROM appointments a
        JOIN services s ON a.service_id = s.id
        JOIN users u ON a.customer_id = u.id
        WHERE {$date_condition}
        ORDER BY a.appointment_date DESC
    ");
    $stmt->execute();
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $error = "An error occurred while fetching the booking summary.";
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
                        <a class="nav-link" href="manage_appointments.php">
                            <i class="bi bi-calendar-check"></i> Manage Appointments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="booking_summary.php">
                            <i class="bi bi-file-text"></i> Booking Summary
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-5">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2 mt-2">Booking Summary</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <a href="?filter=today" class="btn btn-sm btn-outline-secondary <?php echo $filter === 'today' ? 'active' : ''; ?>">Today</a>
                        <a href="?filter=this_week" class="btn btn-sm btn-outline-secondary <?php echo $filter === 'this_week' ? 'active' : ''; ?>">This Week</a>
                        <a href="?filter=this_month" class="btn btn-sm btn-outline-secondary <?php echo $filter === 'this_month' ? 'active' : ''; ?>">This Month</a>
                        <a href="?filter=this_year" class="btn btn-sm btn-outline-secondary <?php echo $filter === 'this_year' ? 'active' : ''; ?>">This Year</a>
                    </div>
                </div>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Service</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($bookings)): ?>
                                <tr>
                                    <td colspan="4" class="text-center">No bookings found for this period.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($bookings as $booking): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars(date('M d, Y', strtotime($booking['appointment_date']))); ?></td>
                                        <td><?php echo htmlspecialchars($booking['first_name'] . ' ' . $booking['last_name']); ?></td>
                                        <td><?php echo htmlspecialchars($booking['service_name']); ?></td>
                                        <td><?php echo htmlspecialchars($booking['status']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
