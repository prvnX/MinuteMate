<head>
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/notifications.style.css">
    <title>All Notifications </title>
    <link rel="icon" href="<?=ROOT?>/img.png" type="image">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <?php
    require_once("../app/views/components/new_navbar.php");
    $readnotifications=$data['Readnotifications'];
    $unreadnotifications=$data['Unreadnotifications'];

    if($readnotifications==null){
        $readnotifications=[];
    }
    if($unreadnotifications==null){
        $unreadnotifications=[];
    }

    ?>

    <div class="container">
        <div class="page-header">
            <div class="page-title-section">
                <h1>Notifications</h1>
                <p class="page-description">
                    You have <span id="unReadCount" class="notification-type"><?= count($unreadnotifications) ?></span> unread notifications
                </p>
            </div>
            <button class="clear-btn" onclick="removeOldNotifications()">
                Clear Older Notifications
            </button>
        </div>

        <div class="card">
            <div class="card-body">
                <div id="notifications-list">
                    <?php
                    function showDuration($notificationDateObj)
                    {
                        $currentDate = new DateTime("now", new DateTimeZone('Asia/Colombo'));
                        $interval = $currentDate->diff($notificationDateObj);
                        if ($interval->y > 0) return $interval->y . " year" . ($interval->y > 1 ? "s" : "") . " ago";
                        if ($interval->m > 0) return $interval->m . " month" . ($interval->m > 1 ? "s" : "") . " ago";
                        if ($interval->d > 0) return $interval->d . " day" . ($interval->d > 1 ? "s" : "") . " ago";
                        if ($interval->h > 0) return $interval->h . " hour" . ($interval->h > 1 ? "s" : "") . " ago";
                        if ($interval->i > 0) return $interval->i . " minute" . ($interval->i > 1 ? "s" : "") . " ago";
                        if ($interval->s > 0) return $interval->s . " second" . ($interval->s > 1 ? "s" : "") . " ago";
                        return "Just now";
                    }

                    if (empty($unreadnotifications) && empty($readnotifications)) {
                        echo '<div class="empty-state">
                            <div class="empty-state-icon">📭</div>
                            <p class="empty-state-text">No notifications yet</p>
                            <p class="empty-state-subtext">When you get notifications, they\'ll show up here</p>
                        </div>';
                    }

                    foreach ($unreadnotifications as $notification) {
                        $id = $notification->notification_id;
                        $time = $notification->created_at;
                        $title=$notification->notification_type;
                        switch ($title) {
                            case 'minute':
                                $title = 'Minute Created';
                                break;
                            case 'meeting':
                                $title = 'Meeting Created';
                                break;
                            case 'rescheduled':
                                $title = 'Meeting Rescheduled';
                                break;
                            case 'memo':
                                $title = 'Memo Created';
                                break;
                            case 'approved':
                                $title = 'Memo Accepted';
                                break;
                            case 'declined':
                                $title = 'Memo Rejected';
                                break;
                            case 'deleted':
                                $title = 'Meeting Deleted';
                                break;
                            default:
                                $title = 'General Notification';
                        }
                        
                        echo "
                        <div class='notification-item unread' id='Notification-{$id}'>
                            <div class='notification-content'>
                                <p class='notification-message'>{$notification->notification_message}</p>
                                <div class='notification-meta'>
                                    <span class='notification-time'>{$time}</span>
                                    <span class='notification-type'>{$title}</span>
                                </div>
                            </div>
                            <div class='notification-actions'>
                                <button class='btn btn-secondary' onclick='updateState($id)' data-id='{$id}'>Mark as read</button>
                            </div>
                        </div>";
                    }

                    foreach ($readnotifications as $notification) {
                        $id = $notification->notification_id;
                        $time = $notification->created_at;
                        $title=$notification->notification_type;
                        switch ($title) {
                            case 'minute':
                                $title = 'Minute Created';
                                break;
                            case 'meeting':
                                $title = 'Meeting Created';
                                break;
                            case 'rescheduled':
                                $title = 'Meeting Rescheduled';
                                break;
                            case 'memo':
                                $title = 'Memo Created';
                                break;
                            case 'approved':
                                $title = 'Memo Accepted';
                                break;
                            case 'declined':
                                $title = 'Memo Rejected';
                                break;
                            case 'deleted':
                                $title = 'Meeting Deleted';
                                break;
                            default:
                                $title = 'General Notification';
                        }
                        
                        echo "
                        <div class='notification-item read' id='Notification-{$id}'>
                            <div class='notification-content'>
                                <p class='notification-message'>{$notification->notification_message}</p>
                                <div class='notification-meta'>
                                    <span class='notification-time'>{$time}</span>
                                    <span class='notification-type'>{$title}</span>
                                </div>
                            </div>
                        </div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        let unreadCount = <?= count($unreadnotifications) ?>;

        function removeOldNotifications() {
            fetch('<?=ROOT?>/NotificationService/deleteNotifications', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ deleteOldNotifications : true })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    console.error('Error deleting notifications:', data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });

            
            
        }

        function updateState(id){
            const notificationId = id;
            fetch('<?=ROOT?>/NotificationService/updateNotificationState',{
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ notification_id:notificationId })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Notification updated successfully');
                        const button = document.querySelector(`button[data-id="${notificationId}"]`);
                        const card = button.closest('.notification-item');
                        card.classList.remove('unread');
                        card.classList.add('read');
                        button.remove();
                        unreadCount--;
                        document.getElementById('unReadCount').innerText = unreadCount;
                         checkEmptyState();
                    } else {
                        console.error('Error updating notification:', data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });


            
      
            }
            
        

        function checkEmptyState() {
            // Count visible notifications
            const visibleCount = document.querySelectorAll('.notification-item').length;
            
            // Get or create empty state element
            let emptyState = document.querySelector('.empty-state');
            
            if (visibleCount === 0) {
                if (!emptyState) {
                    emptyState = document.createElement('div');
                    emptyState.className = 'empty-state';
                    emptyState.innerHTML = `
                        <div class="empty-state-icon">📭</div>
                        <p class="empty-state-text">No notifications</p>
                        <p class="empty-state-subtext">When you get notifications, they'll show up here</p>
                    `;
                    document.getElementById('notifications-list').appendChild(emptyState);
                } else {
                    emptyState.style.display = 'block';
                }
            } else if (emptyState) {
                emptyState.style.display = 'none';
            }
        }
    </script>
</body>
