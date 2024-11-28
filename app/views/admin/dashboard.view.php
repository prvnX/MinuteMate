<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="<?=ROOT?>/assets/css/admin/admin.style.css">
</head>

<body>
    

<div class="navbar">
<?php
    
    $user="admin";
    $notification="notification"; //use notification-dot if there's a notification
    $menuItems = [ "home" => ROOT."/admin", $notification => ROOT."/admin/notifications", "profile" => ROOT."/admin/viewprofile","logout" => ROOT."/admin/confirmlogout"]; //pass the menu items here (key is the name of the page, value is the url)
    require_once("../app/views/components/navbar.php"); //call the navbar component
    $showAddEvents = false; 
   ?>
</div>

  <div class="container">
    <div class="card">
      <img src="<?=ROOT?>/assets/images/View Pending Member Request.jpeg" alt="View Pending Member Request">
      <a href="<?=ROOT?>/admin/viewpendingRequests">
      <button>View Pending Member Request</button>
      </a>
    </div>

    <div class="card">
      <img src="<?=ROOT?>/assets/images/View Members.jpeg" alt="View Members">
      <a href="<?=ROOT?>/admin/viewMembers">
      <button>View Members</button>
    </a>
    </div>

    <div class="card">
      <img src="<?=ROOT?>/assets/images/Remove Members.jpeg" alt="Remove Members">
      <a href="<?=ROOT?>/admin/PastMembers">
      <button>Past Members</button>
    </a>
    </div>
    <div class="card">
      <img src="<?=ROOT?>/assets/images/edit.png" alt="Remove Members">
      <a href="<?=ROOT?>/admin/vieweditrequests">
      <button>View Edit Requests</button>
    </a>
    </div>
    </div>
    
    <div class="aside">
  <?php
    $notificationsArr =[
        "Requests: 3 Requests are pending.",
        "Reminder: Meeting shceduled for tommorow at a 3 pm ",
       
    ]; //pass the notifications here
    $name = $_SESSION['userDetails']->full_name; //pass the name of the user here  
    require_once("../app/views/components/dashboard-sidebar.php"); //call the dashboard sidebar component
    ?>
 </div>

    
</body>

</html>
