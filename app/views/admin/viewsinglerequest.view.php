 
<!DOCTYPE html>
<html lang = "en">

<head>
    
    <meta charset= "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>View Edit requests</title>
     <link rel="stylesheet" href="<?=ROOT?>/assets/css/admin/viewsinglerequest.css">
</head>

<body>
 
<!-- <?php
    $userid = $_GET['userid'];
    echo $userid;
?> -->
  
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
       
        
        <h1 class="heading">Edit request by<span><?= htmlspecialchars($data['name']) ?></span> </h1>

 
        <table class="request-table">
            <thead>
                <tr>
                    <th> </th>
                    <th>Current Value</th>
                    <th>Requested Change</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><label>Full Name</label></td>
                    <td> 
                           <div class="form-group">  

                                <span><?= htmlspecialchars($data['name']) ?></span>
                            </div>
                    
                    </td>
                    <td> 
                            <div class="form-group">     
                            <span><?= htmlspecialchars($data['newname']) ?></span>

                    </td>
                </tr>
                <tr>
                    <td><label>NIC</label></td>   
                    <td>
                        <div class="form-group">
                        
                            <span><?= htmlspecialchars($data['nic']) ?></span>
                    </td>
                    <td> 
                        <div class="form-group">

                        
                            <span><?= htmlspecialchars($data['newnic']) ?></span>
                    </td>
                </tr>
                <tr>
                    <td><label>Additional Note </label></td>
                    <td colspan="2">
                         
                            <div class="form-group">
                            
                                <span><?= htmlspecialchars($data['additionalnote']) ?></span>
                    </td>
                </tr>
            </tbody>
        </table> 

        <div class="actions">
            <button class="decline-btn">Decline</button>
            <button class="accept-btn">Accept the edit request</button>
        </div>
        
    </div>

    
</body>
</html>



