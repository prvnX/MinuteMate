<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/admin/adminsidebar.style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="navbar">
        <?php
            $user = "admin";
            $notification = "notification";
            $menuItems = [
                "home" => ROOT."/admin",
                $notification => ROOT."/admin/notifications",
                "profile" => ROOT."/admin/viewprofile"
            ];
            require_once("../app/views/components/navbar.php");
        ?>
    </div>
    <?php
        // Get the current full URL
        $currentURL = $_SERVER['REQUEST_URI'];

        // Function to check if a link is active
        function isActive($page) {
            $currentPage = basename($_SERVER['PHP_SELF']);
            return $currentPage === $page ? 'active' : '';
        }
    ?>
    <div class="sidebar">
        <a href="<?=ROOT?>/admin/viewpendingRequests" class="<?= isActive('viewpendingRequests.view.php') ?>">
            <i class="fas fa-user-clock"></i> Pending Member Request
        </a>
        <a href="<?=ROOT?>/admin/viewMembers" class="<?= isActive('viewMembers.view.php') ?>">
            <i class="fas fa-users"></i> Members
        </a>
        <a href="<?=ROOT?>/admin/PastMembers" class="<?= isActive('PastMembers.view.php') ?>">
            <i class="fas fa-user-minus"></i> Past Members
        </a>
    </div>
</body>
</html>
