<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lecturer Dashboard</title>
  <link rel="stylesheet" href="<?=ROOT?>/assets/css/lecturer/lecturer.style.css">
</head>

<body>
    

<div class="navbar">
<?php
    
    $user="lecturer";
    $notification="notification"; //use notification-dot if there's a notification
    $menuItems = [ "home" => ROOT."/lecturer", $notification => ROOT."/lecturer/notifications", "profile" => ROOT."/lecturer/viewprofile"]; //pass the menu items here (key is the name of the page, value is the url)
    require_once("../app/views/components/navbar.php"); //call the navbar component
    $showAddEvents = false; 
   ?>
</div>

  <div class="container">
    <div class="card">
      <img src="<?=ROOT?>/assets/images/enter memo.jpg" alt="Enter a Memo">
      <a href="<?=ROOT?>/lecturer/entermemo">
      <button class = "card button">Enter a Memo</button>
    </a>
    </div>

    <div class="card">
      <img src="<?=ROOT?>/assets/images/review memos.jpeg" alt="Review Student Memos">
      <a href="<?=ROOT?>/lecturer/reviewstudentmemos">
      <button class = "card button">Review Student Memos</button>
      </a>
    </div>

    <div class="card">
      <img src="<?=ROOT?>/assets/images/view minutes.jpg" alt="View Minutes">
      <a href="<?=ROOT?>/lecturer/viewminutes">
      <button class = "card button">View Minutes</button>
      </a>
    </div>

    <div class="card">
      <img src="<?=ROOT?>/assets/images/view submitted memos.jpg" alt="View Submitted Memos">
      <a href="<?=ROOT?>/lecturer/viewsubmittedmemos">
      <button class = "card button">View Submitted Memos</button>
      </a>
    </div>

    <div class="card">
      <img src="<?=ROOT?>/assets/images/view memo report.jpeg" alt="View Memo Reports">
      <a href="<?=ROOT?>/lecturer/viewmemoreports">
      <button class = "card button">View Memo Reports</button>
      </a>
    </div>

    <div class="card">
      <img src="<?=ROOT?>/assets/images/view minute report.png" alt="View Minute Report">
      <a href="<?=ROOT?>/lecturer/viewminutereports">
      <button class = "card button">View Minute Report</button>
      </a>
    </div>
  </div>

  <?php
    $notificationsArr =[
        "Reminder: Meeting scheduled for tomorrow at 3 PM.",
        "Update: Minutes from yesterday's meeting are now available.",
       
    ]; //pass the notifications here
    $name = "John Doe"; //pass the name of the user here  
    require_once("../app/views/components/dashboard-sidebar.php"); //call the dashboard sidebar component
    ?>
    
</body>

</html>
