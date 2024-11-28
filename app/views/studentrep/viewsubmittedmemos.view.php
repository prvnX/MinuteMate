<!DOCTYPE html>
<html lang = "en">

<head>
    <meta charset= "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>View memo</title>
     <link rel="stylesheet" href="<?=ROOT?>/assets/css/lecturer/viewsubmittedmemo.style.css">
</head>

<body>
    <div class= "navbar">
        <?php
             $notification="notification"; //use notification-dot if there's a notification
             $menuItems = [ "home" => ROOT."/lecturer", $notification => ROOT."/lecturer/notifications", "profile" => ROOT."/lecturer/viewprofile"]; //pass the menu items here (key is the name of the page, value is the url)
             require_once("../app/views/components/navbar.php"); //call the navbar component
        ?>

    <h1 class="heading">Submitted Memos</h1>
    </div>


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