<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Edit Requests</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/admin/vieweditrequests.css">
</head>

<body>
<?php
     
    $user="admin";
    $notification="notification"; //use notification-dot if there's a notification
    $menuItems = [ "home" => ROOT."/admin" , $notification => ROOT."/admin/notifications", "profile" => ROOT."/admin/viewprofile"  ]; //pass the menu items here (key is the name of the page, value is the url)
    require_once("../app/views/components/new_navbar.php"); //call the navbar component
    require_once("../app/views/components/admin_sidebar.php"); //call the sidebar component
    ?>
 

    <!-- Main Content -->
    <div class="main-content">
    <h1 class="heading">Edit Requests</h1>
    <div class="memolist reqlist-container">
        <?php if (!empty($requests)): ?>
            <?php foreach ($requests as $request): ?>
                <div class="memoitem">
                    <div class="memocontent">
                        <h1 class="req-heading">Edit request by <span><?= htmlspecialchars($request->full_name) ?></span></h1>
                        <p class="req-date"><?= htmlspecialchars($request->created_at) ?></p>
                    </div>
                    <a href="<?= ROOT ?>/admin/viewsinglerequest/?id=<?= $request->id ?>">
                        <button type="button" id="viewbutton" class="viewbtn">View</button>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-reqs">No edit requests available.</p>
        <?php endif; ?>
    </div>

    <div class="form-buttons">
        <button type="button" id="continueButton" class="continue-button">Continue</button>
    </div>
</div>

    <script>
        document.getElementById("continueButton").addEventListener("click", () => {
            window.location.href = "<?= ROOT ?>/admin"; // Redirect to the dashboard
        });
    </script>
</body>

</html>
