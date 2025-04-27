<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="<?=ROOT?>/img.png" type="image">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/viewprofile.style.css">
   
</head>
<body>
  
    <?php
        
        $notification="notification"; //use notification-dot if there's a notification
        $menuItems = [ "home" => ROOT."/secretary", $notification => ROOT."/secretary/notifications", "profile" => ROOT."/secretary/viewprofile"]; //pass the menu items here (key is the name of the page, value is the url)
        
        echo "<div class='memo-list-navbar'>";
        require_once("../app/views/components/new_navbar.php");
        echo "</div>";
        require_once("../app/views/components/sec_sidebar.php");
         
        $profileDetails = $data['userDetails'][0];
        $ContactDetails = $data['contactNumbers'];
        $roleDetails = $data['userRole'][0];
        $meetingTypes = $data['userMeetingTypes'];
        $attendenceMeetings = $data['attendenceMeetings'];


        

   
    ?>

<div class="container">
    <h1>Your Profile</h1>
    <p class="subtitle">View and manage your personal information</p>

    <div class="profile-section">
        <div class="left-panel">
            <div class="profile-photo">
            <img src="<?=ROOT?>/assets/images/profile.png" alt="profile">


                <!-- <button class="change-photo-btn">Change Photo</button> -->
            </div>

            <div class="stats-box">
            <div ><strong class= "atendence"> <?= $attendenceMeetings[0]->attendence_count ?></strong><br>Meetings Attended</div>            </div>
        </div>

        <div class="right-panel">
            <div class="card">
                <div class="card-header">
                    <span>Personal Information</span>
                    <span class="verified-badge"><?= strtoupper($profileDetails->status)  ?></span>
                </div>
               
                <p><strong>Name:</strong> <?= $profileDetails->full_name ?></p><br>
                <p><strong>Email:</strong> <?= $profileDetails->email ?></p><br>
                <p><strong>NIC:</strong> <?= $profileDetails->nic ?></p><br>
                <p><strong>Username:</strong> <?= $profileDetails->username ?></p><br>
                <p><strong>Role:</strong> <?= $roleDetails->role ?></p><br>
                 
                <?php $beforenum = 0; ?>
                <p><strong>Contact No: </strong><?php foreach($ContactDetails as $key=>$contact){
                    if($beforenum!=$contact->contact_no){
                        
                    if($key==0){
                        $beforenum = $contact->contact_no;
                        echo $contact->contact_no;
                    }else{
                        echo ", ".$contact->contact_no;
                    }
                }
                }?></p>
                


            </div>

            <div class="card">
    <div class="card-header">Meeting Access</div>
    <p>You can attend the following meeting types:</p>
    <div class="badge-group">
        <?php
        $meetingTypes = $data['userMeetingTypes'];
        if (!empty($meetingTypes) && is_array($meetingTypes)) {
            $colors = [
                'IOD' => 'background-color: #FBBF24; color: white;',
                'RHD' => 'background-color: #F87171; color: white;',
                'SYN' => 'background-color: #4ADE80; color: black;',
                'BOM' => 'background-color: #60A5FA; color: white;',
            ];
            foreach ($meetingTypes as $typeObj) {
                $type = $typeObj->meeting_type;
      
                $style = isset($colors[$type]) ? $colors[$type] : 'background-color: gray; color: white;';
                if($type=="IOD"){
                    $type = "IUD";
                }
                echo "<span class='badge' style='$style'>" . htmlspecialchars($type) . "</span> ";
            }
        } else {
            echo "<p style='color: gray;'>No meeting types available</p>";
        }
        ?>
    </div>
</div>


            <div class="card">
                <div class="card-header">Account Security
                    <div class="password">
                        <a href="#" id="changePasswordBtn" class="change-password">Change My Password</a>
                    </div>
                </div>
            </div>

            <div class="button-row">
                <button type="button" class="primary-btn" id="requestchangebtn">Request Change</button>
                <a href="<?= ROOT ?>/secretary/dashboard" class="secondary-btn">Back to Dashboard</a>
            </div>
        </div>
    </div>
</div>

<!--Change Password Modal -->
<div class="modal" id="passwordModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-lock"></i> Change Password</h2>
            <span class="close-btn" id="modalCloseBtn">&times;</span>
        </div>

        <form action="#" id="passwordForm">
            <label>Current Password</label>
            <input type="password" name="current_password" required>

            <label>New Password</label>
            <input type="password" name="new_password" required>

            <label>Confirm Password</label>
            <input type="password" name="confirm_password" required>

            <div class="password-strength">Password Strength</div>
            <div class="password-requirements">
                <strong>Password must contain:</strong>
                <ul>
                    <li>At least 8 characters</li>
                    <li>At least one uppercase letter</li>
                    <li>At least one number</li>
                    <li>At least one special character</li>
                </ul>
            </div>

            <div class="modal-actions">
                <button type="button" class="cancel-btn" id="cancelBtn">Cancel</button>
                <button type="submit" class="submit-btn">Update Password</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("passwordModal");
    const openModalBtn = document.getElementById("changePasswordBtn");
    const closeModalBtn = document.getElementById("modalCloseBtn");
    const cancelBtn = document.getElementById("cancelBtn");
    const requestBtn = document.getElementById("requestchangebtn");

    openModalBtn.addEventListener("click", (event) => {
        event.preventDefault();
        modal.style.display = "block";
    });

    closeModalBtn.addEventListener("click", () => {
        modal.style.display = "none";
    });

    cancelBtn.addEventListener("click", () => {
        modal.style.display = "none";
    });

    window.addEventListener("click", (event) => {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

    requestBtn.addEventListener("click", () => {
        window.location.href = "<?= ROOT ?>/secretary/requestchange";
    });

    const passwordForm = document.getElementById("passwordForm");

    passwordForm.addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        console.log(formData);

        fetch("<?= ROOT ?>/secretary/viewprofile", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert("✅ Password updated successfully.");
                passwordForm.reset();
                document.getElementById("passwordModal").style.display = "none";
            } else {
                alert("❌ " + data.errors.join("\n"));
            }
        })
        .catch(err => {
            alert("❌ Something went wrong.");
            console.error(err);
        });
    });

    // Password strength message
    const passwordInput = document.querySelector('input[name="new_password"]');
    

const strengthText = document.querySelector('.password-strength');
    

    passwordInput.addEventListener("input", () => {
        const value = passwordInput.value;
        if (/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/.test(value)) {
            strengthText.textContent = "✅ Strong password";
            strengthText.style.color = "green";
        } else {
            strengthText.textContent = "❌ Weak password";
            strengthText.style.color = "red";
        }
    });
});
</script>
</body>
</html>
