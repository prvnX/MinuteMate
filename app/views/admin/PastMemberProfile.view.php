<?php

$user="admin";
$notification="notification"; //use notification-dot if there's a notification
$menuItems = [ "home" => ROOT."/admin" , $notification => ROOT."/admin/notifications", "profile" => ROOT."/admin/viewprofile" , "logout" => ROOT."/admin/confirmlogout"]; //pass the menu items here (key is the name of the page, value is the url)
require_once("../app/views/components/new_navbar.php"); 
include '../app/views/components/admin_sidebar.php';

$userData = $data['userData'] ?? null;
$deletedData = $data['deletedData'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Past Member Profile</title>
  <link rel="stylesheet" href="<?=ROOT?>/assets/css/admin/pastMemberProfile.style.css">
</head>
<body>

<div class="profile-container">
  <?php if ($userData && $deletedData): ?>
    <div class="profile-details">
      <div class="content">
        <div class="profile-header">
            <img src="<?= ROOT ?>/assets/images/user.png" alt="Profile Image" class="profile-img">
            <div class="profile-info">
              <h3><?php echo htmlspecialchars($userData->full_name); ?></h3>
              <p><strong>Name:</strong> <?= htmlspecialchars($userData->full_name) ?></p>
              <p><strong>Email:</strong> <?= htmlspecialchars($userData->email) ?></p>
              <p><strong>Lecturer ID:</strong> <?= htmlspecialchars($userData->username) ?></p>
              <p><strong>NIC:</strong> <?= htmlspecialchars($userData->nic) ?></p>
              <p><strong>Role:</strong> <?= htmlspecialchars(implode(', ', $userData->role )) ?></p>
              <p><strong>Contact No.:</strong> <?= htmlspecialchars($userData->contact_no) ?></p>
              <p><strong>Additional Contact No.:</strong> <?= htmlspecialchars($userData->additional_tp_no) ?></p>
              <p><strong>Removed By:</strong> <?= htmlspecialchars($deletedData->removed_by) ?></p>
              <p><strong>Status:</strong> <?= htmlspecialchars($userData->status) ?></p>
              <p><strong>Reason:</strong> <?= htmlspecialchars($deletedData->reason) ?></p>

              <label>Select Meeting Type(s):</label>
              <div class="meeting-options">
                <?php
                $userData->meetingTypes = isset($userData->meetingTypes) ? array_map('strtoupper', $userData->meetingTypes) : [];
                $meetingTypes = ['RHD', 'IOD', 'SYN', 'BOM'];

                foreach ($meetingTypes as $type) {
                  $checked = in_array($type, $userData->meetingTypes) ? 'checked' : '';
                  $class = strtolower($type) . '-option';

                  echo "<div class='meeting-option $class'>
                        <input type='checkbox' id='meeting$type' name='meetingType[]' value='$type' $checked>
                        <label for='meeting$type'>$type</label>
                  </div>";
                }
                ?>
              </div>


            </div>
        </div>
      </div>
      <div class="action-buttons">
        <a href="<?=ROOT?>/admin/addPastMember?id=<?= htmlspecialchars($memberId) ?>" class="btn-add">Add</a>
      </div>
    </div>
  <?php else: ?>
    <p>Member not found.</p>
  <?php endif; ?>
</div>



</body>
</html>
