<head>
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/lecturer/selectmemo.style.css">
    <title>Please Select a memo</title>
    
</head>
<body>
<?php
    $user="lecturer";
    $memocart="memocart";   //use memocart-dot if there is a memo in the cart change with db
    $notification="notification"; //use notification-dot if there's a notification
    $menuItems = [ "home" => ROOT."/lecturer",$memocart => ROOT."/lecturer/memocart", $notification => ROOT."/lecturer/notifications", "profile" => ROOT."/lecturer/viewprofile"]; //pass the menu items here (key is the name of the page, value is the url)
    require_once("../app/views/components/navbar.php"); //call the navbar component
    $memos= [
        1 => array(
            'id' => 12,
            'Title' => 'Memo A',
            'date' => '2024-11-20'

        ),
        2 => array(
            'id' => 13,
            'Title' => 'Memo B',
            'date' => '2024-11-21'
        ),
        3 => array(
            'id' => 14,
            'Title' => 'Memo C',
            'date' => '2024-11-22'
        ),
        4 => array(
            'id' => 15,
            'Title' => 'Memo D',
            'date' => '2024-11-23'
        ),
        5 => array(
            'id' => 16,
            'Title' => 'Memo E',
            'date' => '2024-11-24'
        )
        ];
    ?>
    <div class="select-memo-container">
    <h1 class="select-memo-heading">Please select a memo</h1>
    <select name="memo" id="memo" class="select-memo">
        <option value="" disabled selected>Select a memo</option>
        <?php
        foreach($memos as $memo) {
            $key=$memo['id'];
            echo "<option value='$key'>".$memo['Title']." - ".$memo['date']."</option>";
        }
        ?>
    </select>
    <button class="select-memo-button" id="selectmemoButton">view memo report</button>
    <button class="select-memo-button" id="cancelButton">Cancel</button>
    </div>
    <div class="calender">
    <?php  
            $showAddEvents=false;
            require_once("../app/views/components/Calender/calandar-view.php"); //call the calander component
            ?>
    </div>
    <script>
        document.getElementById("selectmemoButton").addEventListener("click", function() {
            var memo = document.getElementById("memo").value;
            if(memo) {
                window.location.href = "<?=ROOT?>/lecturer/viewmemoreports?memo="+memo;
            }
        });
        document.getElementById("cancelButton").addEventListener("click", function() {
            window.location.href = "<?=ROOT?>/lecturer";
        });
    </script>


   