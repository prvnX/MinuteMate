<?php
include 'Calendar.php';

$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('m');


$calendar = new Calendar();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newEvent = [
        'start' => $_POST['date'],
        'end' => $_POST['date'],
        'summary' => $_POST['summary'],
        'classes' => [$_POST['class']]
    ];
    $calendar->addEvent($newEvent);
}


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
        <div class="cal-heading">
            <h2><?php echo date('F', strtotime("$year-$month-01")); ?> 
                <span class="year"><?php echo date('Y', strtotime("$year-$month-01")); ?></span>
            </h2>

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

    
        <?php echo $calendar->draw($year, $month);?>
        
        <?php if ($showAddEvents==true) : ?>
            <button class="add-event-button" onclick="openModal()">+ Add Event</button>
        <?php endif; ?>

    </div>

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

                <label for="host">Host:</label>
                <input type="text" id="host" name="host" required>

                <label for="note">Additional Note:</label>
                <textarea id="note" name="note"></textarea>
                
                <div id="agendaContainer" class="agendaContainer">
                <label for="agendaItem">Agenda:</label>
                        <div class="input-container">

                            <input type="text" class="agendaItems" id="agendaItem" name="Agenda[]" placeholder="Enter the Agenda Item here" />
                        </div>
                    </div>
                    <button type="button" id="addMoreBtn" class="addmorebtn">Add Another Item</button>

                <button type="button" class="submit-button" onclick="handleFormSubmit()">Add Event</button>
            </form>
        </div>
    </div>


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
        function openModal() {
            document.getElementById('eventModal').style.display = "block";
        }

        function closeModal() {
            document.getElementById('eventModal').style.display = "none";
        }

        document.querySelectorAll('.iud, .rhd, .syn, .bom').forEach(element => {
            element.addEventListener('mouseenter', function(event) {
                let message = document.createElement('div');
                message.className = 'message';
                message.textContent = `${element.classList[0].toUpperCase()} Meeting`;
                message.style.left = `${event.clientX}px`;
                message.style.top = `${event.clientY}px`;
                document.body.appendChild(message);
                setTimeout(() => { message.style.opacity = 1; }, 0);

                element.addEventListener('mouseleave', function() {
                    message.style.opacity = 0;
                    setTimeout(() => document.body.removeChild(message), 200);
                });
            });
        });

        window.onclick = function(event) {
            var modal = document.getElementById('eventModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        function handleFormSubmit(){
            const date = document.getElementById('date').value;
            const meeting_type = document.getElementById('class').value;
            const start_time = document.getElementById('Starttime').value;
            const end_time = document.getElementById('Endtime').value;
            const location = document.getElementById('location').value;
            const additional_note = document.getElementById('note').value;
            const host = document.getElementById('host').value;
            const dateObj=new Date();
            const currentDateOnly = new Date(dateObj.getFullYear(), dateObj.getMonth(), dateObj.getDate()); 
            const currentDate = dateObj;
            // const currentDate=dateObj.toLocaleDateString();
            let insertedDateObj=new Date(date); 
            const insertedDate=insertedDateObj.toLocaleDateString();
            const insertedDateOnly = new Date(insertedDateObj.getFullYear(), insertedDateObj.getMonth(), insertedDateObj.getDate()); 
            let startT = new Date(`2000-01-01T${start_time}:00`); 
            let endT = new Date(`2000-01-01T${end_time}:00`);
            let diffMs = Math.abs(startT - endT); 
            let diffMins = Math.floor((diffMs/1000)/60); 
            const agendaArray=[];
            const allAgendaInputs = document.getElementsByName("Agenda[]");
            console.log(insertedDate);
            console.log(currentDate);


            fetch('<?= ROOT ?>/Events/getUserinsystem', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    host:host
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success){
                    showAlert("Host is not a user in the system");
                    return;
                }
                else{
                    console.log("User in the table");
                    if(date=="" || meeting_type=="" || start_time=="" || end_time=="" || location==""){
                showAlert("Please fill all the fields");
                return;
            }
            else if(insertedDateOnly<currentDateOnly){
                showAlert("Error:Cannot add an event to a past date");
                return;
            }
            else if(startT>endT){
                showAlert("Error: Start time should be before the end time");
                return;
            }
            else if(diffMins<45){
                showAlert("Error: Meeting duration should be at least 45 minutes");
                return;
            }
            else if(diffMins>480){
                showAlert("Error: Meeting duration should be at most 8 hours");
                return;
            }
            
            else if(date)
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
                    additional_note: additional_note,
                    agenda: agendaArray,
                    host:host
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
            })
            .catch(error => {
                console.error('Error:', error);

            });
        
            allAgendaInputs.forEach((input, index) => {
            console.log(`Agenda ${index + 1}: ${input.value}`);
            if(input.value!="" || input.value.trim()!=""){
                agendaArray.push(input.value);
            }
            });


           
        }
            
    function showAlert(message) {
            const modal = document.getElementById('alertModal');
            const messageElement = document.getElementById('Message');
            messageElement.textContent = message;
            modal.style.display = 'block';
            document.getElementById('successOk').onclick = function () {
            modal.style.display = 'none';
            location.reload();  
        };
    }
    input=document.querySelector(".agendaItems");
    document.getElementById("addMoreBtn").addEventListener("click", function() {
        if (input.value === "") {
            showAlert("Add atleast one agenda item before adding another one");
            return;
        }
        else{
            const newInputContainer = document.createElement("div");
            newInputContainer.classList.add("input-container");
            newInputContainer.classList.add("agendaItems");

            const newInput = document.createElement("input");
            newInput.type = "text";
            newInput.name = "Agenda[]";
            newInput.placeholder = "Enter the Next Agenda Item here";

            closeBtn = document.createElement("button");
            closeBtn.type = "button";
            closeBtn.innerHTML = "Remove this agenda item";
            closeBtn.classList.add("closeBtn");

            closeBtn.addEventListener("click", function() {
                if(confirm('Are you sure you want to delete this item?')){
                this.parentElement.remove();
                }
                else{
                    return;
                }
            });
            newInputContainer.appendChild(newInput);
            newInputContainer.appendChild(closeBtn)
            newInputContainer.style.display = "block";
            document.getElementById("agendaContainer").appendChild(newInputContainer);
            return;
        }
    });






    function handleDayClick(date) {
        window.location.href = `<?= ROOT ?>/Events?date=${date}`;
    }


    </script>
</body>
</html>
