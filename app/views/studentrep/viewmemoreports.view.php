<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?=ROOT?>/img.png" type="image">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/studentrep/viewmemoreports.style.css">
    <title>Memo Report</title>
</head>
<body>

 
<?php
    
    $user="studentrep";
    $memocart="memocart";   //use memocart-dot if there is a memo in the cart change with db
    $notification="notification"; //use notification-dot if there's a notification
    $menuItems = [ "home" => ROOT."/studentrep" , $notification => ROOT."/studentrep/notifications", "profile" => ROOT."/studentrep/viewprofile"  ]; //pass the menu items here (key is the name of the page, value is the url)
    require_once("../app/views/components/new_navbar.php"); //call the navbar component
    require_once("../app/views/components/std_sidebar.php"); //call the sidebar component
    
    
    ?>

 


<div class="container">
    <!-- Header -->
    <div class="header">
        <h1>Memo Report</h1>
        <h2>Memo Details</h2>
    </div>

<div class="form-group">
    <label>Title:</label>
    <span><?= htmlspecialchars($data['memoDetails']->title) ?></span>
</div>
<div class="form-group">
    <label>Meeting Type:</label>
    <span><?= htmlspecialchars($data['memoDetails']->meeting_type)  ?></span>
</div>
<div class="form-group">
    <label>Memo ID:</label>
    <span><?= htmlspecialchars($data['memoDetails']->id) ?></span>
</div>
<div class="form-group">
    <label>Date:</label>
    <span><?= htmlspecialchars($data['memoDetails']->date) ?></span>
</div>
<div class="form-group">
    <label>Status:</label>
    <span><?= htmlspecialchars($data['memoDetails']->status) ?></span>
</div>
<div class="form-group">
    <label>Author:</label>
    <span><?= htmlspecialchars($data['memoDetails']->user) ?></span>
</div>
    <!-- <div class="header">
    <h3>Flow of the Memo through Different Meetings</h3>

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

    </div> -->

    <!-- Footer -->
    <div class="footer">
    <img src="<?=ROOT?>/assets/images/img.png" alt="logo">
    </div>
</div>
</body>
</html>
