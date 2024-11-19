<!-- adminsidebar.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/admin/adminsidebar.style.css">
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
        <a href="<?=ROOT?>/admin/viewpendingRequests">View Pending Member Request</a>
        <a href="<?=ROOT?>/admin/viewMembers">View Members</a>
        <a href="<?=ROOT?>/admin/PastMembers">Remove Members</a>
    </div>
   
</body>
</html>