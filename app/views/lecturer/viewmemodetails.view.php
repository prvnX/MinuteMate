<!DOCTYPE html>
<html lang = "en">

<head>
    <meta charset= "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>View memo Details</title>
     <link rel="stylesheet" href="<?=ROOT?>/assets/css/secretary/viewmemodetails.style.css">
     <link rel="icon" href="<?=ROOT?>/img.png" type="image">

</head>

<body>

  
    <?php
        $user="secretary";
        $memocart="memocart";   //use memocart-dot if there is a memo in the cart change with db
        $notification="notification"; //use notification-dot if there's a notification
        $menuItems = [ "home" => ROOT."/secretary",$memocart => ROOT."/secretary/memocart", $notification => ROOT."/secretary/notifications", "profile" => ROOT."/secretary/viewprofile"]; //pass the menu items here (key is the name of the page, value is the url)
        
        echo "<div class='memo-list-navbar'>";
        require_once("../app/views/components/new_navbar.php");
        echo "</div>";
        require_once("../app/views/components/sec_sidebar.php");

    ?>

    <header class="page-header">
         <h1>Memo Details </h1>
    </header>

    <div class="memo-details-container">
    <?php
    if (!empty($memo)) : ?>
        
        <p class="memo-detail"><strong>ID:</strong> <span class="memo-id"><?= htmlspecialchars($memo->memo_id) ?></span></p>
        <p class="memo-detail"><strong>Title:</strong> <span class="memo-title"><?= htmlspecialchars($memo->memo_title) ?></span></p>
        <p class="memo-detail"><strong>Status:</strong> <span class="memo-status"><?= htmlspecialchars($memo->status) ?></span></p>
        <p class="memo-detail"><strong>Submitted By:</strong> <span class="memo-submitted-by"><?= htmlspecialchars($memo->submitted_by) ?></span></p>
        
        <p class="memo-detail"><strong>Content:</strong></p>
        <div class="memo-content-box">
            <?php
            $memoContent=$memo->memo_content;
            echo html_entity_decode($memoContent);
            ?>
        </div>

       
        <a href="<?= ROOT ?>/lecturer/viewsubmittedmemos" class="btn-back">Back to Memos</a>
    <?php else : ?>
        <p class="memo-error">Memo not found.</p>
    <?php endif; ?>
</div>

</body>