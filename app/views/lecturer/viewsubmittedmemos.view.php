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

    <?php
$memos = [
    "M001" => "Memo Title 1",
    "M002" => "Memo Title 2",
    "M003" => "Memo Title 3",
    "M004" => "Memo Title 4",
    "M005" => "Memo Title 5",
    "M006" => "Memo Title 6",
    "M007" => "Memo Title 7",
    "M008" => "Memo Title 8",
    "M009" => "Memo Title 9",
    "M0010" => "Memo Title 10"
];
?>

<div class="memolist">
    <?php foreach ($memos as $id=>$title): ?>
        <div class="memoitem">
            <div class="memocontent">
                <h3><?= $title ?></h3>
                <p><?= $id ?></p>
    </div>
    <a href="<?=ROOT?>/lecturer/viewsubmittedmemos/<?= $id ?>">
        <button class="viewbtn">View</button>
    </a>
        </div>
    <?php endforeach; ?>
 </div>

</body> 