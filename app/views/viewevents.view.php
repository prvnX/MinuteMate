
<head>
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/viewevents.style.css">
    <title>View Events</title>
    <link rel="icon" href="<?=ROOT?>/img.png" type="image">

    
</head>
<body>
<?php
    $user=$_SESSION['userDetails']->role;
    $memocart="memocart";
    $notification="notification";
    if($user=="secretary"){
        $menuItems = [ "home" => ROOT."/$user",$memocart => ROOT."/$user/memocart", $notification => ROOT."/$user/notifications", "profile" => ROOT."/$user/viewprofile" , "logout" => ROOT."/$user/confirmlogout"]; //pass the menu items here (key is the name of the page, value is the url)
    }
    else{
        $menuItems = [ "home" => ROOT."/$user",$notification => ROOT."/$user/notifications", "profile" => ROOT."/$user/viewprofile" , "logout" => ROOT."/$user/confirmlogout"]; //pass the menu items here (key is the name of the page, value is the url)
    }
    require_once("../app/views/components/new_navbar.php"); //call the navbar component
    ?>

    <div class="dashboard-container">  
    <div class="events-container">
        <?php if($date!=""){ ?>
        <h1>Events on : <?= $date ?></h1>
        <?php } ?>
        <div class="events">
            <div class="event">
        <?php 
            
            // Decode fetchData from the controller
            $fetchData = json_decode($fetchData, true); // Decode as an associative array
            
            // Check if fetchData contains an error
            if (isset($fetchData['error'])) {
                echo "<div class='error-message'>" . htmlspecialchars($fetchData['error']) . "</div>";
            } else if (is_array($fetchData) && count($fetchData) > 0) {
                // Loop through meeting data if fetchData is valid
                foreach ($fetchData as $meeting) {
                    echo "<div class='meeting'>";
                    $meetingType=strtolower($meeting['meeting_type']);
                    $meetingEditAccess=0;  
                    if($_SESSION['userDetails']->role=="secretary"){
                    $secMeetingTypes=($_SESSION['secMeetingTypes']);
                    foreach($secMeetingTypes as $type){
                        if(strtolower($type)=="iod"){
                            $type="iud";
                        }
                        if(strtolower($type)==$meetingType){
                            $meetingEditAccess=1;
                        }
                    }
                }


                    $meeting['meeting_type'] = $meeting['meeting_type'] == 'syn' ? 'syndicate' : $meeting['meeting_type'];
                    echo "<h1 class='meetingtitle'>" . ucfirst(htmlspecialchars($meeting['meeting_type']))." Meeting </h1><hr>";
                    echo "<table>";
                    echo "<tr><td class='left'>Date</td><td>" . htmlspecialchars($meeting['date']) . "</td></tr>";
                    echo "<tr><td class='left'>Start Time:</td><td>" . htmlspecialchars($meeting['start_time']) . "</td></tr>";
                    echo "<tr><td class='left'>End Time:</td><td>" . htmlspecialchars($meeting['end_time']) . "</td></tr>";
                    echo "<tr><td class='left'>Location:</td><td>" . htmlspecialchars($meeting['location']) . "</td></tr>";
                    echo "<tr><td class='left'>Meeting Type:</td><td>" . htmlspecialchars($meeting['meeting_type']) . "</td></tr>";
                    echo "<tr><td class='left'>Created By:</td><td>" . htmlspecialchars($meeting['created_by_name']) . "</td></tr>";
                    echo "<tr><td>Additional Notes:</td><td>" . htmlspecialchars($meeting['additional_note']) . "</td></tr>";
                    $meetingid = htmlspecialchars($meeting['meeting_id']);
                    echo "<tr><td>Memos Submitted:</td><td>" . htmlspecialchars($meeting['memos']) . " memos submitted for this meeting.</td></tr>";

                    echo "</table>";
                    if($_SESSION['userDetails']->role=="secretary"|| $_SESSION['userDetails']->role=="lecturer"){
                        echo "<button class='view-meeting-agenda' id='view-meeting-agenda' onclick='viewAgendaAndMemos(".$meetingid.")'>View Agenda Details and Submitted Memos</button>";
                    }
                    date_default_timezone_set('Asia/Colombo');
                    $todayDate = date('Y-m-d');
                    if($_SESSION['userDetails']->role=="secretary" && $meeting['date'] >= $todayDate && $meetingEditAccess){
                        echo "<div class='meeting-action-btns'>
                        <button class='delete-btn action-btn' onclick='handleMeetingDelete(".$meetingid.")'>Delete The Meeting</button>
                        <button class='resch-btn action-btn' onclick='handleMeetingReschedule(".$meetingid.")'>Reschedule The Meeting </button>
                        </div>";
                    }
                    echo "</div>";
      
                    
                }
            } else {
                echo "<div class='error-message'>No meetings found.</div>";
            }
            
            ?>
            </div>
            </div>
              
        
    </div>
    <div class="viewevent-sidebar">   
    <div class='events-side'>
   <?php
    
    $showAddEvents=false;
    $name = $_SESSION['userDetails']->full_name; //pass the name of the user here
    $role = $_SESSION['userDetails']->role;
    if($role=="secretary"){
        $showAddEvents=true;
    }
    require_once("../app/views/components/Calender/calandar-view.php");
    ?>
    <div class="meeting-detail">
    <div class="name-item"><div class="yellow circle"></div>IUD </div>
    <div class="name-item"><div class="red circle"></div>RHD </div>
    <div class="name-item"><div class="blue circle"></div>BOM </div>
    <div class="name-item"><div class="green circle"></div>Syndicate </div>

    </div>
    <div class="message-box">
    <ul>
        <li class="week_meeting_title">Meetings in this week</li>
        <?php
            $Meetings=$data['meetingsinweek'];
            if($Meetings){            
            foreach ($Meetings as $Meeting) {
                
                echo "<li class='meeting-li'><i class='fas fa-calendar-alt'></i>".strtoupper($Meeting->name)." Meeting -  ".$Meeting->date."</li>";
            }
        }
        else{
            echo "<li>No upcoming meetings in this week</li>";
        }
        ?>
    </ul>
</div>
    </div>
    </div>
<!-- Delete Confirm Modal -->
    <div id="confirmModal" class="modal">
    <div class="modal-content">
        <p id="confirmMessage"></p>
        <div class="delete-modal-actions">
            <button id="confirmNo" class="modal-btn btn-no">No</button>
            <button id="confirmYes" class="modal-btn btn-yes">Yes</button>
        </div>
    </div>
</div>

<!-- Reschedule Modal -->
<div id="rescheduleModal" class="modal">
    <div class="modal-content">
    <span class="close-btn" onclick="closeModal()">&times;</span>
        <h2 class="modal-header">Reschedule Meeting</h2>
        <form id="rescheduleForm">
            <div class="modal-body">
                <label for="newDate">New Date:</label>
                <input type="date" id="newDate" name="newDate" required>
                
                <label for="startTime">New Start Time:</label>
                <input type="time" id="startTime" name="startTime" required>
                
                <label for="endTime">New End Time:</label>
                <input type="time" id="endTime" name="endTime" required>
            </div>
            <div class="modal-actions">
                <button type="button" id="rescheduleYes" class="btn btn-yes">Reschedule</button>
            </div>
        </form>
    </div>
</div>

<!-- Alert Message -->
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
<!-- Popup box -->
<div id="meeting-agenda-popup" class="popup-box">
  <div class="popup-content">
    <span class="close-btn">&times;</span>
    <h2 class="popup-title">Agenda Details</h2>
    
    <!-- Agenda Section -->
    <div class="section">
      <h3 class="section-title">Agenda Items</h3>
      <ul class="item-list" id="agenda-list">
        <!-- List of agenda items will be displayed here -->
      </ul>
      <ul class="item-list" id="meeting-item-list">
        <!-- List of agenda items will be displayed here -->
      </ul>
    </div>

    <!-- Memos Section -->
    <div class="section">
      <h3 class="section-title">Memos Submitted</h3>
      <ul class="item-list" id="memo-list">
        <!-- List of memos will be displayed here -->
      </ul>
    </div>
  </div>
</div>
    <!-- Quick View -->
    <div class="quick-view-popup" id="quick-view-popup">
        <div class="quick-view">
        <span class="close-btn quick-view-close" onclick="closequickview()">&times;</span>
        <div class="quick-view-inside">
            <div class="quick-view-item" id="quick-view-title">
                <!--title here-->
            </div>
            <div class="quick-view-sub">
                <!-- sub title here-->
            </div>
            <div class="quick-view-content" id="quick-view-content">
                <!--content here-->
            </div>
            </div>
            </div>
    </div>

    <script>
        function handleMeetingDelete(meetingId) {
    document.getElementById('confirmMessage').innerText = `Are you sure you want to delete this meeting?`;
    const modal = document.getElementById('confirmModal');
    modal.style.display = 'block';
    document.getElementById('confirmYes').onclick = function () {
        const url = '<?=ROOT?>/Events/deleteMeeting';
        fetch(url, {
                method: 'POST', // Use POST as the PHP function expects POST data
                headers: {
                        'Content-Type': 'application/json', // Specify content type
                        },
                body: JSON.stringify({ meeting_id: meetingId }), // Send meeting ID as JSON
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json(); // Parse JSON response
            })
        .then(data => {
            // Handle success or failure from PHP
        if (data.success) { //success is the key in the json response from php same as the error key
            console.log(data.success); // Log success message

            showAlert(data.success); // Optionally alert the user
            modal.style.display = 'none';

        } else {
            console.error('Deletion failed:', data.error || 'Unknown error');
        }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
    //close the modal on click
    document.getElementById('confirmNo').onclick = function () {
        modal.style.display = 'none';
    };
}

function handleMeetingReschedule(meetingId) {
    const modal = document.getElementById('rescheduleModal');
    modal.style.display = 'block';

    document.getElementById('rescheduleYes').onclick = function () {
        const newDate = document.getElementById('newDate').value;
        const startTime = document.getElementById('startTime').value;
        const endTime = document.getElementById('endTime').value;

    if (newDate && startTime && endTime) {
        const url = '<?=ROOT?>/Events/rescheduleMeeting';
        const data = {
            meeting_id: meetingId,
            newDate: newDate,
            startTime: startTime,
            endTime: endTime
        };
        fetch(url,{
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                console.log(data.success);
                showAlert(data.success);
            } else {
                console.error('Rescheduling failed:', data.error || 'Unknown error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
        modal.style.display = 'none';
        } 
        else {
            showAlert("Please fill in all fields.");
        }
    };
        document.querySelector('.close-btn').onclick = function () {
            modal.style.display = 'none';
        };
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
    window.onclick = function (event) {
    const modal = document.getElementById('confirmModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
    };

    // Get the elements
const agendaButtons = document.getElementsByClassName('view-meeting-agenda');
const popupBox = document.getElementById('meeting-agenda-popup');
const closeButton = popupBox.querySelector('.close-btn');

// Show the popup when the button is clicked
function viewAgendaAndMemos(meetingID){ 
    const url = '<?=ROOT?>/Events/getMemoList';
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ meeting_id: meetingID })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            //console.log(data);
            const memoList = document.getElementById('memo-list');
            memoList.innerHTML = '';
            //console.log(data.memos);
            if(data.memos){
                if(data.forwardedMemos){
                    data.memos=[...data.memos,...data.forwardedMemos];
                }
                data.memos.forEach(memo => {
                    const li = document.createElement('li');
                    li.innerHTML = `<span onclick="showquickview('', '${memo.memo_title}', '${memo.memo_content}')">${memo.memo_title}</span>`;                    
                    memoList.append(li);
                });
            }
            else if(data.forwardedMemos){
                data.forwardedMemos.forEach(memo => {
                    const li = document.createElement('li');
                    li.innerHTML = `<span onclick="showquickview('', '${memo.memo_title}', '${memo.memo_content}')">${memo.memo_title}</span>`;                    
                    memoList.append(li);
                });
            }
            else{
                memoList.innerHTML='';
                const li = document.createElement('li');
                li.innerHTML = `No memos submitted for this meeting.`;
                memoList.appendChild(li);
            }
        } else {
            // const li = document.createElement('li');
            // li.innerHTML = `No memos submitted for this meeting.`;
            // memoList.appendChild(li);
             console.error('Failed to get meeting details:', data.error || 'Unknown error');
        
        }
        popupBox.style.display = 'flex';
    })
    .catch(error => {
        console.error('Error:', error);
    });

    //get agenda List
    const agendaUrl='<?=ROOT?>/Events/getAgendaList';
    const forwardAgendaIds=[];
    fetch(agendaUrl,{
        method: 'POST',
        headers :{
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({meeting_id: meetingID})
    }
    ).then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    }).then(data =>{
        if(data.success){
            
            const agenda=data.agendas;
            const meetingAgenda=data.meetingAgenda;
            const agendaList = document.getElementById('agenda-list');
            agendaList.innerHTML = '<p class="agendatitle">Agenda From Previous Meetings</p>';
            if(agenda.length > 0){

                agenda.forEach(item => {
                    
                    const li = document.createElement('li');
                    const innertext=`${item.title} (From ${item.type.toUpperCase()} Meeting On : ${item.date})`;
                    forwardAgendaIds.push(innertext);
                    li.innerHTML = `<span id="agenda-item" onclick="showquickview('${item.type}', '${item.title}', '${item.content}')">${innertext}</span>`;                    
                    agendaList.appendChild(li);
                });
            } else {
                const li = document.createElement('li');
                li.textContent = 'No agenda items found.';
                agendaList.appendChild(li);
            }

            if(meetingAgenda){
                

                const agendaList = document.getElementById('meeting-item-list');
                agendaList.innerHTML = '<p class="agendatitle">Agenda For This Meeting</p>';
                console.log(forwardAgendaIds);
                let skippeditems=0;
                meetingAgenda.forEach((item,index) => {
                    if(forwardAgendaIds.length>=index+1){
                        if(forwardAgendaIds[index]==item.agenda_item){
                            skippeditems++;
                            return;
                            }
                            else{
                                const li = document.createElement('li');
                    li.innerHTML = `<span id="agenda-item">${item.agenda_item}</span>`;                    
                    agendaList.appendChild(li);
                            }
                        }
                        else{
                            const li = document.createElement('li');
                    li.innerHTML = `<span id="agenda-item">${item.agenda_item}</span>`;                    
                    agendaList.appendChild(li);
                        }


                });

                if(skippeditems==meetingAgenda.length){
                        const li = document.createElement('li');
                        li.innerHTML = `<span id="agenda-item">No agenda items found.</span>`;                    
                        agendaList.appendChild(li);
                    }

                
                
            }
        }
    }).catch(error => {
        console.error('Error:', error);
    });
}


// Close the popup when the close button is clicked
closeButton.addEventListener('click', () => {
   popupBox.style.display = 'none';
});

function showquickview(type='',title='',content='',memo=''){
    const quickview=document.getElementById('quick-view-popup');
    // console.log("i was called");
     quickview.style.display='flex';
     quickview.getElementsByClassName('quick-view-item')[0].innerText=title;
     quickview.getElementsByClassName('quick-view-content')[0].innerHTML=content;

}
function closequickview(){
    const quickview=document.getElementById('quick-view-popup');
    quickview.style.display='none';
}

// Close the popup when clicking outside the content box
window.addEventListener('click', (event) => {
  if (event.target === popupBox) {
    popupBox.style.display = 'none';
    document.getElementById('quick-view-popup').style.display='none';

  }
});


    </script>
</body>
