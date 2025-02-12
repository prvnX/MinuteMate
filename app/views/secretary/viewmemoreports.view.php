<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/secretary/viewmemoreports.style.css">
    <title>Memo Report</title>
    <link rel="icon" href="<?=ROOT?>/img.png" type="image">

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
    <!-- Header -->
    <div class="header">
        <h1>Memo Report</h1>
    </div>

    <!-- Date and Time -->
    <div class="form-group">
        <label>memo Id:</label>
        <span><?= htmlspecialchars($data['id']) ?></span>
    </div>

    <div class="form-group">
        <label>Date:</label>
        <span><?= htmlspecialchars($data['date']) ?></span>
    </div>

    <div class="form-group">
        <label>Time:</label>
        <span><?= htmlspecialchars($data['time']) ?></span>
    </div>

    <!-- Memo Details -->
    <div class="form-group">
        <label>Status:</label>
        <span><?= htmlspecialchars($data['status']) ?></span>
    </div>
    <div class="form-group">
        <label>Linked Memos:</label>
        <span><?= htmlspecialchars($data['linked_memos']) ?></span>
    </div>
    <div class="form-group">
        <label>Author:</label>
        <span><?= htmlspecialchars($data['author']) ?></span>
    </div>

    <!-- Footer -->
    <div class="footer">
    <img src="<?=ROOT?>/assets/images/img.png" alt="logo">
    </div>
</div>
</body>
</html>
