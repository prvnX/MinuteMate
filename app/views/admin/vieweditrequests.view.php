<!DOCTYPE html>
<html lang = "en">

<head>
    
    <meta charset= "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>View Edit requests</title>
     <link rel="stylesheet" href="<?=ROOT?>/assets/css/admin/vieweditrequests.css">
</head>

<body>
 


  
<div class="navbar">    
<?php
    
    $user="admin";
    $notification="notification"; //use notification-dot if there's a notification
    $menuItems = [ "home" => ROOT."/admin", $notification => ROOT."/admin/notifications", "profile" => ROOT."/admin/viewprofile","logout" => ROOT."/admin/confirmlogout"]; //pass the menu items here (key is the name of the page, value is the url)
    require_once("../app/views/components/navbar.php"); //call the navbar component
    $showAddEvents = false; 
   ?>

    
</div>
    <!-- Sidebar -->
 <div class="sidebar">
         
        <ul>
            <li><a href="#"><img src="<?=ROOT?>/assets/images/writing.png" alt="writing">   Enter a Memo</img></li>
            <li class="active"><img src="<?=ROOT?>/assets/images/sticky.png" alt="sticky">     View Edit requests</a></li>
            <li><a href="#"><img src="<?=ROOT?>/assets/images/note.png" alt="note">  View Memo Reports</a></li>
            <li><a href="#"><img src="<?=ROOT?>/assets/images/interface.png" alt="interface">  Review Student Memos</a></li>
        </ul>
    </div>
     
  <!-- Main Content -->
  <div class="main-content">
        <h1 class="heading">Edit requests</h1>
        <div class="memolist">
            <?php
            // Dummy data for demonstration
            

            $_REQUEST = [
                
                        ['userid' => '001',
                          'name' => 'Nuwan chanu',
                          'newname' => 'Nuwan Chanuka',
                          'nic' => '19981234567V',
                          'newnic' => '199812345567',
                          'additionalnote' => 'I would like to request a change of my user profile name to [New Name]. Please ensure that this change is reflected across all associated platforms and services. If any further information is required to process this request, do not hesitate to contact me.',
              
                        ],
                      [
                          'userid' => '002',
                          'name' => 'John Doe',
                          'newname' => 'John Dohn',
                          'nic' => '19981234567V',
                          'newnic' => '19981234565',
                          'additionalnote' => 'I would like to request a change of my user profile name to [New Name]. Please ensure that this change is reflected across all associated platforms and services. If any further information is required to process this request, do not hesitate to contact me.',
                        ],
                      [
                          'userid' => '003',
                          'name' => 'keneth sil',
                          'newname' => 'keneth Doe',
                          'nic' => '19981234567V',
                          'newnic' => '19981234567',
                          'additionalnote' => 'I would like to request a change of my user profile name to [New Name]. Please ensure that this change is reflected across all associated platforms and services. If any further information is required to process this request, do not hesitate to contact me.',
                        ],
                      ];
                  
                    
      
            ?>
            
                <?php foreach ($_REQUEST as $request): ?>
                    
                    <div class="memoitem">
                        <div class="memocontent">
                            <h1 class="heading">Edit request by <span><?= htmlspecialchars($request['name']) ?></span></h1>
                        </div>
                        <a href="<?= ROOT ?>/admin/viewsinglerequest/<?= $request['userid'] ?>">
                            <button type="button" id="viewbutton" class="viewbtn">View</button>
                        </a>
                    </div>
                   
                <?php endforeach; ?>
                
            
        </div>
        
        <div class="form-buttons">
                 
            <button type="button" id="continueButton" class="continue-button">Continue</button>

            </div> 
    </div>
</body>
</html>



<script>
        document.getElementById("continueButton").addEventListener("click", () => {
    window.location.href = "<?= ROOT ?>/admin"; // Redirect to the dashboard

}); 
</script>