<?php
include '../app/views/admin/adminsidebar.view.php';

// Retrieve the meeting type from the URL
$meetingType = $_GET['meetingType'] ?? 'Unknown Meeting Type';

// Dummy data for members based on the meeting type
$dummyMembers = [
    'RHD' => [
        ['id' => 1, 'name' => 'John Doe'],
        ['id' => 2, 'name' => 'Alice Brown']
    ],
    'IOD' => [
        ['id' => 3, 'name' => 'Robert Smith'],
        ['id' => 4, 'name' => 'Diana Prince']
    ],
    'SYN' => [
        ['id' => 5, 'name' => 'Clark Kent'],
        ['id' => 6, 'name' => 'Bruce Wayne']
    ],
    'BOM' => [
        ['id' => 7, 'name' => 'Selina Kyle'],
        ['id' => 8, 'name' => 'Lois Lane']
    ]
];

// Get the members for the selected meeting type or an empty array if not found
$members = $dummyMembers[$meetingType] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Members in <?= htmlspecialchars($meetingType) ?></title>
  <link rel="stylesheet" href="<?=ROOT?>/assets/css/admin/viewMembersList.style.css">
</head>
<body>

<div class="members-list-container">
  <h2 class="title">Members in <?= htmlspecialchars($meetingType) ?></h2>
  <?php if (!empty($members)): ?>
    <ul class="members-list">
      <?php foreach ($members as $member): ?>
        <li class="member-item">
          <a href="<?=ROOT?>/admin/viewMemberProfile?id=<?= htmlspecialchars($member['id']) ?>">
            <?= htmlspecialchars($member['name']) ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <p>No members found for this meeting type.</p>
  <?php endif; ?>
</div>

</body>
</html>
