<?php 

$user="admin";
$notification="notification"; //use notification-dot if there's a notification
$menuItems = [ "home" => ROOT."/admin" , $notification => ROOT."/admin/notifications", "profile" => ROOT."/admin/viewprofile" , "logout" => ROOT."/admin/confirmlogout"]; //pass the menu items here (key is the name of the page, value is the url)
require_once("../app/views/components/admin_navbar.php"); //call the navbar component
include '../app/views/components/admin_sidebar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Members</title>
  <link rel="stylesheet" href="<?=ROOT?>/assets/css/admin/viewMembers.style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body>

<main class="main-content">
  <div class="container">
    <div class="card-grid">
      <div class="card">
        <div class="icon-circle red"><i class="fas fa-users"></i></div>
        <h3 class="card=title"> RHD Members</h3>
        <p clas="card-description">View members of the RHD committee</p>
        <a href="<?=ROOT?>/admin/viewMembersByMeetingType?meetingType=RHD" class="card-btn">RHD</a>
      </div>

      <div class="card">
        <div class="icon-circle yellow"><i class="fas fa-users"></i></div>
        <h3 class="card=title"> IUD Members</h3>
        <p clas="card-description">View members of the IUD committee</p>
        <a href="<?=ROOT?>/admin/viewMembersByMeetingType?meetingType=IUD" class="card-btn">IUD</a>
      </div>

      <div class="card">
        <div class="icon-circle green"><i class="fas fa-users"></i></div>
        <h3 class="card=title"> SYN Members</h3>
        <p clas="card-description">View members of the SYN committee</p>
        <a href="<?=ROOT?>/admin/viewMembersByMeetingType?meetingType=SYN" class="card-btn">SYN</a>
      </div>

      <div class="card">
        <div class="icon-circle blue"><i class="fas fa-users"></i></div>
        <h3 class="card=title"> BOM Members</h3>
        <p clas="card-description">View members of the BOM committee</p>
        <a href="<?=ROOT?>/admin/viewMembersByMeetingType?meetingType=BOM" class="card-btn">BOM</a>
      </div>
    </div>
  </div>
</main>
</body>
</html>
