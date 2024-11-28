
<head>
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/studentrep/student.style.css">
    <title>studentrep Dashboard</title>
    
</head>
<body>
<?php
    $user="studentrep";
    $memocart="memocart";   //use memocart-dot if there is a memo in the cart change with db
    $notification="notification"; //use notification-dot if there's a notification
    $menuItems = [ "home" => ROOT."/studentrep", $notification => ROOT."/studentrep/notifications", "profile" => ROOT."/studentrep/viewprofile","logout"=> ROOT."/studentrep/confirmlogout"]; //pass the menu items here (key is the name of the page, value is the url)
    require_once("../app/views/components/navbar.php"); //call the navbar component
    ?>
    <div class="dashboard-container">
    <div class="dashboard-button-container">
     
    <div class="card">
    <img src="<?=ROOT?>/assets/images/enter memo.jpg" alt="Enter a Memo">
    <a href="<?=ROOT?>/studentrep/entermemo">
    <button class="card-button">Enter a Memo</button>
    </a>
      
    </div>
     
    <div class="card">
      <img src="<?=ROOT?>/assets/images/view submitted memos.jpg" alt="View Submitted Memos">
        <a href="<?=ROOT?>/studentrep/viewsubmittedmemos">
      <button class="card-button">View submitted Memos</button>
        </a>
    </div>
    <div class="card">
      <img src="<?=ROOT?>/assets/images/view minutes.jpg" alt="View Minutes">
      <a href="<?=ROOT?>/studentrep/viewminutes">
      <button class="card-button">View Minutes</button>
        </a>
    </div>
    <div class="card">

      <img src="<?=ROOT?>/assets/images/view memo report.jpeg" alt="View Memo Reports">
        <a href="<?=ROOT?>/studentrep/viewmemoreports">
      <button class="card-button">View Memo Reports</button>
        </a>
    </div>
    
</div>
    <?php
   
    $showAddEvents=false;
    $notificationsArr =[
        "Reminder: Meeting scheduled for tomorrow at 3 PM.",
        "Update: Minutes from yesterday's meeting are now available.",
        "Memo Added: A new memo has been attached to this week’s meeting.",
        "Backup Complete: Meeting records were successfully backed up today.",
    ]; //pass the notifications here
    $name = $_SESSION['userDetails']->full_name; //pass the name of the user here  
    require_once("../app/views/components/dashboard-sidebar.php"); //call the dashboard sidebar component
    ?>
    </div>
    </div>
</body>
