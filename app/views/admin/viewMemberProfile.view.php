<?php 
  $user = "admin";
  $notification = "notification"; //use notification-dot if there's a notification
  $menuItems = [
      "home" => ROOT."/admin",
      $notification => ROOT."/admin/notifications",
      "profile" => ROOT."/admin/viewprofile"
  ];
  require_once("../app/views/components/new_navbar.php");
  require_once("../app/views/components/sec_sidebar.php");

// Fetch the user data passed from the controller
$userData = $data['userData'] ?? null;
$userId = $userData->id ?? null;
$currentPage = 'viewMembers'; // For navbar highlighting
?>

<div class="content">
    <div class="profile-header">
        <img src="<?= ROOT ?>/assets/images/user.png" alt="Profile Image" class="profile-img">
        <h3><?= htmlspecialchars($userData->full_name); ?></h3>
    </div>
    <p>Email: <?= htmlspecialchars($userData->email); ?></p>
    <p>Username: <?= htmlspecialchars($userData->username); ?></p>
    <p>NIC: <?= htmlspecialchars($userData->nic); ?></p>
    <p>Role: <?= implode(', ', array_map('htmlspecialchars' ,$userData->role)); ?></p>
    <p>Contact No.: <?= htmlspecialchars($userData->contact_no ?? 'N/A'); ?></p>
    <p>Additional Contact No.: <?= htmlspecialchars($userData->additional_tp_no ?? 'N/A'); ?></p>

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

    <div class="action-buttons">
    <a href="<?= ROOT ?>/admin/editMemberProfile?id=<?= urlencode($userData->username) ?>" class="btn-edit">Edit</a>
    <div class="remove-button-wrapper">
    <button class="remove-btn" onclick="openRemoveModal()">Remove</button>
</div>

<div id="removeModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3 id="modalTitle">Remove Member</h3>
        <form action="<?= ROOT ?>/admin/removeMember" method="post">
            <!-- Editable Inputs for Username and Full Name -->
            <div>
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" value="<?= htmlspecialchars($userData->username); ?>" required>
            </div>
            <div>
                <label for="full_name">Full Name:</label>
                <input type="text" name="full_name" id="full_name" value="<?= htmlspecialchars($userData->full_name); ?>" required>
            </div>

            <!-- Reason for Removal -->
            <div>
                <label for="reason">Reason for Removal:</label>
                <input type="text" name="reason" id="reason" required>
            </div>

            <!-- Hidden input for removed_by populated from session -->
            <input type="hidden" name="removed_by" value="<?=  $_SESSION['userdetails']->username ?? ''; ?>" id="removed_by">

            <div class="modal-buttons">
                <button type="submit" class="btn-remove-confirm">Confirm Remove</button>
                <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<link rel="stylesheet" href="<?= ROOT ?>/assets/css/admin/viewMemberProfile.style.css">

<script>

function openRemoveModal() {
    document.getElementById("modalTitle").innerText = "Remove Member";
    document.getElementById("removeModal").style.display = "block";
}

function closeModal() {
    document.getElementById("removeModal").style.display = "none";
}

window.onclick = function(event) {
    if (event.target === document.getElementById("removeModal")) {
        closeModal();
    }
}


    function handleMemberAction(action) {
        if (action === 'remove' && !confirm('Are you sure you want to remove this member?')) return;

        const selectedMeetingTypes = Array.from(document.querySelectorAll('input[name="meetingType[]"]:checked')).map(input => input.value);

        fetch(`<?= ROOT ?>/admin/handleMemberAction`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                id: <?= json_encode($userId) ?>,
                action: action,
                meetingTypes: selectedMeetingTypes
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(action === 'edit' ? 'Member updated successfully!' : 'Member removed.');
                window.location.href = '<?= ROOT ?>/admin/viewMembers';
            } else {
                alert('An error occurred. Please try again.');
            }
        });
    }
</script>
