<?php
require_once 'includes/dashboard_header.php';

// Fetch user details
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $stmt = $pdo->prepare("UPDATE users SET first_name = ?, middle_name = ?, last_name = ?, email = ?, phone_number = ? WHERE id = ?");
    if ($stmt->execute([$first_name, $middle_name, $last_name, $email, $phone, $_SESSION['user_id']])) {
        $success_message = "Profile updated successfully!";
        // Refresh user data
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
    } else {
        $error_message = "Error updating profile. Please try again.";
    }
}
?>

<nav class="navbar navbar-expand-lg navbar-light fixed-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="<?php echo $_SESSION['user_role']; ?>/dashboard.php">
            <img src="assets/images/mr-freddie.png" alt="MR.FREDDIE Logo" style="max-height: 65px; width: auto; object-fit: contain; transform: scale(1.5); transform-origin: left center;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-4 me-2"></i>
                            <span>Profile</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
                            <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Log Out</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $_SESSION['user_role']; ?>/dashboard.php">
                            <i class="bi bi-house-door"></i> Dashboard
                        </a>
                    </li>
                    <?php if ($_SESSION['user_role'] === 'customer'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="customer/book_appointment.php">
                            <i class="bi bi-calendar-plus"></i> Book Appointment
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="customer/appointments.php">
                            <i class="bi bi-calendar-check"></i> My Appointments
                        </a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link active" href="profile.php">
                            <i class="bi bi-person"></i> Profile
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">My Profile</h1>
            </div>

            <?php if (isset($success_message)): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form method="post" action="">
                <div class="mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="middle_name" class="form-label">Middle Name</label>
                    <input type="text" class="form-control" id="middle_name" name="middle_name" value="<?php echo htmlspecialchars($user['middle_name']); ?>">
                </div>
                <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone_number']); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
        </main>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
