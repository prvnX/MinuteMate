
<head>
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/secretary/dashboard.style.css">
    <title>Secretary Dashboard</title>
    
</head>
<body>
<?php
    $user="secretary";
    $memocart="memocart";   //use memocart-dot if there is a memo in the cart change with db
    $notification="notification"; //use notification-dot if there's a notification
    $menuItems = [ "home" => ROOT."/secretary",$memocart => ROOT."/secretary/memocart", $notification => ROOT."/secretary/notifications", "profile" => ROOT."/secretary/viewprofile" , "logout" => ROOT."/secretary/confirmlogout"]; //pass the menu items here (key is the name of the page, value is the url)
    require_once("../app/views/components/navbar.php"); //call the navbar component
    ?>
    <div class="dashboard-container">
    <div class="dashboard-button-container">
    <div class="card">
    <img src="<?=ROOT?>/assets/images/create-minute.png" alt="Review Student Memos">
    <a href="<?=ROOT?>/secretary/selectmeeting">
    <button class="card-button">Create a Minute</button>
    </a>
      
    </div>
    <div class="card">
    <img src="<?=ROOT?>/assets/images/enter memo.jpg" alt="Enter a Memo">
    <a href="<?=ROOT?>/secretary/entermemo">
    <button class="card-button">Enter a Memo</button>
    </a>
      
    </div>
    <div class="card">
      <img src="<?=ROOT?>/assets/images/view minutes.jpg" alt="View Minutes">
      <a href="<?=ROOT?>/secretary/viewminutes">
      <button class="card-button">View Minutes</button>
        </a>
    </div>
    <div class="card">
      <img src="<?=ROOT?>/assets/images/view submitted memos.jpg" alt="View  Memos">
        <a href="<?=ROOT?>/secretary/viewmemos">
      <button class="card-button">View Memos</button>
        </a>
    </div>
    <div class="card">

      <img src="<?=ROOT?>/assets/images/view memo report.jpeg" alt="View Memo Reports">
        <a href="<?=ROOT?>/secretary/viewmemoreports">
      <button class="card-button">View Memo Reports</button>
        </a>
    </div>
    <div class="card">
      <img src="<?=ROOT?>/assets/images/view minute report.png" alt="View Minute Report">
        <a href="<?=ROOT?>/secretary/viewminutereports">
      <button class="card-button">View Minute Report</button>
        </a>
    </div>
</div>
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
