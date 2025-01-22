<?php
require_once '../includes/dashboard_header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'staff') {
    header('Location: ../login.php');
    exit;
}

// Initialize variables
$staff = null;
$error = null;

try {
    // Verify PDO connection
    if (!isset($pdo)) {
        require_once '../includes/db_connect.php';
    }
    
    $staff_id = $_SESSION['user_id'];
    
    // Debug output
    error_log("Staff ID: " . $staff_id);
    
    // Simplified query to test
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND role = 'staff'");
    $stmt->execute([$staff_id]);
    $staff = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Debug output
    error_log("Staff data: " . print_r($staff, true));

    if (!$staff) {
        throw new Exception("Staff member not found");
    }

} catch (Exception $e) {
    $error = "System error: " . $e->getMessage();
    error_log("Dashboard Error: " . $e->getMessage());
    $staff = [
        'first_name' => 'Unknown',
        'last_name' => 'Staff'
    ];
}
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">
                            <i class="bi bi-house-door"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_appointments.php">
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

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-5">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2 mt-2">Welcome, <?php echo htmlspecialchars($staff['first_name'] . ' ' . $staff['last_name']); ?></h1>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger mt-3"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($staff): ?>
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Manage Appointments</h5>
                                <p class="card-text">View and update the status of customer appointments.</p>
                                <a href="manage_appointments.php" class="btn btn-primary">Go to Appointments</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Booking Summary</h5>
                                <p class="card-text">View a summary of all bookings and their statuses.</p>
                                <a href="booking_summary.php" class="btn btn-primary">View Summary</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
