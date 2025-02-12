 


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/secretary/viewminutereports.style.css">
    <link rel="icon" href="<?=ROOT?>/img.png" type="image">

    <title>Minute Report</title>
</head>
<body>

<div class="navbar">
<?php
    $user="secretary";
    $memocart="memocart";   //use memocart-dot if there is a memo in the cart change with db
    $notification="notification"; //use notification-dot if there's a notification
    $menuItems = [ "home" => ROOT."/secretary",$memocart => ROOT."/secretary/memocart", $notification => ROOT."/secretary/notifications", "profile" => ROOT."/secretary/viewprofile"]; //pass the menu items here (key is the name of the page, value is the url)
    require_once("../app/views/components/navbar.php"); //call the navbar component
   ?>

   
</div>







<div class="container">
    <div class="header">
        <h1>Minute Report</h1>
    </div>
    <div class="form-group">
        <label>Date:</label>
        <span><?= htmlspecialchars($data['date']) ?></span>
    </div>
    <div class="form-group">
        <label>Time:</label>
        <span><?= htmlspecialchars($data['time']) ?></span>
    </div>
    <div class="form-group">
        <label>Meeting Type:</label>
        <span><?= htmlspecialchars($data['meeting_type']) ?></span>
    </div>
    <div class="form-group">
        <label>Meeting Minute:</label>
        <span><?= htmlspecialchars($data['meeting_minute']) ?></span>
    </div>
    <div class="form-group">
        <label>Linked Minutes:</label>
        <span><?= htmlspecialchars($data['linked_minutes']) ?></span>
    </div>
    <div class="form-group">
        <label>Linked Memos:</label>
        <span><?= htmlspecialchars($data['linked_memos']) ?></span>
    </div>
    <div class="form-group">
        <label>Recording:</label>
        <span><a href="<?= htmlspecialchars($data['recording']) ?>" target="_blank">View Recording</a></span>
    </div>
    <div class="form-group">
        <label>Attendees:</label>
        <span><?= htmlspecialchars($data['attendees']) ?></span>
    </div>
    <div class="footer">
    <img src="<?=ROOT?>/assets/images/img.png" alt="logo">
    </div>
</div>
</body>
</html>
