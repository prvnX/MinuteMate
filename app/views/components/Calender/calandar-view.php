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
            <form method="post" id="">
                <label for="class">Event Type:</label>
                <select id="class" name="meeting_type" required>
                    <?php
                    $secMeetingTypes=$_SESSION['secMeetingTypes'];
                    foreach($secMeetingTypes as $meetingType){
                        $meetingType = strtolower($meetingType);  
                        if($meetingType=="bom"){
                            echo "<option value='bom'>BOM Meeting</option>";
                        }
                        else if($meetingType=="rhd"){
                            echo "<option value='rhd'>RHD Meeting</option>";
                        }
                        else if($meetingType=="syn"){
                            echo "<option value='syn'>Syndicate Meeting</option>";
                        }
                        else if($meetingType=="iod"||$meetingType=="iud"){
                            echo "<option value='iud'>IUD Meeting</option>";
                        }
                    }
                    ?>
                </select>

                <label for="date">Date:</label>
                <input type="date" id="date" name="date" required>

                <label for="Starttime">Start Time:</label>
                <input type="time" id="Starttime" name="Starttime" required>

                <label for="Endtime">End Time:</label>
                <input type="time" id="Endtime" name="Endtime" required>

                <label for="location">location:</label>
                <input type="text" id="location" name="Endtime" required>

                <label for="note">Additional Note:</label>
                <textarea id="note" name="note"></textarea>

                <button type="button" class="submit-button" onclick="handleFormSubmit()">Add Event</button>
            </form>
        </div>
    </div>

    <!-- Alert Modal -->

    <div id="alertModal" class="modal">
    <div class="modal-content">
        <div class="modal-body">
            <p id="Message">Your action was successful!</p>
        </div>
        <div class="modal-actions">
            <button type="button" id="successOk" class="btn btn-ok">OK</button>
        </div>
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

        // Handle form submission
        function handleFormSubmit(){
            const date = document.getElementById('date').value;
            const meeting_type = document.getElementById('class').value;
            const start_time = document.getElementById('Starttime').value;
            const end_time = document.getElementById('Endtime').value;
            const location = document.getElementById('location').value;
            const additional_note = document.getElementById('note').value;
            if(date=="" || meeting_type=="" || start_time=="" || end_time=="" || location==""){
                showAlert("Please fill all the fields");
                return;
            }
            fetch('<?= ROOT ?>/Events/addMeeting', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    date: date,
                    meeting_type: meeting_type,
                    start_time: start_time,
                    end_time: end_time,
                    location: location,
                    additional_note: additional_note
                })
            })
            .then(response => response.json())
            .then(data => {
                showAlert(data.success);
            })
            .catch(error => {
                console.error('Error:', error);

            });
        }
            //function for handling the alert
    function showAlert(message) {
            const modal = document.getElementById('alertModal');
            const messageElement = document.getElementById('Message');
            messageElement.textContent = message;
            modal.style.display = 'block';
            document.getElementById('successOk').onclick = function () {
            modal.style.display = 'none';
            location.reload();  // Refresh the current page
        };
    }






    function handleDayClick(date) {
        window.location.href = `<?= ROOT ?>/Events?date=${date}`;
    }


    </script>
</body>
</html>
