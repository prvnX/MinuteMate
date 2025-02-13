<!DOCTYPE html>
<html lang = "en">

<head>
    <meta charset= "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?=ROOT?>/img.png" type="image">
     <title>View memo</title>
     <link rel="stylesheet" href="<?=ROOT?>/assets/css/studentrep/viewsubmittedmemo.style.css">
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

        
    <div><h1 class="heading">Submitted Memos</h1></div>

<div class="memolist">
    <?php foreach ($memos as $memo): ?>
        <div class="memoitem">
            <div class="memocontent">
                <h3><?= htmlspecialchars($memo->memo_title) ?></h3>
                <p><?= htmlspecialchars($memo->memo_id) ?></p>
            </div>
             <a href="<?=ROOT?>/studentrep/viewmemodetails/?memo_id=<?= $memo->memo_id ?>">
                 <button class="viewbtn">View</button>
             </a>
        </div>
    <?php endforeach; ?>
 </div>

</body> 

 </html>