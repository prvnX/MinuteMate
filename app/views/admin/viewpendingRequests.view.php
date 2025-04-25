<?php 
$user="admin";
$notification="notification"; //use notification-dot if there's a notification
$menuItems = [ 
  "home" => ROOT."/admin", 
  $notification => ROOT."/admin/notifications", 
  "profile" => ROOT."/admin/viewprofile", 
  "logout" => ROOT."/admin/confirmlogout"
];
require_once("../app/views/components/admin_navbar.php");
include '../app/views/components/admin_sidebar.php'; 
?>

<div class="content">
    <h2>Pending Requests</h2>
    <div class="pending-request-container">
        <?php if (!empty($pendingRequests)): ?>
            <ul class="pending-request-list">
                <?php foreach ($pendingRequests as $request): ?>
                    <li>
                        <a href="<?= ROOT ?>/admin/viewRequestDetails?id=<?php echo $request->id; ?>" class="request-link">
                            <span class="request-name"><?php echo htmlspecialchars($request->full_name); ?></span>
                            <span class="view-details">View Details</span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div class="no-request-message">
                No pending requests found.
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Include the CSS file here -->
<link rel="stylesheet" href="<?= ROOT ?>/assets/css/admin/viewpendingRequests.style.css">
