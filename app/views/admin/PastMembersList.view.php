<?php
include '../app/views/admin/adminsidebar.view.php';

// Retrieve the meeting type from the URL
$meetingType = $_GET['meetingType'] ?? 'Unknown Meeting Type';

// Dummy data for past members based on the meeting type
$dummyMembers = [
    'RHD' => [
        ['id' => 1, 'name' => 'Rasha Doe', 'status' => 'removed'],
        ['id' => 2, 'name' => 'Sanda Brown', 'status' => 'removed'],
    ],
    'IOD' => [
        ['id' => 3, 'name' => 'AC Smith', 'status' => 'active'], // Active member, will be excluded
        ['id' => 4, 'name' => 'Olof Prince', 'status' => 'removed'],
    ],
    'SYN' => [
        ['id' => 5, 'name' => 'THisu Kent', 'status' => 'removed'],
        ['id' => 6, 'name' => 'Bruce Wayne', 'status' => 'removed'],
    ],
    'BOM' => [
        ['id' => 7, 'name' => 'Selina Kyle', 'status' => 'removed'],
        ['id' => 8, 'name' => 'Lois Lane', 'status' => 'active'], // Active member, will be excluded
    ],
];

// Filter members for the selected meeting type and status = 'removed'
$members = array_filter($dummyMembers[$meetingType] ?? [], function ($member) {
    return $member['status'] === 'removed';
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Past Members in <?= htmlspecialchars($meetingType) ?></title>
  <link rel="stylesheet" href="<?=ROOT?>/assets/css/admin/pastMembersList.style.css">
</head>
<body>

<div class="members-list-container">
  <h2 class="title">Past Members in <?= htmlspecialchars($meetingType) ?></h2>
  <?php if (!empty($members)): ?>
    <ul class="members-list">
      <?php foreach ($members as $member): ?>
        <li class="member-item">
          <span class="member-name">
            <?= htmlspecialchars($member['name']) ?>
          </span>
          <a class="view-btn" href="<?=ROOT?>/admin/PastMemberProfile?id=<?= htmlspecialchars($member['id']) ?>">
            View
          </a>
          <a class="delete-btn" href="<?=ROOT?>/admin/deletePastMember?id=<?= htmlspecialchars($member['id']) ?>">
            Delete
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <p>No past members found for this meeting type.</p>
  <?php endif; ?>
</div>

</body>
</html>
