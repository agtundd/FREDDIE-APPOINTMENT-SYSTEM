<?php
require_once '../includes/dashboard_header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $is_available = $_POST['is_available'];

    try {
        $stmt = $pdo->prepare("INSERT INTO calendar (date, is_available) 
                              VALUES (?, ?) 
                              ON DUPLICATE KEY UPDATE is_available = VALUES(is_available)");
        $stmt->execute([$date, $is_available]);
        echo json_encode(['success' => true]);
        exit;
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

// Fetch calendar data
$stmt = $pdo->query("SELECT date, is_available FROM calendar");
$calendar_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                        <a class="nav-link active" href="edit_calendar.php">
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

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mb-5">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom mt-5">
                <h1 class="h2 mt-3 w-100 text-center text-dark">MR. FREDDIE Appointment Calendar</h1>
            </div>

            <div class="text-center mb-4">
                <div class="d-inline-block me-3">
                    <span class="badge bg-success">&nbsp;&nbsp;&nbsp;</span>
                    <small class="text-muted">Open for Appointments</small>
                </div>
                <div class="d-inline-block">
                    <span class="badge bg-secondary">&nbsp;&nbsp;&nbsp;</span>
                    <small class="text-muted">Not Available</small>
                </div>
            </div>

            <div id="calendar"></div>
        </main>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css" rel="stylesheet">

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
                <?php foreach ($calendar_data as $date): ?>
                {
                    id: '<?php echo $date['date']; ?>',
                    start: '<?php echo $date['date']; ?>',
                    display: 'background',
                    color: <?php echo $date['is_available'] ? "'#90EE90'" : "'#D3D3D3'"; ?>
                },
                <?php endforeach; ?>
            ],
            dateClick: function(info) {
                var clickedDate = new Date(info.dateStr);
                var today = new Date();
                today.setHours(0, 0, 0, 0);

                if (clickedDate >= today) {
                    var isCurrentlyAvailable = info.dayEl.querySelector('.fc-bg-event')?.style.backgroundColor === 'rgb(144, 238, 144)' || false;
                    
                    if (confirm('Do you want to make this date ' + (isCurrentlyAvailable ? 'unavailable' : 'available') + '?')) {
                        $.ajax({
                            url: 'edit_calendar.php',
                            method: 'POST',
                            data: {
                                date: info.dateStr,
                                is_available: isCurrentlyAvailable ? 0 : 1
                            },
                            success: function(response) {
                                var data = JSON.parse(response);
                                if (data.success) {
                                    let existingEvent = calendar.getEventById(info.dateStr);
                                    if (existingEvent) {
                                        existingEvent.remove();
                                    }
                                    calendar.addEvent({
                                        id: info.dateStr,
                                        start: info.dateStr,
                                        display: 'background',
                                        color: isCurrentlyAvailable ? '#D3D3D3' : '#90EE90'
                                    });
                                }
                            }
                        });
                    }
                }
            }
        });
        calendar.render();
    });
</script>

<style>
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
    min-height: 60px !important;
}

.fc-toolbar-title {
    color: black !important;
    font-weight: bold !important;
    text-transform: uppercase;
}

.fc .fc-toolbar {
    justify-content: center !important;
}

#calendar {
    max-width: 900px;
    margin: 0 auto;
}

/* Clean, minimal calendar styling */
.fc {
    background: white;
    border: none !important;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
    border-radius: 8px;
}

.fc-header-toolbar {
    padding: 20px !important;
}

.fc-toolbar-title {
    color: #333 !important;
    font-size: 1.5rem !important;
    font-weight: 500 !important;
}

.fc-button {
    background: #f8f9fa !important;
    border: 1px solid #dee2e6 !important;
    color: #333 !important;
    box-shadow: none !important;
    padding: 8px 12px !important;
}

fc-button:hover {
    background: #e9ecef !important;
}

.fc-day {
    border: 1px solid #f0f0f0 !important;
}

.fc-daygrid-day-number {
    color: #333 !important;
    padding: 8px !important;
    font-weight: 500;
}

.fc-day-today {
    background: #f8f9fa !important;
}

.fc-daygrid-day-frame {
    padding: 4px !important;
}

#calendar {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

/* Availability indicators */
.fc-daygrid-day.available {
    background-color: #90EE90 !important;
}

.fc-daygrid-day.unavailable {
    background-color: #D3D3D3 !important;
}

/* Past dates styling */
.fc-day-past {
    opacity: 0.7;
    cursor: not-allowed;
}
</style>