<!DOCTYPE html>
<html lang = "en">

<head>
    <meta charset= "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?=ROOT?>/img.png" type="image">
     <title>View memo Details</title>
     <link rel="stylesheet" href="<?=ROOT?>/assets/css/studentrep/viewmemodetails.style.css">
</head>

<body>

     
<?php
    $user="studentrep";
    $memocart="memocart";   //use memocart-dot if there is a memo in the cart change with db
    $notification="notification"; //use notification-dot if there's a notification
    $menuItems = [ "home" => ROOT."/studentrep" , $notification => ROOT."/studentrep/notifications", "profile" => ROOT."/studentrep/viewprofile"  ]; //pass the menu items here (key is the name of the page, value is the url)
    require_once("../app/views/components/new_navbar.php"); //call the navbar component
    require_once("../app/views/components/std_sidebar.php"); //call the sidebar component
    ?>
    
    <div><h1 class="heading">Memo Details</h1></div>

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


        <a href="<?= ROOT ?>/studentrep/viewmemos" class="btn-back">Back to Memos</a>
    <?php else : ?>
        <p class="memo-error">Memo not found.</p>
    <?php endif; ?>
</div>

</body>