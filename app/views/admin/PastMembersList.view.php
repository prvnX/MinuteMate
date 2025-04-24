<?php
  $user = "admin";
  $notification = "notification"; //use notification-dot if there's a notification
  $menuItems = [
      "home" => ROOT."/admin",
      $notification => ROOT."/admin/notifications",
      "profile" => ROOT."/admin/viewprofile"
  ];
  require_once("../app/views/components/new_navbar.php");
  include '../app/views/components/admin_sidebar.php'; 


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
  <?php if (!empty($removedMembers)): ?>
    <ul class="members-list">
      <?php foreach ($removedMembers as $member): ?>
        <li class="member-item">
          <span class="member-name">
            <?= htmlspecialchars($member->full_name) ?>
          </span>
          <a class="view-btn" href="<?=ROOT?>/admin/PastMemberProfile?id=<?= htmlspecialchars($member->username) ?>">
            View
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
