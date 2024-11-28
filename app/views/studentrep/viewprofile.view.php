<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/viewprofile.style.css">
    <style>
        /* Modal styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1000; /* On top of other content */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5); /* Black with transparency */
        }
        .modal-content {
            background-color: #fefefe;
            margin: 10% auto; /* 10% from top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 50%; /* Adjust as needed */
            border-radius: 8px;
            position: relative;
        }
        .close {
            color: #aaa;
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover, .close:focus {
            color: black;
            text-decoration: none;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .btn {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="navbar">
    <?php
    $user=$_SESSION['userDetails']->role;
    $notification="notification"; //use notification-dot if there's a notification
    $menuItems = [ "home" => ROOT."/studentrep", $notification => ROOT."/studentrep/notifications", "profile" => ROOT."/studentrep/viewprofile"]; //pass the menu items here (key is the name of the page, value is the url)
    require_once("../app/views/components/navbar.php"); //call the navbar component
    $profileDetails=["name"=>$_SESSION['userDetails'] -> full_name,"Email"=>$_SESSION['userDetails'] -> email,"LectureID"=>"932837273","NIC"=>$_SESSION['userDetails'] ->nic,"Role"=>" Student Rep","Contact_No:"=>"071 283 3684, 077 647 2983","Meeting_types"=>"IUD, RHD, Syndicate"]
   ?>
    </div>
    <div class="title">
    <h1>Your Profile</h1>
    </div>
    <div class="profile-container">
        <main class="profile-content">
            <div class="footer">
                <img src="<?=ROOT?>/assets/images/profile.png" alt="profile">
            </div>
            <div class="profile-right">
                <p><strong>Name:</strong> <?= $profileDetails['name'] ?></p><br>
                <p><strong>Email:</strong><?= $profileDetails['Email'] ?></p><br>
                <p><strong>Student ID:</strong><?= $profileDetails['LectureID'] ?></p><br>
                <p><strong>NIC:</strong> <?= $profileDetails['NIC'] ?></p><br>
                <p><strong>Role:</strong><?= $profileDetails['Role'] ?></p><br>
                <p><strong>Contact No:</strong><?= $profileDetails['Contact_No:'] ?></p><br>
                <p><strong>Meeting types can attend:</strong><?= $profileDetails['Meeting_types'] ?></p><br>
                <div class="profile-password">
                 <a href="#" id="changePasswordBtn" class="change-password">Change My Password</a>
                 
                 </div>
            
            </div>
                  
            
        </main>
        <div class="form-buttons-container">
            
            <div class="form-buttons">
                
                <button type="button" class="request-change" onclick="" id="requestchangebtn">Request Change </button>
               
            </div> 
            <div class="form-buttons">
                 
            <button type="button" id="continueButton" class="continue-button">Continue</button>

            </div>    
        </div>
    </div>

    <!-- Modal -->
    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeModal">&times;</span>
            <h2>Change Password</h2>
            <form action="#" method="POST">
                <div class="form-group">
                    <label for="currentPassword">Current Password</label>
                    <input type="password" id="currentPassword" name="currentPassword" required>
                </div>
                <div class="form-group">
                    <label for="newPassword">New Password</label>
                    <input type="password" id="newPassword" name="newPassword" required>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                </div>
                <div class="subbtn">
                <button type="submit" class="btn">Reset Password</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById("continueButton").addEventListener("click", () => {
    window.location.href = "<?= ROOT ?>/studentrep"; // Redirect to the dashboard
});

        document.addEventListener("DOMContentLoaded", () => {
            const modal = document.getElementById("passwordModal");
            const openModalBtn = document.getElementById("changePasswordBtn");
            const closeModalBtn = document.getElementById("closeModal");
            const requestbtn = document.getElementById("requestchangebtn");

            // Open modal when the "Change My Password" link is clicked
            openModalBtn.addEventListener("click", (event) => {
                event.preventDefault();
                modal.style.display = "block";
            });

            // Close modal when the close button is clicked
            closeModalBtn.addEventListener("click", () => {
                modal.style.display = "none";
            });

            // Close modal when clicking outside the modal content
            window.addEventListener("click", (event) => {
                if (event.target === modal) {
                    modal.style.display = "none";
                }
            });

            requestbtn.addEventListener("click", ()=>{
                window.location.href= "<?= ROOT ?>/studentrep/requestchange";
            })
        });
    </script>
</body>
</html>
