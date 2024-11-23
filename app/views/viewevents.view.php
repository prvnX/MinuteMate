
<head>
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/viewevents.style.css">
    <title>Secretary Dashboard</title>
    
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
    require_once("../app/views/components/navbar.php"); //call the navbar component
    ?>

    <div class="dashboard-container">  
    <div class="events-container">
        <h1>Events on : <?= $date ?></h1>
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
                    echo "Meeting ID: " . htmlspecialchars($meeting['meeting_id']) . "<br>";
                    echo "Date: " . htmlspecialchars($meeting['date']) . "<br>";
                    echo "Start Time: " . htmlspecialchars($meeting['start_time']) . "<br>";
                    echo "End Time: " . htmlspecialchars($meeting['end_time']) . "<br>";
                    echo "Location: " . htmlspecialchars($meeting['location']) . "<br>";
                    echo "Meeting Type: " . htmlspecialchars($meeting['meeting_type']) . "<br>";
                    echo "Additional Notes: " . htmlspecialchars($meeting['additional_note']) . "<br>";
                    echo htmlspecialchars($meeting['memos'])." memos submitted";
                    echo "</div>";
                }
            } else {
                echo "<div class='error-message'>No meetings found.</div>";
            }
            ?>
              
        
    </div>
    <div class="viewevent-sidebar">   
   
   
   
   
   
   
   
   
   
   
   
   <?php
   



    $showAddEvents=true;
    $notificationsArr =[
        "Reminder: Meeting scheduled for tomorrow at 3 PM.",
        "Update: Minutes from yesterday's meeting are now available.",
        "Memo Added: A new memo has been attached to this week’s meeting."    ]; //pass the notifications here
    $name = $_SESSION['userDetails']->full_name; //pass the name of the user here
    require_once("../app/views/components/dashboard-sidebar.php"); //call the dashboard sidebar component
    ?>
    </div>
    </div>
</body>
