<head>
        <link rel="stylesheet" href="<?=ROOT?>/assets/css/component-styles/dashboard-sidebar.style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <div class="sidebar">
            <div class="sidebar-header">
                <h1>Good Morning</h1>
                <p><?= $name ?></p>
            </div>
            <div class="notifications">
                <?php for ($i = 0; $i < count($notificationsArr); $i++): ?>
                    <div class="notification-text">
                        <p><?= $notificationsArr[$i] ?></p>
                    </div>
                <?php endfor; ?>
            </div>   
            <?php  
            require_once("../app/views/components/Calender/calandar-view.php"); //call the calander component
            ?>
            </div>


