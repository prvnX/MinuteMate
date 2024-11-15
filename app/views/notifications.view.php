<head>
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/notifications.style.css">
    <title>Notifications</title>
    <style>

    </style>
</head>

<body>
    <?php
    require_once("../app/views/components/navbar.php");

//placeholders only
// Dummy data for unread notifications
$unreadnotifications = [
    [
        'Notification_ID' => 1,
        'User_ID' => 101,
        'Message' => "New meeting created: 'Project Planning'",
        'Notification_Type' => "New meeting created",
        'Related_ID' => 201,
        'Is_Read' => 0,
        'Created_At' => "2024-11-15 23:16:20"
    ],
    [
        'Notification_ID' => 2,
        'User_ID' => 102,
        'Message' => "Memo Submitted: 'Budget Report for Q3'",
        'Notification_Type' => "Memo Submitted",
        'Related_ID' => 301,
        'Is_Read' => 0,
        'Created_At' => "2024-11-14 15:30:00"
    ],
    [
        'Notification_ID' => 3,
        'User_ID' => 103,
        'Message' => "Memo Approved: 'HR Policy Updates'",
        'Notification_Type' => "Memo Approved",
        'Related_ID' => 302,
        'Is_Read' => 0,
        'Created_At' => "2024-11-13 09:00:00"
    ],
    [
        'Notification_ID' => 4,
        'User_ID' => 104,
        'Message' => "New meeting created: 'Quarterly Review'",
        'Notification_Type' => "New meeting created",
        'Related_ID' => 202,
        'Is_Read' => 0,
        'Created_At' => "2024-11-12 08:30:00"
    ],
    [
        'Notification_ID' => 5,
        'User_ID' => 105,
        'Message' => "Memo Submitted: 'Employee Feedback Survey'",
        'Notification_Type' => "Memo Submitted",
        'Related_ID' => 303,
        'Is_Read' => 0,
        'Created_At' => "2024-11-11 16:45:00"
    ],
    [
        'Notification_ID' => 6,
        'User_ID' => 106,
        'Message' => "Memo Declined: 'Annual Budget'",
        'Notification_Type' => "Memo Declined",
        'Related_ID' => 304,
        'Is_Read' => 0,
        'Created_At' => "2024-11-10 14:15:00"
    ],
    [
        'Notification_ID' => 7,
        'User_ID' => 107,
        'Message' => "New meeting created: 'Team Sync-up'",
        'Notification_Type' => "New meeting created",
        'Related_ID' => 205,
        'Is_Read' => 0,
        'Created_At' => "2024-11-09 09:30:00"
    ],
    [
        'Notification_ID' => 8,
        'User_ID' => 108,
        'Message' => "Memo Submitted: 'Client Meeting Minutes'",
        'Notification_Type' => "Memo Submitted",
        'Related_ID' => 305,
        'Is_Read' => 0,
        'Created_At' => "2024-11-08 10:00:00"
    ],
    [
        'Notification_ID' => 9,
        'User_ID' => 109,
        'Message' => "Memo Approved: 'New Marketing Plan'",
        'Notification_Type' => "Memo Approved",
        'Related_ID' => 306,
        'Is_Read' => 0,
        'Created_At' => "2024-11-07 12:30:00"
    ],
    [
        'Notification_ID' => 10,
        'User_ID' => 110,
        'Message' => "New meeting created: 'Year-End Review'",
        'Notification_Type' => "New meeting created",
        'Related_ID' => 207,
        'Is_Read' => 0,
        'Created_At' => "2024-11-06 11:00:00"
    ]
];

// Dummy data for read notifications
$readnotifications = [
    [
        'Notification_ID' => 11,
        'User_ID' => 101,
        'Message' => "Meeting minutes added: 'Project Planning Meeting'",
        'Notification_Type' => "Minute added",
        'Related_ID' => 202,
        'Is_Read' => 1,
        'Created_At' => "2024-11-12 14:00:00"
    ],
    [
        'Notification_ID' => 12,
        'User_ID' => 104,
        'Message' => "Memo Declined: 'Annual Report'",
        'Notification_Type' => "Memo Declined",
        'Related_ID' => 303,
        'Is_Read' => 1,
        'Created_At' => "2024-11-11 11:45:00"
    ],
    [
        'Notification_ID' => 13,
        'User_ID' => 102,
        'Message' => "New meeting created: 'Team Sync-up'",
        'Notification_Type' => "New meeting created",
        'Related_ID' => 202,
        'Is_Read' => 1,
        'Created_At' => "2024-11-10 08:30:00"
    ],
    [
        'Notification_ID' => 14,
        'User_ID' => 105,
        'Message' => "Memo Approved: 'Sales Report Q4'",
        'Notification_Type' => "Memo Approved",
        'Related_ID' => 307,
        'Is_Read' => 1,
        'Created_At' => "2024-11-09 17:00:00"
    ],
    [
        'Notification_ID' => 15,
        'User_ID' => 106,
        'Message' => "Memo Submitted: 'HR Policy Draft'",
        'Notification_Type' => "Memo Submitted",
        'Related_ID' => 308,
        'Is_Read' => 1,
        'Created_At' => "2024-11-08 16:00:00"
    ],
    [
        'Notification_ID' => 16,
        'User_ID' => 107,
        'Message' => "Memo Declined: 'Tech Strategy Meeting'",
        'Notification_Type' => "Memo Declined",
        'Related_ID' => 309,
        'Is_Read' => 1,
        'Created_At' => "2024-11-07 14:45:00"
    ],
    [
        'Notification_ID' => 17,
        'User_ID' => 108,
        'Message' => "New meeting created: 'Company-wide Announcement'",
        'Notification_Type' => "New meeting created",
        'Related_ID' => 210,
        'Is_Read' => 1,
        'Created_At' => "2024-11-06 13:30:00"
    ],
    [
        'Notification_ID' => 18,
        'User_ID' => 109,
        'Message' => "Memo Submitted: 'Budget Forecast for Next Year'",
        'Notification_Type' => "Memo Submitted",
        'Related_ID' => 310,
        'Is_Read' => 1,
        'Created_At' => "2024-11-05 09:30:00"
    ],
    [
        'Notification_ID' => 19,
        'User_ID' => 110,
        'Message' => "Memo Approved: 'Q1 Sales Projections'",
        'Notification_Type' => "Memo Approved",
        'Related_ID' => 311,
        'Is_Read' => 1,
        'Created_At' => "2024-11-04 11:15:00"
    ],
    [
        'Notification_ID' => 20,
        'User_ID' => 111,
        'Message' => "Meeting minutes added: 'Year-End Review Meeting'",
        'Notification_Type' => "Minute added",
        'Related_ID' => 212,
        'Is_Read' => 1,
        'Created_At' => "2024-11-03 08:00:00"
    ]
];
    ?>
    <div class="notifications-container">
        <div class="notifications-header">
            <h1>Notifications</h1>
                    <p class="sub-heading">You have <span id="unReadCount"> <?=count($unreadnotifications)?> </span> Unread Notifications</p>

        </div>
        <div class="notifications-content">
                    <?php
                    function showDuration($notificationDateObj) {
                        $currentDate = new DateTime("now",new DateTimeZone('Asia/Colombo'));  //get current time
                        $interval = $currentDate->diff($notificationDateObj);
                        if ($interval->y > 0) {
                            return $interval->y . " year" . ($interval->y > 1 ? "s" : "") . " ago";
                        } elseif ($interval->m > 0) {
                            return $interval->m . " month" . ($interval->m > 1 ? "s" : "") . " ago";
                        } elseif ($interval->d > 0) {
                            return $interval->d . " day" . ($interval->d > 1 ? "s" : "") . " ago";
                        } elseif ($interval->h > 0) {
                            return $interval->h . " hour" . ($interval->h > 1 ? "s" : "") . " ago";
                        } elseif ($interval->i > 0) {
                            return $interval->i . " minute" . ($interval->i > 1 ? "s" : "") . " ago";
                        } elseif ($interval->s > 0) {
                            return $interval->s . " second" . ($interval->s > 1 ? "s" : "") . " ago";
                        } else {
                            return "Just now";
                        }
                    }
                    foreach ($unreadnotifications as $notification) {
                        $id=$notification['Notification_ID'];
                        $notificationDateObj = new DateTime( $notification['Created_At'],new DateTimeZone('Asia/Colombo'));
                        $duration=showDuration($notificationDateObj);
                        echo "<div class='notification-item' id='Notification-{$id}'>";
                        echo "<p class='notification-message'>" . $notification['Message'] . "</p>";
                        echo "<p class='duration'>".$duration ."</p>";
                        echo "<button class='notification-read-button' id='{$id}' onclick='handleMark(this)'>"."<img src='".ROOT."/assets/images/markasread.png'><span>Mark as read<span></button>";
                        echo "</div>";
                    }
                    foreach ($readnotifications as $notification) {
                        $id=$notification['Notification_ID'];
                        $notificationDateObj = new DateTime( $notification['Created_At'],new DateTimeZone('Asia/Colombo'));
                        $duration=showDuration($notificationDateObj);
                        echo "<div class='notification-item read-notification' id='.$id.'>";
                        echo "<p class='notification-message'>" . $notification['Message'] . "</p>";
                        echo "<p class='duration'>". $duration ."</p>";
                        echo "</div>";
                    }
                    ?>
                    <script>
                        let unReadCount = <?=count($unreadnotifications)?>;
                        let btnClicks = 0;
                        function handleMark(button) {
                            btnClicks=btnClicks+1;
                            const notificationId="Notification-"+button.id;
                            const notification=document.getElementById(notificationId);
                            notification.classList.add('read-notification');
                            button.style.display='none';
                            document.getElementById('unReadCount').innerText=unReadCount-btnClicks;
                    }
                    </script>

        </div>
