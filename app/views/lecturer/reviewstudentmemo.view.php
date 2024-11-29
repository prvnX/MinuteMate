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
        .heading{
            text-align: center;
            font-family: ubuntu;
            font-weight: 500;
            margin: 40px auto ;
        }

     </style>
  
</head>

<body>
<div class="navbar">
<?php
    $user="lecturer";
    $notification="notification"; //use notification-dot if there's a notification
    $menuItems = [ "home" => ROOT."/lecturer", $notification => ROOT."/lecturer/notifications", "profile" => ROOT."/lecturer/viewprofile"]; //pass the menu items here (key is the name of the page, value is the url)
    require_once("../app/views/components/navbar.php"); //call the navbar component
   ?>

        <h1 class="heading">Review Student Memos</h1>

   <div class="memolist">
    <?php
    $memos=[
        (object)["memo_id"=>1, "memo_title"=>"Memo 1"],
        (object)["memo_id"=>2, "memo_title"=>"Memo 2"],
        (object)["memo_id"=>3, "memo_title"=>"Memo 3"],
        (object)["memo_id"=>4, "memo_title"=>"Memo 4"],
        (object)["memo_id"=>5, "memo_title"=>"Memo 5"],
        (object)["memo_id"=>6, "memo_title"=>"Memo 6"],
        (object)["memo_id"=>7, "memo_title"=>"Memo 7"],
        (object)["memo_id"=>8, "memo_title"=>"Memo 8"],
    ];


    foreach ($memos as $memo): ?>
        <div class="memoitem">
            <div class="memocontent">
            <h3><?= htmlspecialchars($memo->memo_title) ?></h3>
            <p><?= htmlspecialchars($memo->memo_id) ?></p>
         </div>
            <a href="<?=ROOT?>/lecturer/acceptmemo/?memo_id=<?= $memo->memo_id ?>">
                <button class="viewbtn">Review</button>
            </a>
        </div>
    <?php endforeach; ?>
 </div>
</body>