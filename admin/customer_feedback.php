<?php
require_once '../includes/dashboard_header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$stmt = $pdo->query("
    SELECT f.id, f.rating, f.comment, f.created_at, s.name as service, u.first_name, u.last_name
    FROM feedback f
    JOIN appointments a ON f.appointment_id = a.id
    JOIN services s ON a.service_id = s.id
    JOIN users u ON a.customer_id = u.id
    ORDER BY f.created_at DESC
");
$feedbacks = $stmt->fetchAll();
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
                        <a class="nav-link active" href="customer_feedback.php">
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

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mb-5">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom mt-5">
                <h1 class="h2 mt-3 w-100 text-center text-dark">Customer Feedback</h1>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Service</th>
                            <th>Rating</th>
                            <th>Comment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($feedbacks as $feedback): ?>
                            <tr>
                                <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($feedback['created_at']))); ?></td>
                                <td><?php echo htmlspecialchars($feedback['first_name'] . ' ' . $feedback['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($feedback['service']); ?></td>
                                <td><?php echo htmlspecialchars($feedback['rating']); ?> / 5</td>
                                <td><?php echo htmlspecialchars($feedback['comment']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
