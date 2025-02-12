<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SignIn</title>
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/selectrole.style.css">
    <link rel="icon" href="<?=ROOT?>/img.png" type="image">

</head>
<body>
    <div class="login-container">
        <div class="left-container">
            <img src="<?=ROOT?>/assets/images/login-img.png" alt="minutemate-logo" class="login-img ">
        </div>
        <div class="right-container">
    <h1>Select Your User Type</h1>
    <form action="<?=ROOT?>/selectrole/handlelogin" method="POST">
        <div class="radio-sec">
            <input type="radio" name="role" id="secretary" value="secretary" required>
            <label for="secretary" class="custom-radio">Secretary</label>
            <?php 
                $secMeetingTypes=$_SESSION['secMeetingTypes'];
                foreach($secMeetingTypes as $meetingType){
                    $meetingType = strtolower($meetingType);  
                    if($meetingType=="bom"){
                        echo "<p>BOM Meeting</p>";
                    }
                    else if($meetingType=="rhd"){
                        echo "<p>RHD Meeting</p>";
                    }
                    else if($meetingType=="syn"){
                        echo "<p>Syndicate Meeting</p>";
                    }
                    else if($meetingType=="iod" || $meetingType=="iud"){
                        echo "<p>IUD Meeting</p>";
                }
            }
             ?>
        </div>
        <div class="radio-sec">
            <input type="radio" name="role" id="board-member" value="Board Member" required>
            <label for="board-member" class="custom-radio">Board Member</label>
            <?php 
                $meetingTypes=$_SESSION['meetingTypes'];
                foreach($meetingTypes as $meetingType){
                    $meetingType = strtolower($meetingType);  
                    if($meetingType=="bom"){
                        echo "<p>BOM Meeting</p>";
                    }
                    else if($meetingType=="rhd"){
                        echo "<p>RHD Meeting</p>";
                    }
                    else if($meetingType=="syn"){
                        echo "<p>Syndicate Meeting</p>";
                    }
                    else if($meetingType=="iod"){
                        echo "<p>IUD Meeting</p>";
                }
            }
             ?>
        </div>

        <button type="submit" class="btn-long">Continue</button>
    </form>
</div>
</body>