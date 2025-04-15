<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/component-styles/new_navbar.style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
</head>

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
                      5
                    </div>
                    
                    <div class="notification-dropdown">
                      <div class="notification-titles">
                        <div class="notification-header">Notifications
                        <div class="clear-notifications">Mark all as read </div>
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
    noofnotifications = 0;
    document.addEventListener("DOMContentLoaded", function() {
        const notificationIcon = document.querySelector(".notification-icon");
        const notificationDropdown = document.querySelector(".notification-dropdown");
        const notificationList = document.getElementById("notification-list");
        const notificationCount = document.getElementsByClassName("notification-count")[0];

        // Sample notifications array (Replace this with fetch request later)
        const notifications = [
        {
        title: "New meeting created: 'Project Planning'",
        time: "2 hours ago"
        },
        {
        title: "Memo Submitted: 'Budget Report for Q3'",
        time: "1 minute ago"
        },
        {
        title: "Memo Approved: 'HR Policy Updates'",
        time: "30 minutes ago"
        },
        {
        title: "New meeting created: 'Quarterly Review'",
        time: "4 hours ago"
        },
        {
        title: "Memo Declined: 'Annual Budget'",
        time: "3 hours ago"
        },
        {
        title: "Meeting Rescheduled: 'Project Planning'",
        time: "1 hour ago"
        },
        {
        title: "Meeting Deleted: 'Project Planning'",
        time: "1 hour ago"
        },
        {
          title : "New Minute Created: 'Project Planning'",
          time : "1 hour ago"
        }
        ];

console.log(notifications);
        noofnotifications = notifications.length;
        notificationCount.textContent = noofnotifications // Set notification count
        if(noofnotifications == 0){
            notificationCount.classList.add("notification-count-hide");
        }


        // Load notifications dynamically
        function loadNotifications() {
            notificationList.innerHTML = "";
            if (notifications.length === 0) {
                notificationList.innerHTML = "<div class='notification-item'>No notifications</div>";
            } else {
                notifications.forEach(notification => {
                    let iconClass = "";
                    if(notification.title.toLowerCase().includes("meeting")) {
                        iconClass = "event_available";
                    }
                    else if(notification.title.toLowerCase().includes("approved")) {
                        iconClass = "check_circle";
                    }
                    else if(notification.title.toLowerCase().includes("declined")) {
                        iconClass = "contract_delete";
                    }
                    else if(notification.title.toLowerCase().includes("memo")) {
                        iconClass = "description";
                    }
                    else if(notification.title.toLowerCase().includes("minute")){
                      iconClass = "task";
                    }
                    else if(notification.title.toLowerCase().includes("deleted")){
                      iconClass = "event_busy";
                    }
                    else if(notification.title.toLowerCase().includes("rescheduled")){
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
                    let title = document.createElement("div");
                    title.className = "notification-title";
                    title.innerText = notification.title;
                    let time = document.createElement("div");
                    time.className = "notification-time";
                    time.innerText = notification.time;
                    title.appendChild(itemicon);
                    title.appendChild(time);
                    item.appendChild(title);


                    notificationList.appendChild(item);
  
                    
                });
            }
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
            loadNotifications();
            notificationDropdown.classList.toggle("active");
            notificationCount.classList.toggle("notification-count-hide");

            // if(notificationIcon.style.color === "#1e40af") {
            //     notificationIcon.style.color = "#1e293b";
            // }
        });

    });
</script>