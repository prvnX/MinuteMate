<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
     @import url('https://fonts.googleapis.com/css2?family=Kaushan+Script&display=swap');
     :root {
      --primary-color: #1e40af;
      --secondary-color: #3b82f6;
      --background-color: #f0f9ff;
      --text-color: #1e293b;
      --card-bg-color: #ffffff;
      --navbar-bg-color: #e0f2fe;
    }

    .navbar {
      background-color: var(--navbar-bg-color);
      color: var(--primary-color);
      padding: 8px 16px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      height: 40px;
    }

    .navbar-brand {
      font-size: 1.5em;
      font-weight: bold;
      text-decoration: none;
      color: var(--primary-color);
      font-family:'Kaushan Script', cursive; 
    }

    .navbar-menu {
      display: flex;
      gap: 15px;
      align-items: center;
    }

    .navbar-menu a {
      color: var(--primary-color);
      text-decoration: none;
      padding: 5px 10px;
      border-radius: 5px;
      transition: background-color 0.3s;
    }

    .navbar-menu a:hover {
      background-color: rgba(59, 130, 246, 0.1);
    }
    .search-bar {
      display: flex;
      align-items: center;
      background-color: white;
      border-radius: 20px;
      padding: 5px 10px;
      margin-right: 15px;
      width: 250px;
      margin-top: 1rem;
    }

    .search-bar .search-bar-input {
      border: none;
      outline: none;
      padding: 5px;
      font-size: 14px;
      width: 100%;
      
    }
    .search-btn {
      background: none;
      border: none;
      color: var(--primary-color);
      cursor: pointer;
      padding: 5px 10px;
      border-radius: 15px;
      transition: background-color 0.3s;
    }

    .search-btn:hover {
      background-color: rgba(59, 130, 246, 0.1);
    }
    @media (max-width: 768px) {
      .search-bar {
        width: 150px;
      }
    }
    </style>
</head>


<nav class="navbar">
        <a href="<?=ROOT?>/home" class="navbar-brand">MinuteMate</a>
        <div class="navbar-menu">
            <form action="<?=ROOT."/".$user?>/search" method="post">
            <div class="search-bar">
                <input type="text" placeholder="Search..." name="search" class="search-bar-input" required m>
                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            </form>
            <?php foreach ($menuItems as $name => $url) : ?>
                <a href="<?= $url ?>">
                    <?php
                     $iconClass="";
                     $styles="";
                     switch($name){
                        case "home":
                            $iconClass="fas fa-home";
                            break;
                        case "notification":
                            $iconClass="fa-solid fa-bell";
                            break;
                        case "notification-dot":
                            $iconClass="fa-solid fa-bell";
                            $styles="color:rgb(201, 39, 39)";
                            break;
                        case "profile":
                            $iconClass="fas fa-user";
                            break;
                        case "logout":
                            $iconClass="fas fa-sign-out-alt";
                            break;
                        case "memocart":
                            $iconClass="fa-solid fa-envelope";
                            break;
                        case "memocart-dot":
                            $iconClass="fa-solid fa-envelope";
                            $styles="color:rgb(201, 39, 39)";
                            break;
                     }
                     echo "<i class=".$iconClass."></i>"
                     ?>
                     <i class="<?=$iconClass?>" style="<?=$styles?>"></i>

                </a>
            <?php endforeach; ?>
            

        </div>
</nav>