<head>
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/secretary/selectminute.style.css">
    <title>Please Select a Minute</title>
    
</head>
<body>
<?php
    $user="secretary";
    $memocart="memocart";   //use memocart-dot if there is a memo in the cart change with db
    $notification="notification"; //use notification-dot if there's a notification
    $menuItems = [ "home" => ROOT."/secretary",$memocart => ROOT."/secretary/memocart", $notification => ROOT."/secretary/notifications", "profile" => ROOT."/secretary/viewprofile"]; //pass the menu items here (key is the name of the page, value is the url)
    require_once("../app/views/components/navbar.php"); //call the navbar component
    $minutes= [
        1 => array(
            'id' => 12,
            'Title' => 'Minute A',
            'date' => '2024-11-20'

        ),
        2 => array(
            'id' => 13,
            'Title' => 'Minute B',
            'date' => '2024-11-21'
        ),
        3 => array(
            'id' => 14,
            'Title' => 'Minute C',
            'date' => '2024-11-22'
        ),
        4 => array(
            'id' => 15,
            'Title' => 'Minute D',
            'date' => '2024-11-23'
        ),
        5 => array(
            'id' => 16,
            'Title' => 'Minute E',
            'date' => '2024-11-24'
        )
        ];
    ?>
    <div class="select-minute-container">
    <h1 class="select-minute-heading">Please select a minute</h1>
    <select name="minute" id="minute" class="select-minute">
        <option value="" disabled selected>Select a minute</option>
        <?php
        foreach($minutes as $minute) {
            $key=$minute['id'];
            echo "<option value='$key'>".$minute['Title']." - ".$minute['date']."</option>";
        }
        ?>
    </select>
    <button class="select-minute-button" id="selectminuteButton">view minute report</button>
    <button class="select-minute-button" id="cancelButton">Cancel</button>
    </div>
    <div class="calender">
    <?php  
            $showAddEvents=false;
            require_once("../app/views/components/Calender/calandar-view.php"); //call the calander component
            ?>
    </div>
    <script>
        document.getElementById("selectminuteButton").addEventListener("click", function() {
            var minute = document.getElementById("minute").value;
            if(minute) {
                window.location.href = "<?=ROOT?>/secretary/viewminutereports?minute="+minute;
            }
        });
        document.getElementById("cancelButton").addEventListener("click", function() {
            window.location.href = "<?=ROOT?>/secretary";
        });
    </script>


