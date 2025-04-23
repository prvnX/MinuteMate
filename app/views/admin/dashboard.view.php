 <head>
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/admin/admin.style.css">
    <link rel="icon" href="<?=ROOT?>/img.png" type="image">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <title>admin Dashboard</title>
    
</head>
<body>
<?php
    $user="admin";
    $notification="notification"; //use notification-dot if there's a notification
    $menuItems = [ "home" => ROOT."/admin" , $notification => ROOT."/admin/notifications", "profile" => ROOT."/admin/viewprofile" , "logout" => ROOT."/admin/confirmlogout"]; //pass the menu items here (key is the name of the page, value is the url)
    require_once("../app/views/components/new_navbar.php"); //call the navbar component
    require_once("../app/views/components/admin_sidebar.php"); //call the sidebar component
    ?>
    <main class="main-content">
      <div class="container">
        <div class="welcome-section">
          <h1>
            <?php
            date_default_timezone_set('Asia/Colombo');
            $currentTime = date("H:i");
            if ($currentTime >= "06:00" && $currentTime < "12:00") {
              $greeting = "Good Morning, ";
            } elseif ($currentTime >= "12:00" && $currentTime < "18:00") {
              $greeting = "Good Afternoon,";
            } else {
              $greeting = "Good Evening,";
            }
            echo $greeting." ".$_SESSION['userDetails']->full_name;
            ?>
          </h1>
        </div>

        <div class="content-wrapper">
          <div class="quick-actions">
            <div class="card" id="pending-member-requests">
              <div class="icon-bg">
                
                <i class="fas fa-file-alt"></i>
              </div>
              <h2>Pending member requests</h2>
              <p>View pending requests</p>
              <a href="<?=ROOT?>/admin/viewpendingRequests"><button>View</button></a>
            </div>
            <div class="card" id="members">
              <div class="icon-bg">
              
              <i class="fa-solid fa-magnifying-glass"></i>
              </div>
              <h2>View Members</h2>
              <p>IOD, RHD, BOM, </p>
              <a href="<?=ROOT?>/admin/viewMembers">
              <button>Members</button>
              </a>
            </div>
            <div class="card" id="remove-members">
              <div class="icon-bg">
                <i class="fas fa-list"></i>
              </div>
              <h2>Past Members</h2>
              <p>view removed members here</p>
              <a href="<?=ROOT?>/admin/PastMembers">
              <button>view</button>
              </a>
            </div>

            <div class="card" id="departments">
              <div class="icon-bg">
                <i class="fas fa-building"></i>
              </div>
              <h2>departments</h2>
              <p>Add Departments here </p>
              <a href="<?=ROOT?>/admin/department">
              <button>view</button>
              </a>
            </div>

            <div class="card" id="view-edit-requests">
              <div class="icon-bg">
                <i class="fas fa-sticky-note"></i>
              </div>
              <h2>View edit requests</h2>
              <p>view edit requests of the user profiles here</p>
              <a href="<?=ROOT?>/admin/vieweditrequests">
              <button>View</button>
              </a>
            </div>
          </div>

          <div class="calendar-section">
          <?php
              $showAddEvents=true;
          require_once("../app/views/components/Calender/calandar-view.php"); //call the calander component
        ?>
        
        </div>
      </div>

    </main>
 
 
 
</body>

</html>
