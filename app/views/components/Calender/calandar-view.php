<?php
include 'Calendar.php';

$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('m');

// Initialize the Calendar
$calendar = new Calendar();

// Check if the form is submitted to add a new event
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newEvent = [
        'start' => $_POST['date'],
        'end' => $_POST['date'],
        'summary' => $_POST['summary'],
        'classes' => [$_POST['class']]
    ];
    $calendar->addEvent($newEvent);
}

// Adjust month navigation
$prevMonth = $month == 1 ? 12 : $month - 1;
$nextMonth = $month == 12 ? 1 : $month + 1;
$prevYear = $month == 1 ? $year - 1 : $year;
$nextYear = $month == 12 ? $year + 1 : $year;
?>

<head>
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/component-styles/calandar.style.css">
</head>
<body>
    <div class="calendar-container">
        <!-- Month and Year Header -->
        <div class="cal-heading">
            <h2><?php echo date('F', strtotime("$year-$month-01")); ?> 
                <span class="year"><?php echo date('Y', strtotime("$year-$month-01")); ?></span>
            </h2>

            <!-- Navigation Buttons -->
            <div class="nav-buttons">
                <form method="get" style="display: inline;">
                    <input type="hidden" name="month" value="<?php echo $prevMonth; ?>">
                    <input type="hidden" name="year" value="<?php echo $prevYear;echo "date=''" ?>">
                    <input type="hidden" name="date" value="">
                    <button type="submit">&lt;</button>
                </form>
                
                <form method="get" style="display: inline;">
                    <input type="hidden" name="month" value="<?php echo $nextMonth; ?>">
                    <input type="hidden" name="year" value="<?php echo $nextYear;echo "date=''" ?>">
                    <input type="hidden" name="date" value="">
                    <button type="submit">&gt;</button>
                </form>
            </div>
        </div>

        <!-- Display Calendar -->
        <?php echo $calendar->draw($year, $month);?>
        
        <?php if ($showAddEvents==true) : ?>
            <button class="add-event-button" onclick="openModal()">+ Add Event</button>
        <?php endif; ?>

    </div>

    <!-- Add Event Modal -->
    <div id="eventModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Add an event</h3>
            <form method="post">
                <label for="summary">Event Name:</label>
                <input type="text" id="summary" name="summary" required>

                <label for="class">Event Type:</label>
                <select id="class" name="class" required>
                    <option value="rhd">RHD Meeting</option>
                    <option value="bom">BOM Meeting</option>
                    <option value="syn">Syndicate Meeting</option>
                    <option value="iud">IUD Meeting</option>
                </select>

                <label for="date">Date:</label>
                <input type="date" id="date" name="date" required>

                <label for="time">Time:</label>
                <input type="time" id="time" name="time">

                <button type="submit" class="submit-button">Add Event</button>
            </form>
        </div>
    </div>

    <script>
        // Function to open the modal
        function openModal() {
            document.getElementById('eventModal').style.display = "block";
        }

        // Function to close the modal
        function closeModal() {
            document.getElementById('eventModal').style.display = "none";
        }

        // Show tooltip message on event hover
        document.querySelectorAll('.iud, .rhd, .syn, .bom').forEach(element => {
            element.addEventListener('mouseenter', function(event) {
                let message = document.createElement('div');
                message.className = 'message';
                message.textContent = `${element.classList[0].toUpperCase()} Meeting`;
                message.style.left = `${event.clientX}px`;
                message.style.top = `${event.clientY}px`;
                document.body.appendChild(message);
                setTimeout(() => { message.style.opacity = 1; }, 0);

                // Remove the message when the mouse leaves
                element.addEventListener('mouseleave', function() {
                    message.style.opacity = 0;
                    setTimeout(() => document.body.removeChild(message), 200);
                });
            });
        });

        // Close the modal if the user clicks outside of it
        window.onclick = function(event) {
            var modal = document.getElementById('eventModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    function handleDayClick(date) {
        window.location.href = `<?= ROOT ?>/Events?date=${date}`;
    }

    </script>
</body>
</html>
