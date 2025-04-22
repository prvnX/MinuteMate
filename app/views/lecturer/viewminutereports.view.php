<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?=ROOT?>/img.png" type="image">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/lecturer/viewminutereports.style.css">
    <title>Minute Report</title>
</head>
<body>

<?php
     
    $user="lecturer";
    $minutecart="minutecart";   //use minutecart-dot if there is a minute in the cart change with db
    $notification="notification"; //use notification-dot if there's a notification
    $menuItems = [ "home" => ROOT."/lecturer" , $notification => ROOT."/lecturer/notifications", "profile" => ROOT."/lecturer/viewprofile"  ]; //pass the menu items here (key is the name of the page, value is the url)
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
        <span><?= htmlspecialchars($data['minuteDetails']->meeting_type) ?></span>
    </div>
    <div class="form-group">
        <label>Minute ID:</label>
        <span><?= htmlspecialchars($data['minuteDetails']->MeetingID) ?></span>
    </div>
    <div class="form-group">
        <label>Date:</label>
        <span><?= htmlspecialchars($data['minuteDetails']->created_date) ?></span>
    </div>
    <div class="form-group">
        <label>Linked Minutes</label>
        <span><?= htmlspecialchars($data['minuteDetails']->linked_minutes) ?></span>
    </div>
    <div class="form-group">
        <label>Author:</label>
        <span><?= htmlspecialchars($data['minuteDetails']->created_by) ?></span>
    </div>

    <div class="header">
        <h3>Flow of the Minute through Different Meetings</h3>

        <div class="timeline">
        <?php foreach ($data['timeline'] as $step): ?>
            <div class="timeline-step">
                <div class="dot-wrapper">
                    <div class="dot" data-tooltip="<?= htmlspecialchars($step['label'] . ': ' . $step['date']) ?>"></div>
                </div>
                <div class="label"><?= htmlspecialchars($step['label']) ?></div>
                <div class="date"><?= htmlspecialchars($step['date']) ?></div>
            </div>
        <?php endforeach; ?>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <img src="<?=ROOT?>/assets/images/img.png" alt="logo">
    </div>
</div>
</body>
</html>