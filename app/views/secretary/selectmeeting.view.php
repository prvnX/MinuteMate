<head>
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/secretary/selectmeeting.style.css">
    <title>Please Select a meeting</title>
    
</head>
<body>
<?php
    $user="secretary";
    $memocart="memocart";   //use memocart-dot if there is a memo in the cart change with db
    $notification="notification"; //use notification-dot if there's a notification
    $menuItems = [ "home" => ROOT."/secretary",$memocart => ROOT."/secretary/memocart", $notification => ROOT."/secretary/notifications", "profile" => ROOT."/secretary/viewprofile"]; //pass the menu items here (key is the name of the page, value is the url)
    require_once("../app/views/components/navbar.php"); //call the navbar component
    $meetings= [
        1 => array(
            'id' => 12,
            'name' => 'Meeting A',
            'date' => '2024-11-20'
        ),
        2 => array(
            'id' => 13,
            'name' => 'Meeting B',
            'date' => '2024-11-21'
        ),
        3 => array(
            'id' => 14,
            'name' => 'Meeting C',
            'date' => '2024-11-22'
        ),
        4 => array(
            'id' => 15,
            'name' => 'Meeting D',
            'date' => '2024-11-23'
        ),
        5 => array(
            'id' => 16,
            'name' => 'Meeting E',
            'date' => '2024-11-24'
        )
        ];
    ?>
    <div class="select-meeting-container">
    <h1 class="select-meeting-heading">Please select a meeting</h1>
    <select name="meeting" id="meeting" class="select-meeting">
        <option value="" disabled selected>Select a meeting</option>
        <?php
        foreach($meetings as $meeting) {
            $key=$meeting['id'];
            echo "<option value='$key'>".$meeting['name']." - ".$meeting['date']."</option>";
        }
        ?>
    </select>
    <button class="select-meeting-button" id="selectMeetingButton">Create Minute</button>
    <button class="select-meeting-button" id="cancelButton">Cancel</button>
    </div>
    <div class="calender">
    <?php  
            $showAddEvents=false;
            require_once("../app/views/components/Calender/calandar-view.php"); //call the calander component
            ?>
    </div>
    <script>
        document.getElementById("selectMeetingButton").addEventListener("click", function() {
            var meeting = document.getElementById("meeting").value;
            if(meeting) {
                window.location.href = "<?=ROOT?>/secretary/createminute?meeting="+meeting;
            }
        });
        document.getElementById("cancelButton").addEventListener("click", function() {
            window.location.href = "<?=ROOT?>/secretary";
        });
    </script>