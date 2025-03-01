<!DOCTYPE html>
<html lang = "en">

<head>
    
    <meta charset= "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?=ROOT?>/img.png" type="image">
     <title>View memo</title>
     <link rel="stylesheet" href="<?=ROOT?>/assets/css/lecturer/viewminutes.style.css">
  
</head>

<body>
<div class="navbar">
<?php
    $user="lecturer";
    $memocart="memocart";   //use memocart-dot if there is a memo in the cart change with db
    $notification="notification"; //use notification-dot if there's a notification
    $menuItems = [ "home" => ROOT."/lecturer" , $notification => ROOT."/lecturer/notifications", "profile" => ROOT."/lecturer/viewprofile" , "logout" => ROOT."/lecturer/confirmlogout"]; //pass the menu items here (key is the name of the page, value is the url)
    require_once("../app/views/components/new_navbar.php"); //call the navbar component
    require_once("../app/views/components/lec_sidebar.php"); //call the sidebar component
    ?>
<?php
$minutes = [
    "M001" =>[ "title"=>"Minute Title 1", "type" => "rhd"],
    "M002" =>[ "title"=>"Minute Title 2", "type" => "iud"],
    "M003" =>[ "title"=>"Minute Title 3", "type" => "bom"],
    "M004" =>[ "title"=>"Minute Title 4", "type" => "rhd"],
    "M005" =>[ "title"=>"Minute Title 5", "type" => "syn"],
    "M006" =>[ "title"=>"Minute Title 6", "type" => "rhd"],
    "M007" =>[ "title"=>"Minute Title 7", "type" => "syn"],
    "M008" =>[ "title"=>"Minute Title 8", "type" => "rhd"],
    "M009" =>[ "title"=>"Minute Title 9", "type" => "iud"],
    "M0010"=>[ "title"=>"Minute Title 10", "type" => "bom"] 
];
?>

   <div class="meetinglist">
   <h1 class="heading">Minutes</h1>
   <button class="rhdbtn" id="rhd" >RHD</button> 
   <button class="iudbtn" id="iud">IUD </button>
   <button class="syndicatebtn" id="syn">SYNDICATE </button>
   <button class="bombtn" id="bom">BOM </button>
</div>
</div>



<div class="container">
    <div class="minutelist">
        <?php foreach ($minutes as $id => $details): ?>
            <div class="minuteitem" data-id="<?= $id ?>">
                <div class="minutecontent">
                    <h3><?= $details['title'] ?></h3>
                    <p><?= $id ?></p>
                    <span class="minute-type <?= strtolower($details['type']) ?>">
                    <?= strtoupper($details['type']) ?>
                    </span>
                </div>

                <div class="buttons">
                    <a href="<?= ROOT ?>/lecturer/viewminutes/<?= $id ?>">
                        <button class="viewbtn">View</button>
                    </a>
                    <a href="<?= ROOT ?>/lecturer/viewminutes/pdf/<?= $id ?>">
                        <button class="viewbtn">Download PDF</button>
                    </a>
                </div>
            </div> 
        <?php endforeach; ?>
    </div> 
</div> 


<script>
    const minutes = <?= json_encode(array_map(function($item, $id) {
        return ['id' => $id, 'title' => $item['title'], 'type' => $item['type']];
    }, $minutes, array_keys($minutes))); ?>;

    document.addEventListener('DOMContentLoaded', () => {
        const buttons = ['rhd', 'iud', 'syn', 'bom'];

        buttons.forEach(type => {
            document.getElementById(type).addEventListener('click', () => filterMinutes(type));
        });

        function filterMinutes(type) {
            minutes.forEach(item => {
                const element = document.querySelector(`[data-id="${item.id}"]`);
                if (element) {
                    element.style.display = (item.type === type) ? 'flex' : 'none';
                }
            });
        }
    });
</script>

</body>

</html>
