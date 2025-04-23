<?php

$user="admin";
$notification="notification"; //use notification-dot if there's a notification
$menuItems = [ 
  "home" => ROOT."/admin", 
  $notification => ROOT."/admin/notifications", 
  "profile" => ROOT."/admin/viewprofile", 
  "logout" => ROOT."/admin/confirmlogout"
];
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
            <h3><?= htmlspecialchars($userData->full_name) ?></h3>
            <p><strong>Name:</strong> <?= htmlspecialchars($userData->full_name) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($userData->email) ?></p>
            <p><strong>Lecturer ID:</strong> <?= htmlspecialchars($userData->username) ?></p>
            <p><strong>NIC:</strong> <?= htmlspecialchars($userData->nic) ?></p>
            <p><strong>Role:</strong> <?= htmlspecialchars(implode(', ', $userData->role)) ?></p>
            <p><strong>Contact No.:</strong> <?= htmlspecialchars($userData->contact_no) ?></p>
            <p><strong>Additional Contact No.:</strong> <?= htmlspecialchars($userData->additional_tp_no) ?></p>
            <p><strong>Removed By:</strong> <?= htmlspecialchars($deletedData->removed_by) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($userData->status) ?></p>
            <p><strong>Reason:</strong> <?= htmlspecialchars($deletedData->reason) ?></p>

            <div class="meeting-options">
              <p><strong>User Meeting Types</p>
              <?php
              $userMeetingTypes = isset($userData->meetingTypes) ? array_map('strtoupper', $userData->meetingTypes) : [];
              $meetingTypes = ['RHD', 'IOD', 'SYN', 'BOM'];

              foreach ($meetingTypes as $type) {
                $checked = in_array($type, $userMeetingTypes) ? 'checked' : '';
                  $class = strtolower($type) . '-option';
                  echo "<div class='meeting-option $class'>
                    <label for='meeting$type'>
                        <input type='checkbox' id='meeting$type' name='meetingType[]' value='$type' $checked disabled>
                        $type
                        </label>
                      </div>";
                }
              ?>

<p><strong>Secretary Meeting Types:</strong></p>
<?php
// Extract meeting type strings from objects
$secMeetings = isset($userData->secMeetings) 
    ? array_map(fn($row) => strtoupper($row->meeting_type), $userData->secMeetings) 
    : [];

$allMeetingTypes = ['RHD', 'IOD', 'SYN', 'BOM']; // All available types for display

foreach ($allMeetingTypes as $type) {
    $checked = in_array($type, $secMeetings) ? 'checked' : '';
    $class = strtolower($type) . '-option';
    echo "<div class='meeting-option $class'>
        <label for='sec_meeting$type'>
            <input type='checkbox' id='sec_meeting$type' name='secMeetingType[]' value='$type' $checked disabled>
            $type
          </label>
          </div>";
}
?>
           </div>
          </div> <!-- .profile-info -->
        </div> <!-- .profile-header -->
      </div> <!-- .content -->

      <div class="add-button-wrapper">
        <button class="add-btn" onclick="editModal()">Add</button>
      </div>

      <div id="editModal" class="modal">
        <div class="modal-content">
          <span class="close" id="closeModal">&times;</span>

          <?php if($userData): ?>
            <h2>Edit Member Details</h2>
            <form action="<?= ROOT ?>/admin/reactivateMember" method="POST">
              <input type="hidden" name="username" value="<?= htmlspecialchars($userData->username) ?>">

              <label>Full Name:</label>
              <input type="text" name="full_name" value="<?= htmlspecialchars($userData->full_name) ?>" required>

              <label>Email:</label>
              <input type="email" name="email" value="<?= htmlspecialchars($userData->email) ?>" required>

              <label>NIC:</label>
              <input type="text" name="nic" value="<?= htmlspecialchars($userData->nic) ?>" required>

              <label>Contact No.:</label>
              <input type="text" name="contact_no" value="<?= htmlspecialchars($userData->contact_no) ?>">

              <label>Additional Contact No.:</label>
              <input type="text" name="additional_tp_no" value="<?= htmlspecialchars($userData->additional_tp_no) ?>"> 

              <label>Select Role(s):</label>
              <div class="role-option">
                <?php
                $allRoles = ['secretary', 'lecturer', 'student Representative'];
                $existingRoles = $userData->role;

                foreach($allRoles as $role){
                  $checked = in_array($role, $existingRoles) ? 'checked' : '';
                  echo "<label><input type='checkbox' class='role-checkbox' name='roles[]' value='$role' $checked> " . ucfirst($role) . "</label>";
                }
                ?>
              </div>

              <!-- Secretary Meeting Types -->
              <div id="secretaryMeetingTypesContainer" style="display: none;">
                <label><strong>Select Secretary Meeting Type:</strong></label>
                <div class="meeting-options">
                  <?php foreach (['RHD', 'IOD', 'SYN', 'BOM'] as $type): ?>
                    <div class="meeting-option <?= strtolower($type) ?>-option">
                    <label for="secretary<?= $type ?>">
                      <input type="checkbox"
                            id="secretary<?= $type ?>"
                            name="secretaryMeetingType[]"
                            value="<?= $type ?>"
                            <?= in_array($type, $secMeetings ?? []) ? 'checked' : '' ?>>
                      <?= $type ?>
                    </label>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>

              <!-- Lecturer Meeting Types -->
              <div id="lecturerMeetingTypesContainer" style="display: none;">
                <label><strong>Select Lecturer Meeting Type:</strong></label>
                <div class="meeting-options">
                  <?php foreach (['RHD', 'IOD', 'SYN', 'BOM'] as $type): ?>
                    <div class="meeting-option <?= strtolower($type) ?>-option">
                    <label for="lecturer<?= $type ?>">
                      <input type="checkbox"
                            id="lecturer<?= $type ?>"
                            name="lecturerMeetingType[]"
                            value="<?= $type ?>"
                            <?= in_array($type, $userData->meetingTypes ?? []) ? 'checked' : '' ?>>
                      <?= $type ?>
                    </label>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>

              <!-- General Meeting Types (if needed) -->
              <div id="meetingTypeContainer" style="display: none;">
                <label><strong>Select Meeting Type(s):</strong></label>
                <div class="meeting-options">
                  <?php foreach (['RHD', 'IOD', 'SYN', 'BOM'] as $type): ?>
                    <div class="meeting-option <?= strtolower($type) ?>-option">
                    <label for="meeting<?= $type ?>">
                      <input type="checkbox"
                            id="meeting<?= $type ?>"
                            name="meetingType[]"
                            value="<?= $type ?>"
                            <?= in_array($type, $userData->meetingTypes ?? []) ? 'checked' : '' ?>>
                      <?= $type ?>
                    </label>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>

              <label for="status"><strong>Status:</strong></label>
              <select id="status" name="status" required>
                <option value="active" <?= $userData->status === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="removed" <?= $userData->status === 'removed' ? 'selected' : '' ?>>Removed</option>
              </select>

              <input type="hidden" name="reactivate" value="1">
              <div class="action-buttons">
                <button type="submit" class="btn-submit">Save Changes</button>
              </div>
            </form>
          <?php else: ?>
            <p>Member not found.</p>
          <?php endif; ?>
        </div> <!-- .modal-content -->
      </div> <!-- #editModal -->
    </div> <!-- .profile-details -->
  <?php else: ?>
    <p>Member not found.</p>
  <?php endif; ?>
</div> <!-- .profile-container -->

</body>
</html>

<script>
  const modal = document.getElementById("editModal");
  const closeBtn = document.getElementById("closeModal");

  function editModal() {
    modal.style.display = "block";
    handleRoleSections();
  }

  closeBtn.onclick = () => modal.style.display = "none";
  window.onclick = (e) => { if (e.target == modal) modal.style.display = "none"; };

  function handleRoleSections() {
    const roles = Array.from(document.querySelectorAll('.role-checkbox:checked')).map(cb => cb.value.toLowerCase());

    document.getElementById("secretaryMeetingTypesContainer").style.display = roles.includes('secretary') ? 'block' : 'none';
    document.getElementById("lecturerMeetingTypesContainer").style.display = roles.includes('lecturer') ? 'block' : 'none';
    document.getElementById("meetingTypeContainer").style.display = roles.includes('student representative') ? 'block' : 'none';
  }

  document.querySelectorAll('.role-checkbox').forEach(cb => {
    cb.addEventListener('change', handleRoleSections);
  });

  document.addEventListener("DOMContentLoaded", function () {
    const roles = <?= json_encode($userData->role ?? []) ?>;
    if (roles.includes("secretary")) {
      document.getElementById("secretaryMeetingTypesContainer").style.display = "block";
    }
  });


  document.addEventListener("DOMContentLoaded", handleRoleSections);
</script>
