<!DOCTYPE html>
<html lang = "en">

<head>
    
    <meta charset= "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Memocart</title>
     <link rel="stylesheet" href="<?=ROOT?>/assets/css/secretary/memocart.style.css">
     <link rel="icon" href="<?=ROOT?>/img.png" type="image">

     <style>
        .memolist{
            margin: auto;
        }
     </style>
  
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
        $memoList=$data['memos'];
   ?>


             <header class="page-header">
                <h1>Memo Cart </h1>
                <p class="subtitle">View memos to be accepted</p>
            </header>


    <div class="main-container">
        <div class="content-area">
            <div class="memolist" id="memolist">
                <?php foreach ($memoList as $memoitem): ?>
                    <div class="memoitem" >
                        <div class="memocontent">
                        <h3><?= htmlspecialchars($memoitem->memo_title) ?></h3>
                       
                        <p>Memo ID: <?= htmlspecialchars($memoitem->memo_id) ?></p>
                    </div>
                        <a href="<?=ROOT?>/secretary/acceptmemo/?memo_id=<?= $memoitem->memo_id ?>">
                            <button class="viewbtn">View</button>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
    </div>
</body>