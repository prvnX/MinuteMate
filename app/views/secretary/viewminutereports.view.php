<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?=ROOT?>/img.png" type="image">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/secretary/viewminutereports.style.css">
    <title>Minute Report</title>
</head>
<body>

<?php
    
    $user="secretary";
    $minutecart="minutecart";   //use minutecart-dot if there is a minute in the cart change with db
    $notification="notification"; //use notification-dot if there's a notification
    $menuItems = [ "home" => ROOT."/secretary" , $notification => ROOT."/secretary/notifications", "profile" => ROOT."/secretary/viewprofile"  ]; //pass the menu items here (key is the name of the page, value is the url)
    require_once("../app/views/components/new_navbar.php"); //call the navbar component
    require_once("../app/views/components/lec_sidebar.php"); //call the sidebar component
    
?>

<div class="container">
    <!-- Header -->
    <div class="header">
        <h1>Minute Report</h1>
        <h2>Minute Details</h2>
    </div>

    <div class="form-group">
        <label>Title:</label>
        <span><?= htmlspecialchars($data['minuteDetails']->title) ?></span>
    </div>
    <div class="form-group">
        <label>Meeting Type:</label>
        <span><?= htmlspecialchars($data['minuteDetails']->meeting_type) ?> Meeting</span>
    </div>
    <div class="form-group">
        <label>Meeting ID:</label>
        <span><?= htmlspecialchars($data['minuteDetails']->MeetingID) ?></span>
    </div>
   
    <div class="form-group">
        <label>Minute ID:</label>
        <span><?= htmlspecialchars($data['minuteDetails']->Minute_ID) ?>
        <a href="<?=ROOT."/".$_SESSION['userDetails']->role."/viewminute?minuteID=".$data['minuteDetails']->Minute_ID?>"
        > (click here to view the minute) </a></span>
        
    
    </div>
    <div class="form-group">
        <label>Searching Keywords:</label>
        <span><?= htmlspecialchars($data['minuteDetails']->keywords) ?></span>        
    </div>
    <div class="form-group">
        <label>Author:</label>
        <span><?= htmlspecialchars($data['minuteDetails']->user) ?></span>
    </div>


    <div class="form-group">
        <label>Date:</label>
        <span><?= htmlspecialchars($data['minuteDetails']->created_date) ?></span>
    </div>
    <div class="form-group">
        <label>Linked Minutes</label>
        <span><?= $linkedMinutes=$data['minuteDetails']->linked_minutes;
                    if($linkedMinutes==null){
                        echo "No linked minutes";
                    }else{
                        $linkedMinutes = explode(",", $linkedMinutes);
                    foreach($linkedMinutes as $linkedMinute){
                        echo htmlspecialchars($linkedMinute).", ";
                        echo '<a href="' . ROOT . '/' . $_SESSION['userDetails']->role . '/viewminute?minuteID=' . $data['minuteDetails']->linked_minutes . '">View Minute</a>';}

                }
        
        
        ?>
        <a href="<?=ROOT."/".$_SESSION['userDetails']->role."/viewminute?minuteID=".$data['minuteDetails']->linked_minutes?>"
        > (click here to view the linked minute) </a></span>
    </div>
    </span>
     <!-- Footer -->
     <div class="footer">
        <img src="<?=ROOT?>/assets/images/img.png" alt="logo">
    </div>
    </div>
    
 

    
</div>
</body>
</html>