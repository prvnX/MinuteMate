<?php
include '../app/views/admin/adminsidebar.view.php';

// Dummy data for past members
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
  <?php if ($member): ?>
    <h2 class="profile-title">Profile Details</h2>
    <div class="profile-details">
      <p><strong>Name:</strong> <?= htmlspecialchars($member['name']) ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($member['email']) ?></p>
      <p><strong>Lecturer ID:</strong> <?= htmlspecialchars($member['lecturer_id']) ?></p>
      <p><strong>NIC:</strong> <?= htmlspecialchars($member['nic']) ?></p>
      <p><strong>Role:</strong> <?= htmlspecialchars($member['role']) ?></p>
      <p><strong>Phone:</strong> <?= htmlspecialchars($member['phone']) ?></p>
      <p><strong>Meeting Types:</strong> <?= htmlspecialchars(implode(', ', $member['meetingTypes'])) ?></p>
      <p><strong>Status:</strong> <?= htmlspecialchars($member['status']) ?></p>
    </div>
    <div class="action-buttons">
      <a href="<?=ROOT?>/admin/addPastMember?id=<?= htmlspecialchars($memberId) ?>" class="btn-add">Add</a>
      <a href="<?=ROOT?>/admin/deletePastMember?id=<?= htmlspecialchars($memberId) ?>" class="btn-delete">Delete</a>
    </div>
  <?php else: ?>
    <p>Member not found.</p>
  <?php endif; ?>
</div>

</body>
</html>
