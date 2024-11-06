<html>
<head>
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/secretary/dashboard.style.css">
    
</head>
<body>
<?php
    $user="secretary";
    $memocart="memocart";   //use memocart-dot if there is a memo in the cart change with db
    $notification="notification"; //use notification-dot if there's a notification
    $menuItems = [ "home" => ROOT."/secretary",$memocart => ROOT."/secretary/memocart", $notification => ROOT."/secretary/notifications", "profile" => ROOT."/secretary/viewprofile"]; //pass the menu items here (key is the name of the page, value is the url)
    require_once("../app/views/components/navbar.php"); //call the navbar component
    $showAddEvents = true;
    ?>
    <div>
    <?php  
    require_once("../app/views/components/Calender/calandar-view.php"); //call the calander component
    ?>
    </div>
</body>

</html>