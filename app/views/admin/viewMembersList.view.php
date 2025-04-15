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
          <span class="member-name">
            <?= htmlspecialchars($member->username) ?> <!-- Use -> instead of [] -->
          </span>
          <a class="view-btn" href="<?=ROOT?>/admin/viewMemberProfile?id=<?= htmlspecialchars($member->username) ?>">
            View
          </a>
          <a class="edit-btn" href="<?=ROOT?>/admin/editMemberProfile?id=<?= htmlspecialchars($member->username) ?>">
            Edit
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
