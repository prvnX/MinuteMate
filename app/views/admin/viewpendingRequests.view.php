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
?>


<div class="content">
    <h2>Pending Requests</h2>
    <ul class="pending-request-list">
        <?php if (!empty($pendingRequests)): ?>
            <?php foreach ($pendingRequests as $request): ?>
                <li>
                    <a href="<?= ROOT ?>/admin/viewRequestDetails?id=<?php echo $request->id; ?>">
                        <?php echo htmlspecialchars($request->full_name); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>No pending requests found.</li>
        <?php endif; ?>
    </ul>
</div>

<!-- Include the CSS file here -->
<link rel="stylesheet" href="<?= ROOT ?>/assets/css/admin/viewpendingRequests.style.css">
