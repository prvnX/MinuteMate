<?php 
include '../app/views/admin/adminsidebar.view.php'; 

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
    <p>Role: <?= htmlspecialchars($userData->role); ?></p>
    <p>Contact No.: <?= htmlspecialchars($userData->contact_no ?? 'N/A'); ?></p>

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
        <a href="<?= ROOT ?>/admin/editMemberProfile?id=<?= urlencode($username) ?>" class="btn-edit">Edit</a>
        <button class="btn-remove" onclick="handleMemberAction('remove')">Remove</button>
    </div>
</div>

<link rel="stylesheet" href="<?= ROOT ?>/assets/css/admin/viewMemberProfile.style.css">

<script>
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
