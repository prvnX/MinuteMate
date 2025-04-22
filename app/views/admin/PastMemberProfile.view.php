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
      <div class="add-button-wrapper">
      <button class="add-btn" onclick="editModal()">Add</button>
      </div>

      <div id="editModal" class="modal">
        <div class="modal-content">
          <span class="close" id="closeModal">&times;</span>
          <h2>Edit Member Details</h2>
          <form action="<?= ROOT ?>/admin/reactivateMember" method="POST">
            <input type="text" name="username" value="<?= htmlspecialchars($userData->username) ?>">

            <label>Full Name:</label>
            <input type="text" name="full_name" value="<?= htmlspecialchars($userData->full_name) ?>" required>

            <label>Email:</label>
            <input type="text" name="email" value="<?= htmlspecialchars($userData->email) ?>" required>
          
            <label>NIC:</label>
            <input type="text" name="nic" value="<?= htmlspecialchars($userData->nic) ?>" required>

            <label>Contact No.:</label>
            <input type="text" name="contact_no" value="<?= htmlspecialchars($userData->contact_no) ?>">
      
            <label>Additional Contact No.:</label>
            <input type="text" name="additional_tp_no" value="<?= htmlspecialchars($userData->additional_tp_no) ?>"> 
            
            <label>Select Role(s):</label>
            <div class="role-option">
              <?php
              $allRoles = ['secratary', 'lecturer', 'student Representative'];
              $existingRoles = $userData->role;

              foreach($allRoles as $role){
                $checked = in_array($role, $existingRoles) ? 'checked' : '';
                echo "<label><input type='checkbox' name='roles[]' value='$role' $checked> " . ucfirst($role) . "</label>";
              }
              ?>
            </div>

              <label>Select Meeting Type(s):</label>
            <div class="meeting-options">
                <?php
                
                foreach (['RHD', 'IOD', 'SYN', 'BOM'] as $type) {
                  $checked = in_array($type, $userData->meetingTypes) ? 'checked' : '';
                  echo "<label><input type='checkbox' name='meeting_types[]' value='$type' $checked> $type</label>";
              }
              ?>
            </div>

              <input type="hidden" name="reactivate" value="1">
              <button type="submit" class="btn-add">Confirm</button>
          </form>

    </div>
  <?php else: ?>
    <p>Member not found.</p>
  <?php endif; ?>
</div>
</body>
</html>

<script>
  const modal = document.getElementById("editModal");
  const closeBtn = document.getElementById("closeModal");

  function editModal() {
    modal.style.display = "block";
  }

  closeBtn.onclick = () => modal.style.display = "none";

  window.onclick = (e) => {
    if (e.target == modal) modal.style.display = "none";
  };
</script>
