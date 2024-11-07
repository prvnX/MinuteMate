<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Memo Interface</title>
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
      <button>Enter a Memo</button>
    </div>
    <div class="card">
      <img src="<?=ROOT?>/assets/images/review memos.jpeg" alt="Review Student Memos">
      <button>Review Student Memos</button>
    </div>
    <div class="card">
      <img src="<?=ROOT?>/assets/images/view minutes.jpg" alt="View Minutes">
      <button>View Minutes</button>
    </div>
    <div class="card">
      <img src="<?=ROOT?>/assets/images/view submitted memos.jpg" alt="View Submitted Memos">
      <button>View Submitted Memos</button>
    </div>
    <div class="card">
      <img src="<?=ROOT?>/assets/images/view memo report.jpeg" alt="View Memo Reports">
      <button>View Memo Reports</button>
    </div>
    <div class="card">
      <img src="<?=ROOT?>/assets/images/view minute report.png" alt="View Minute Report">
      <button>View Minute Report</button>
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
