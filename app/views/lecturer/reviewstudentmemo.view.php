<!DOCTYPE html>
<html lang = "en">

<head>
    
    <meta charset= "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?=ROOT?>/img.png" type="image">
     <title>Memocart</title>
     <link rel="stylesheet" href="<?=ROOT?>/assets/css/secretary/memocart.style.css">
     
  
</head>

<body>

        <?php
            $user= 'lecturer';
             $notification="notification"; //use notification-dot if there's a notification
             $menuItems = [ "home" => ROOT."/lecturer", $notification => ROOT."/lecturer/notifications", "profile" => ROOT."/lecturer/viewprofile"]; //pass the menu items here (key is the name of the page, value is the url)
             require_once("../app/views/components/new_navbar.php"); //call the navbar component
             require_once("../app/views/components/lec_sidebar.php"); //call the sidebar component
            
        ?>

    <header class="page-header">
        <h1>Review student  memos </h1>
       
    </header>

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
     ?>

    <div class="main-container">
            <div class="content-area">
                <div class="memolist" id="memolist">
                    <?php foreach ($memos as $memoitem): ?>
                        <div class="memoitem" >
                            <div class="memocontent">
                            <h3><?= htmlspecialchars($memoitem->memo_title) ?></h3>
                        
                            <p>Memo ID: <?= htmlspecialchars($memoitem->memo_id) ?></p>
                        </div>
                        <a href="<?=ROOT?>/lecturer/acceptmemo/?memo_id=<?= $memoitem->memo_id ?>">
                            <button class="viewbtn">Review</button>
                        </a>
                        </div>
                    <?php endforeach; ?>
                </div>
        </div>

</body>