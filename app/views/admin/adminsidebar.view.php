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
    <div class="sidebar">
        <a href="<?=ROOT?>/admin/viewpendingRequests">
            <i class="fas fa-user-clock"></i>  Pending Member Request
        </a>
        <a href="<?=ROOT?>/admin/viewMembers">
            <i class="fas fa-users"></i>  Members
        </a>
        <a href="<?=ROOT?>/admin/PastMembers">
            <i class="fas fa-user-minus"></i> Remove Members
        </a>
    </div>
</body>
</html>
