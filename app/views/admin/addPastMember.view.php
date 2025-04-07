<?php
// Include sidebar and other layout components
include '../app/views/components/admin_sidebar.php';

// Dummy data for past members (use a database in production)
$dummyMembers = [
    1 => ['name' => 'Rasha Doe', 'email' => 'rashadoe@example.com', 'lecturer_id' => 'L10001', 'nic' => '987654321V', 'role' => 'Lecturer', 'phone' => '0772345678', 'meetingTypes' => ['RHD'], 'status' => 'removed'],
    2 => ['name' => 'Sanda Brown', 'email' => 'sandabrown@example.com', 'lecturer_id' => 'L10002', 'nic' => '123654789V', 'role' => 'Lecturer', 'phone' => '0773456789', 'meetingTypes' => ['RHD'], 'status' => 'removed'],
    3 => ['name' => 'AC Smith', 'email' => 'acsmith@example.com', 'lecturer_id' => 'L10003', 'nic' => '135792468V', 'role' => 'Lecturer', 'phone' => '0774567890', 'meetingTypes' => ['IOD'], 'status' => 'active'],
    // Add other members as necessary
];

// Retrieve member ID from the URL
$memberId = $_GET['id'] ?? null;

// Fetch the member's details
$member = $dummyMembers[$memberId] ?? null;

// Initialize success message
$successMessage = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve updated member data from the form
    $updatedName = $_POST['name'] ?? '';
    $updatedEmail = $_POST['email'] ?? '';
    $updatedPhone = $_POST['phone'] ?? '';
    $updatedLecturerId = $_POST['lecturer_id'] ?? '';
    $updatedNic = $_POST['nic'] ?? '';
    $updatedRole = $_POST['role'] ?? '';
    $updatedMeetingTypes = $_POST['meetingTypes'] ?? [];
    $updatedStatus = $_POST['status'] ?? 'removed';

    // Update the member's details
    $updatedMember = [
        'name' => $updatedName,
        'email' => $updatedEmail,
        'lecturer_id' => $updatedLecturerId,
        'nic' => $updatedNic,
        'role' => $updatedRole,
        'phone' => $updatedPhone,
        'meetingTypes' => $updatedMeetingTypes,
        'status' => $updatedStatus,
    ];

    // Simulate saving to the database
    $dummyMembers[$memberId] = $updatedMember;

    // Set success message
    $successMessage = "The member has been successfully updated in the system!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Past Member</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/admin/addPastMember.style.css">
</head>
<body>

<div class="profile-container">
    <!-- Success message section -->
    <?php if ($successMessage): ?>
        <div class="success-message">
            <?= htmlspecialchars($successMessage) ?>
        </div>
    <?php endif; ?>

    <?php if ($member): ?>
        <h2 class="profile-title">Edit Past Member </h2>
        <form action="" method="POST" class="edit-member-form">
            <div class="profile-header">
                <img src="<?= ROOT ?>/assets/images/user.png" alt="Profile Image" class="profile-img">
                <div class="profile-info">
                    <label for="name"><strong>Name:</strong></label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($member['name']) ?>" required>

                    <label for="email"><strong>Email:</strong></label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($member['email']) ?>" required>

                    <label for="phone"><strong>Phone:</strong></label>
                    <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($member['phone']) ?>" required>

                    <label for="lecturer_id"><strong>Lecturer ID:</strong></label>
                    <input type="text" id="lecturer_id" name="lecturer_id" value="<?= htmlspecialchars($member['lecturer_id']) ?>" required>

                    <label for="nic"><strong>NIC:</strong></label>
                    <input type="text" id="nic" name="nic" value="<?= htmlspecialchars($member['nic']) ?>" required>

                    <label for="role"><strong>Role:</strong></label>
                    <input type="text" id="role" name="role" value="<?= htmlspecialchars($member['role']) ?>" required>

                    <label for="meetingTypes"><strong>Meeting Types:</strong></label>
                    <select id="meetingTypes" name="meetingTypes[]" multiple required>
                        <option value="RHD" <?= in_array('RHD', $member['meetingTypes']) ? 'selected' : '' ?>>RHD</option>
                        <option value="IOD" <?= in_array('IOD', $member['meetingTypes']) ? 'selected' : '' ?>>IOD</option>
                        <option value="SYN" <?= in_array('SYN', $member['meetingTypes']) ? 'selected' : '' ?>>SYN</option>
                        <option value="BOM" <?= in_array('BOM', $member['meetingTypes']) ? 'selected' : '' ?>>BOM</option>
                    </select>

                    <label for="status"><strong>Status:</strong></label>
                    <select id="status" name="status" required>
                        <option value="active" <?= $member['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="removed" <?= $member['status'] === 'removed' ? 'selected' : '' ?>>Removed</option>
                    </select>
                </div>
            </div>

            <div class="action-buttons">
                <button type="submit" class="btn-submit">Save Changes</button>
            </div>
        </form>
    <?php else: ?>
        <p>Member not found.</p>
    <?php endif; ?>
</div>

</body>
</html>
