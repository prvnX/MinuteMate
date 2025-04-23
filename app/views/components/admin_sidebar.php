<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
:root {
      --primary-color: #1e40af;
      --secondary-color: #3b82f6;
      --background-color: #f0f9ff;
      --text-color: #1e293b;
      --card-bg-color: #ffffff;
      --navbar-bg-color: #e0f2fe;
    }
    .sidebar-toggle {
            position: fixed;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 80px;
            background-color: #1e40af;
            border: none;
            border-radius: 0 20px 20px 0;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1001;
            transition: background-color 0.3s;
        }

        .sidebar-toggle:hover {
            background-color: #2563eb;
        }

        .custom-sidebar {
            position: fixed;
            left: -250px;
            top: 0;
            width: 250px;
            height: 100%;
            background:white;
            backdrop-filter: blur(10px);
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            transition: left 0.3s;
            z-index: 1000;
            margin-right: 100px;
        }

        .custom-sidebar.open {
            left: 0;
        }

        .custom-sidebar ul {
            list-style-type: none;
            padding: 0;
            margin-top: 60px;
        }

        .custom-sidebar ul li {
            padding: 10px 20px;
        }

        .custom-sidebar ul li a {
            color: #1e40af;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: color 0.3s;
        }

        .custom-sidebar ul li:hover {
            background-color: rgb(200, 222, 251);
            transition: background-color 0.3s;
          
          }

        .custom-sidebar ul li a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
            color: #1e40af;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            color: #1e40af;
            font-size: 24px;
            cursor: pointer;
        }
        .remove-btn{
          left: -250px;
        }
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5); 
        backdrop-filter: blur(1px); 
        z-index: 999;
        display: none; 
    }

.custom-sidebar.open ~ .overlay {
    display: block;
}
    </style>
</head>
<button id="openSidebar" class="sidebar-toggle">
        <i class="fas fa-bars"></i>
    </button>
    <div class="custom-sidebar" id="customSidebar">
        <button id="closeSidebar" class="close-btn">&times;</button>
        <img src="<?=ROOT?>/img.png" alt="Logo" style="width: 150px; margin: 10px auto; display: block;">
        <ul>
            <li><a href="<?=ROOT?>/admin" data-section="home"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="<?=ROOT?>/admin/viewpendingRequest" data-section="pending-member-requests">  <i class="fas fa-file-alt"></i>view pending requests</a></li>
            <li><a href="<?=ROOT?>/admin/viewMembers" data-section="members"><i class="fa-solid fa-magnifying-glass"></i> View members</a></li>
            <li><a href="<?=ROOT?>/admin/PastMembers" data-section="remove-members"><i class="fas fa-list"></i> Past Members</a></li>
            <li><a href="<?=ROOT?>/admin/department" data-section="department"><i class="fas fa-building"></i> Departments</a></li>
            <li><a href="<?=ROOT?>/admin/vieweditrequests" data-section="view-edit-requests"><i class="fas fa-sticky-note"></i> View edit requests</a></li>
            <li><a href="<?=ROOT?>/admin/viewprofile" data-section="settings"><i class="fa-solid fa-user"></i> View Profile </a></li>
        </ul>
        </div>
        <div class="overlay" id="overlay"></div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search functionality
            const customSidebar = document.getElementById('customSidebar');
            const openSidebarBtn = document.getElementById('openSidebar');
            const closeSidebarBtn = document.getElementById('closeSidebar');
            const sidebarLinks = customSidebar.querySelectorAll('a');

            openSidebarBtn.addEventListener('click', function() {
                openSidebarBtn.classList.add('remove-btn')   
                customSidebar.classList.add('open');
            });

            closeSidebarBtn.addEventListener('click', function() {
                customSidebar.classList.remove('open');
                openSidebarBtn.classList.remove('remove-btn')   

            });

            // Close sidebar when clicking outside
            document.addEventListener('click', function(event) {
                if (!customSidebar.contains(event.target) && event.target !== openSidebarBtn) {
                    customSidebar.classList.remove('open');
                    openSidebarBtn.classList.remove('remove-btn')   

                }
            });

            // Add click event listeners to sidebar links
            sidebarLinks.forEach(function(link) {
                link.addEventListener('click', function() {
                    customSidebar.classList.remove('open');
                    openSidebarBtn.classList.remove('remove-btn')   

                });
            });
        });
        </script>