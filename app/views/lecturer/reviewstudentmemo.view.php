<!DOCTYPE html>
<html lang = "en">

<head>
    
    <meta charset= "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?=ROOT?>/img.png" type="image">
     <title>Memocart</title>
     <link rel="stylesheet" href="<?=ROOT?>/assets/css/lecturer/reviewstudentmemos.style.css">
     
  
</head>

<body>

        <?php
            $user= 'lecturer';
             $notification="notification"; //use notification-dot if there's a notification
             $menuItems = [ "home" => ROOT."/lecturer", $notification => ROOT."/lecturer/notifications", "profile" => ROOT."/lecturer/viewprofile"]; //pass the menu items here (key is the name of the page, value is the url)
             require_once("../app/views/components/new_navbar.php"); //call the navbar component
             require_once("../app/views/components/lec_sidebar.php"); //call the sidebar component
             $memoList=$data['memos']; 
            
        ?>

    <header class="page-header">
        <h1>Review student  memos </h1>
       
    </header>


    <div class="main-container">
            <div class="content-area">
                <div class="memolist" id="memolist">
                <?php if (!empty($memoList) && is_array($memoList)): ?>
    <?php foreach ($memoList as $memoitem): ?>
        <div class="memoitem">
            <div class="memocontent">
                <h3><?= htmlspecialchars($memoitem->memo_title) ?></h3>
                
                <div class="memo-meta">
                    <span class="memo-id">Memo ID: <?= htmlspecialchars($memoitem->memo_id) ?></span>
                </div>
                
            </div>

            <a href="<?=ROOT?>/lecturer/acceptmemo/?memo_id=<?= $memoitem->memo_id ?>">
                <button class="viewbtn">Review</button>
            </a>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <!-- No memos -->
    <div class="no-memos-message" style="text-align: center; margin-top: 40px;">
        <h3>No memos assigned to you yet.</h3>
        <p>Please check back later.</p>
    </div>
<?php endif; ?>

                </div>
        </div>

</body>