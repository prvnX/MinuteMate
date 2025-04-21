<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/component-styles/new_navbar.style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
</head>
<?php
// show($_SESSION);
?>

<nav class="navbar">
    <a href="<?=ROOT?>/home" class="navbar-brand">MinuteMate</a>
    <div class="navbar-menu">
        <form action="<?=ROOT."/".$user?>/search" method="post">
            <div class="search-bar">
                <input type="text" placeholder="Search..." name="search" class="search-bar-input" required>
                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i>
                </button>
            </div>

        </form>

        <?php foreach ($menuItems as $name => $url) : ?>
            <?php 
                
                $iconClass = "";
                $styles = "";
                switch($name) {
                    case "home":
                        $iconClass = "fas fa-home";
                        break;
                    case "notification":
                        $iconClass = "fa-solid fa-bell";
                        break;
                    case "notification-dot":
                        $iconClass = "fa-solid fa-bell";
                        break;
                    case "profile":
                        $iconClass = "fas fa-user";
                        break;
                    case "logout":
                        $iconClass = "fas fa-sign-out-alt";
                        break;
                    case "memocart":
                        $iconClass = "fa-solid fa-envelope";
                        break;
                    case "memocart-dot":
                        $iconClass = "fa-solid fa-envelope";
                        $styles = "color:rgb(201, 39, 39)";
                        break;
                }
            ?>
            
            <?php if ($name == "notification" || $name == "notification-dot") : ?>
                <div class="notification-container">
                    <a href="#" class="notification-icon">
                        <i class="<?= $iconClass ?>" style="<?= $styles ?>"></i>
                    </a>
                    <div class="notification-count">
                      0
                    </div>
                    
                    <div class="notification-dropdown">
                      <div class="notification-titles">
                        <div class="notification-header">Notifications
                        <div class="clear-notifications" onclick="markAllRead()">Mark all as read </div>
                        </div>

                        </div>
                        
                        <div id="notification-list">
                            <!-- Notifications will be loaded here -->
                        </div>
                        <div class="notification-footer">
                            <a href="<?= $url ?>">View all Notifications</a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <a href="<?= $url ?>">
                    <i class="<?= $iconClass ?>" style="<?= $styles ?>"></i>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</nav>

<script>
    function timeAgo(dateString) {
    const now = new Date();
    const createdDate = new Date(dateString);
    const diffMs = now - createdDate;
    const diffSec = Math.floor(diffMs / 1000);
    const diffMin = Math.floor(diffSec / 60);
    const diffHr = Math.floor(diffMin / 60);
    const diffDay = Math.floor(diffHr / 24);
    const diffMonth = Math.floor(diffDay / 30); // Approximate months
    const diffYear = Math.floor(diffDay / 365); 

    if (diffSec < 60) return "just now";
    if (diffMin < 60) return `${diffMin} minute${diffMin !== 1 ? "s" : ""} ago`;
    if (diffHr < 24) return `${diffHr} hour${diffHr !== 1 ? "s" : ""} ago`;
    if (diffDay < 7) return `${diffDay} day${diffDay !== 1 ? "s" : ""} ago`;
    if (diffMonth < 12) return `${diffMonth} month${diffMonth !== 1 ? "s" : ""} ago`;
    

    return createdDate.toLocaleDateString(); // fallback for over a year
}
    noofnotifications = 0;
    document.addEventListener("DOMContentLoaded", function() {
        fetchNotifications();

                
    });

    const notificationIcon = document.querySelector(".notification-icon");
        const notificationDropdown = document.querySelector(".notification-dropdown");
        const notificationList = document.getElementById("notification-list");
        const notificationCount = document.getElementsByClassName("notification-count")[0];




        // Load notifications dynamically
        function loadNotifications(notifications) {
            if(!notifications){
                notificationList.innerHTML = "<div class='notification-item'>No Unread notifications</div>";
                notificationCount.classList.add("notification-count-hide");
                return;
            }
            noofnotifications = notifications.length;
                notificationCount.textContent = noofnotifications // Set notification count
                    if(noofnotifications == 0){
                    notificationCount.classList.add("notification-count-hide");
                }
                // console.log(noofnotifications);
            notificationList.innerHTML = "";
            if (notifications.length === 0) {
                notificationList.innerHTML = "<div class='notification-item'>No notifications</div>";
            } else {
                notifications.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                notifications.forEach(notification => {
                    let iconClass = "";
                    if(notification.notification_type.toLowerCase().includes("meeting")) {
                        iconClass = "event_available";
                    }
                    else if(notification.notification_type.toLowerCase().includes("approved")) {
                        iconClass = "check_circle";
                    }
                    else if(notification.notification_type.toLowerCase().includes("declined")) {
                        iconClass = "contract_delete";
                    }
                    else if(notification.notification_type.toLowerCase().includes("memo")) {
                        iconClass = "description";
                    }
                    else if(notification.notification_type.toLowerCase().includes("minute")){
                      iconClass = "task";
                    }
                    else if(notification.notification_type.toLowerCase().includes("deleted")){
                      iconClass = "event_busy";
                    }
                    else if(notification.notification_type.toLowerCase().includes("rescheduled")){
                      iconClass="event_upcoming";
                    }
                    else{
                        iconClass = "notifications";
                    }
                    let itemicon = document.createElement("i");
                    itemicon.className ="material-symbols-outlined notification-item-icon";
                    itemicon.innerText = iconClass ;
                    let item = document.createElement("div");
                    item.className = "notification-item";
                    item.onclick = function (){
                        if(notification.link.toLowerCase().includes('event')){
                            var userlink="<?= ROOT.'/'?>";
                        }
                        else{
                            var userlink="<?= ROOT.'/'.$_SESSION['userDetails']->role.'/' ?>";
                            
                        }
                        updateNotificationState(notification.notification_id);
                        window.location.href = userlink + notification.link;
                    }
                        
                        
                    
                    let title = document.createElement("div");
                    title.className = "notification-title";
                    title.innerText = notification.notification_message;
                    let time = document.createElement("div");
                    time.className = "notification-time";
                    time.innerText = timeAgo(notification.created_at);
                    title.appendChild(itemicon);
                    title.appendChild(time);
                    item.appendChild(title);


                    notificationList.appendChild(item);
  
                    
                });
            }
        }

        function fetchNotifications(){// fetchng notifications for the logged in user
        fetch("<?=ROOT?>/NotificationService/getNotifications", {
        }).then(response =>{
            if (response.ok) {
                return response.json();
            } else {
                throw new Error("Network response was not ok");
            }
        }).then(data => {
            if (data.success) {
                let notifications = data.notifications;
                 
                loadNotifications(notifications);

                
            } else {
                console.error("Error fetching notifications:", data.error);
            }
        }).catch(error => {
            console.error("Fetch error:", error);
        })
            
        }

        let click = true;
        // Toggle notification dropdown
        notificationIcon.addEventListener("click", function(event) {
            if(click){
              notificationIcon.style.color = "#6b6c6e"; // Change to pressed color
              notificationIcon.style.fontSize = "0.95rem"; // Simulate pressing effect
              click =!click;
            }else{
                notificationIcon.style.color = "#1e40af";
                notificationIcon.style.fontSize = "1rem"; // Reset scale

                click =!click;
            }
            event.preventDefault();
            fetchNotifications();
            notificationDropdown.classList.toggle("active");
            notificationCount.classList.toggle("notification-count-hide");

            // if(notificationIcon.style.color === "#1e40af") {
            //     notificationIcon.style.color = "#1e293b";
            // }
        });

    function markAllRead(){
        fetch("<?=ROOT?>/NotificationService/getNotifications", {
        }).then(response =>{
            if (response.ok) {
                return response.json();
            } else {
                throw new Error("Network response was not ok");
            }
        }).then(data => {
            if (data.success) {
                let notifications = data.notifications;
                if(notifications){
                    notifications.forEach(notification => {
                        updateNotificationState(notification.notification_id);
                    });
                }

                
            } else {
                console.error("Error fetching notifications:", data.error);
            }
        }).catch(error => {
            console.error("Fetch error:", error);
        })
        fetchNotifications();
        notificationCount.textContent = 0; // Set notification count
        notificationCount.classList.add("notification-count-hide");

    }

    function updateNotificationState(notificationId){
            fetch("<?=ROOT?>/NotificationService/updateNotificationState", {
                method: "POST",
                headers: {
                        "Content-Type": "application/json"
                },
                body: JSON.stringify({ notification_id: notificationId }) // Moved inside fetch options
            })
            .then(response => response.json()) // Convert response to JSON
            .then(data => {
               if (data.success) {
                    //console.log("Notification state updated successfully");
                } else {
                    console.error("Error updating notification state:", data.error);

                }
            })
            .catch(error => {
                console.error("Fetch error:", error);
            }); 
        }
</script>