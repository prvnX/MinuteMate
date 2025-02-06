<!DOCTYPE html>
<html lang = "en">

<head>
    
    <meta charset= "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Memocart</title>
     <link rel="stylesheet" href="<?=ROOT?>/assets/css/secretary/memocart.style.css">
     <style>
        .memolist{
            margin: auto;
        }
     </style>
  
</head>

<body>
<div class="navbar">
<?php
    $user="secretary";
    $memocart="memocart";   //use memocart-dot if there is a memo in the cart change with db
    $notification="notification"; //use notification-dot if there's a notification
    $menuItems = [ "home" => ROOT."/secretary",$memocart => ROOT."/secretary/memocart", $notification => ROOT."/secretary/notifications", "profile" => ROOT."/secretary/viewprofile"]; //pass the menu items here (key is the name of the page, value is the url)
    require_once("../app/views/components/navbar.php"); //call the navbar component
   ?>

   <div>
        <h1 class="heading">MemoCart</h1>
   </div>

   <div class="memolist">
    <?php foreach ($memos as $memo): ?>
        <div class="memoitem">
            <div class="memocontent">
            <h3><?= htmlspecialchars($memo->memo_title) ?></h3>
            <p><?= htmlspecialchars($memo->memo_id) ?></p>
         </div>
            <a href="<?=ROOT?>/secretary/acceptmemo/?memo_id=<?= $memo->memo_id ?>">
                <button class="viewbtn">View</button>
            </a>
        </div>
    <?php endforeach; ?>
 </div>
</body>