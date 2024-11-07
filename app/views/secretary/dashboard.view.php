
<head>
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/secretary/dashboard.style.css">
    <title>Secretary Dashboard</title>
    
</head>
<body>
<?php
    $user="secretary";
    $memocart="memocart";   //use memocart-dot if there is a memo in the cart change with db
    $notification="notification"; //use notification-dot if there's a notification
    $menuItems = [ "home" => ROOT."/secretary",$memocart => ROOT."/secretary/memocart", $notification => ROOT."/secretary/notifications", "profile" => ROOT."/secretary/viewprofile"]; //pass the menu items here (key is the name of the page, value is the url)
    require_once("../app/views/components/navbar.php"); //call the navbar component
    ?>
    <div class="dashboard-container">
    <div class="dashboard-buttons-container">
        <h1>Dashboard</h1>
        <h2>Welcome, <?=$name?></h2>
    </div>
    <div>
    <?php
   
    $showAddEvents=true;
    $notificationsArr =[
        "Reminder: Meeting scheduled for tomorrow at 3 PM.",
        "Update: Minutes from yesterday's meeting are now available.",
        "Memo Added: A new memo has been attached to this week’s meeting.",
        "Backup Complete: Meeting records were successfully backed up today.",
    ]; //pass the notifications here
    $name = "John Doe"; //pass the name of the user here  
    require_once("../app/views/components/dashboard-sidebar.php"); //call the dashboard sidebar component
    ?>
    </div>
    </div>
</body>
