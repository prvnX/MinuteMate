
<head>
 
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/studentrep/dashboard.style.css">
    <link rel="icon" href="<?=ROOT?>/img.png" type="image">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <title>studentrep Dashboard</title>
    
</head>
<body>
<?php
    $user="studentrep";
    $memocart="memocart";   //use memocart-dot if there is a memo in the cart change with db
    $notification="notification"; //use notification-dot if there's a notification
    $menuItems = [ "home" => ROOT."/studentrep" , $notification => ROOT."/studentrep/notifications", "profile" => ROOT."/studentrep/viewprofile" , "logout" => ROOT."/studentrep/confirmlogout"]; //pass the menu items here (key is the name of the page, value is the url)
    require_once("../app/views/components/new_navbar.php"); //call the navbar component
    require_once("../app/views/components/std_sidebar.php"); //call the sidebar component
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
                     
                    <div class="card" id="enter-memo">
                        <div class="icon-bg">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h2>Enter a Memo</h2>
                        <p>Create and attach memos for a meeting</p>
                        <a href="<?=ROOT?>/studentrep/entermemo">
                        <button>New Memo</button>
                        </a>
                    </div>
                    <div class="card" id="view-minutes">
                        <div class="icon-bg">
                            <i class="fas fa-list"></i>
                        </div>
                        <h2>View Minutes</h2>
                        <p>Access all meeting minutes</p>
                        <a href="<?=ROOT?>/studentrep/viewminutes">
                        <button>View Minutes</button>
                        </a>
                    </div>
                    <div class="card" id="view-memos">
                        <div class="icon-bg">
                            <i class="fas fa-sticky-note"></i>
                        </div>
                        <h2>View Submitted Memos</h2>
                        <p>Access submitted memos</p>
                        <a href="<?=ROOT?>/studentrep/viewsubmittedmemos">
                        <button>View Memos</button>
                        </a>
                    </div>
                    <div class="card" id="view-memo-report">
                        <div class="icon-bg">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h2>View Memo Report</h2>
                        <p>Analyze memo Report</p>
                        <a href="<?=ROOT?>/studentrep/selectmemo">
                        <button>View Report</button>
                        </a>
                    </div>
                     
                </div>

                <div class="calendar-section">
                <?php
                      $showAddEvents=false;
              require_once("../app/views/components/Calender/calandar-view.php"); //call the calander component
            ?>
            <div class="message-box">
    <ul>
        
        <li><i class="fas fa-bell"></i> 2 New Notifications</li>
        <li><i class="fas fa-calendar-alt"></i> <?php $Message=$data['meetingsinweek']==1 ? $data['meetingsinweek']." Upcoming Meeting" : $data['meetingsinweek']." Upcoming Meetings"; echo $Message;  ?> this week</li>
    </ul>
</div>
            </div>
        </div>
   
    </main>

    
</body>














































 