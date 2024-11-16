<?php include '../app/views/admin/adminsidebar.view.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Members</title>
  <link rel="stylesheet" href="<?=ROOT?>/assets/css/admin/viewMembers.style.css">
</head>
<body>

<div class="members-container">
  <h2 class="title">Members</h2>
  <div class="meeting-list">
  <a href="<?=ROOT?>/admin/viewMembersByMeetingType?meetingType=RHD" class="meeting-item">
    <span class="dot red"></span> RHD
</a>
<a href="<?=ROOT?>/admin/viewMembersByMeetingType?meetingType=IOD" class="meeting-item">
    <span class="dot yellow"></span> IOD
</a>
<a href="<?=ROOT?>/admin/viewMembersByMeetingType?meetingType=SYN" class="meeting-item">
    <span class="dot green"></span> SYN
</a>
<a href="<?=ROOT?>/admin/viewMembersByMeetingType?meetingType=BOM" class="meeting-item">
    <span class="dot blue"></span> BOM
</a>

  </div>
</div>

</body>
</html>
