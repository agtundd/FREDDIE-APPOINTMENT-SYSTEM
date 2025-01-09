<?php
require_once '../includes/dashboard_header.php';

// Ensure the user is a customer
if ($_SESSION['user_role'] !== 'customer') {
    header('Location: ../login.php');
    exit;
}

// Fetch available services
$stmt = $pdo->query("SELECT * FROM services");
$services = $stmt->fetchAll();

// Fetch available dates (next 30 days)
$stmt = $pdo->prepare("
    SELECT date FROM calendar 
    WHERE date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)
    AND is_available = 1
");
$stmt->execute();
$available_dates = $stmt->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $service_id = $_POST['service_id'];
    $appointment_date = $_POST['appointment_date'];

    $stmt = $pdo->prepare("INSERT INTO appointments (customer_id, service_id, appointment_date, status) VALUES (?, ?, ?, 'scheduled')");
    if ($stmt->execute([$_SESSION['user_id'], $service_id, $appointment_date])) {
        $success_message = "Appointment booked successfully!";
    } else {
        $error_message = "Error booking appointment. Please try again.";
    }
}
?>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js"></script>

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
                        <a class="nav-link active" href="book_appointment.php">
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

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mb-5">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom mt-5">
                <h1 class="h2 mt-3 w-100 text-center text-dark">Book an Appointment</h1>
            </div>

            <div class="text-center mb-4">
                <div class="d-inline-block me-3">
                    <span class="badge bg-success">&nbsp;&nbsp;&nbsp;</span>
                    <small class="text-muted">Available for Booking</small>
                </div>
                <div class="d-inline-block">
                    <span class="badge bg-secondary">&nbsp;&nbsp;&nbsp;</span>
                    <small class="text-muted">Not Available</small>
                </div>
            </div>

            <div id="calendar" class="mx-auto" style="max-width: 700px;"></div>

            <form method="post" action="" class="mt-4 mx-auto" style="max-width: 400px;">
                <input type="hidden" id="appointment_date" name="appointment_date" required>
                <div class="mb-3 text-center">
                    <label for="service_id" class="form-label">Select Service</label>
                    <select class="form-select form-select-sm mx-auto" id="service_id" name="service_id" required>
                        <option value="">Choose a service...</option>
                        <?php foreach ($services as $service): ?>
                            <option value="<?php echo $service['id']; ?>">
                                <?php echo htmlspecialchars($service['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-sm">Book Appointment</button>
                </div>
            </form>
        </main>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        selectable: true,
        selectConstraint: {
            start: '<?php echo date('Y-m-d'); ?>',
        },
        validRange: {
            start: '<?php echo date('Y-m-d'); ?>'
        },
        dayCellDidMount: function(arg) {
            arg.el.style.backgroundColor = '#D3D3D3';
        },
        events: [
            <?php 
            $stmt = $pdo->query("SELECT date, is_available FROM calendar WHERE is_available = 1");
            $available_dates = $stmt->fetchAll();
            foreach ($available_dates as $date): 
            ?>
            {
                id: '<?php echo $date['date']; ?>',
                start: '<?php echo $date['date']; ?>',
                display: 'background',
                color: '#90EE90'
            },
            <?php endforeach; ?>
        ],
        dateClick: function(info) {
            var clickedDate = new Date(info.dateStr);
            var today = new Date();
            today.setHours(0, 0, 0, 0);

            if (clickedDate >= today) {
                var isAvailable = info.dayEl.querySelector('.fc-bg-event')?.style.backgroundColor === 'rgb(144, 238, 144)' || false;
                
                if (isAvailable) {
                    document.getElementById('appointment_date').value = info.dateStr;
                }
            }
        }
    });
    calendar.render();
});
</script>

<style>
/* Calendar Styles */
#calendar {
    max-width: 700px !important;
    margin: 0 auto;
    padding: 20px;
}

.fc {
    background: white;
    border: none !important;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
    border-radius: 8px;
}

.fc-header-toolbar {
    padding: 15px !important;
}

.fc-toolbar-title {
    color: black !important;
    font-weight: bold !important;
    text-transform: uppercase;
    font-size: 1.2rem !important;
}

.fc .fc-toolbar {
    justify-content: center !important;
}

.fc-daygrid-day-number {
    color: black !important;
    width: 100% !important;
    text-align: center !important;
    padding-right: 0 !important;
}

.fc-daygrid-day-top {
    display: flex;
    justify-content: center !important;
    align-items: center !important;
}

.fc .fc-daygrid-day-frame {
    min-height: 50px !important;
}

.fc-day {
    border: 1px solid #f0f0f0 !important;
}

.fc-day-today {
    background: #f8f9fa !important;
}

.fc-day-past {
    opacity: 0.7;
    cursor: not-allowed;
}
</style>
