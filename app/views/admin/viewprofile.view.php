<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/viewprofile.style.css">
    
</head>
<body>

    <?php
    $user="admin";
    $memocart="memocart";
    $notification="notification"; //use notification-dot if there's a notification
    $menuItems = [ "home" => ROOT."/admin",$notification => ROOT."/admin/notifications", "profile" => ROOT."/admin/viewprofile"]; //pass the menu items here (key is the name of the page, value is the url)
    echo "<div class='memo-list-navbar'>";
    require_once("../app/views/components/admin_navbar.php");
    echo "</div>";
    require_once("../app/views/components/admin_sidebar.php");

    $profileDetails = [
        "name" => $_SESSION['userDetails']->full_name,
        "Email" => $_SESSION['userDetails']->email,
        "NIC" => $_SESSION['userDetails']->nic,
        "Role"=>$_SESSION['userDetails']->role,
        
    ];

   ?>
    <div class="container">
    <h1>Your Profile</h1>
    <p class="subtitle">View and manage your personal information</p>

    <div class="profile-section">

        <div class="right-panel">

            <div>

            <div class="card">
                <div class="card-header">
                    <span>Personal Information</span>
                    <span class="verified-badge">Verified</span>
                </div>
                <div class="info-row"><strong>Name:</strong> <?= $profileDetails['name']?></div>
                <div class="info-row"><strong>Email:</strong> <?= $profileDetails['Email']?></div>
                <div class="info-row"><strong>NIC:</strong> <?= $profileDetails['NIC']?></div>
                <div class="info-row"><strong>Role:</strong> <?= $profileDetails['Role']?></div>
            </div>


            <div class="card">
                <div class="card-header">Account Security
                    <div class="password">
                        <a href="#" id="changePasswordBtn" class="change-password">Change My Password</a>
                    </div>
                </div>
            </div>

            <div class="button-row">
                <button type="button" class="primary-btn" id="requestchangebtn"> Change</button>
                <a href="<?= ROOT ?>/lecturer/dashboard" class="secondary-btn">Back to Dashboard</a>
            </div>
        </div>
    </div>
</div>

<!-- 🔒 Change Password Modal -->
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

<!-- ✏️ Edit Profile Modal -->
<div class="modal" id="editProfileModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Profile</h2>
            <span class="close-btn" id="editCloseBtn">&times;</span>
        </div>

        <form action="<?= ROOT ?>/admin/updateprofile" method="POST">
            <label for="editFullName">Full Name:</label>
            <input type="text" id="editFullName" name="full_name" value="<?= $profileDetails['name'] ?>" required>

            <label for="editEmail">Email:</label>
            <input type="email" id="editEmail" name="email" value="<?= $profileDetails['Email'] ?>" required>

            <label for="editNIC">NIC:</label>
            <input type="text" id="editNIC" name="nic" value="<?= $profileDetails['NIC'] ?>" required>


            <div class="modal-actions">
                <button type="button" class="cancel-btn" id="editCancelBtn">Cancel</button>
                <button type="submit" class="submit-btn">Update Profile</button>
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
    const editModal = document.getElementById("editProfileModal");

    const openEditBtn = document.getElementById("requestchangebtn");
    const closeEditBtn = document.getElementById("editCloseBtn");
    const cancelEditBtn = document.getElementById("editCancelBtn");

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

     // Open edit profile modal
     openEditBtn.addEventListener("click", () => {
        editProfileModal.style.display = "block";
    });

    // Close edit profile modal
    closeEditBtn.addEventListener("click", () => {
        editProfileModal.style.display = "none";
    });
    cancelEditBtn.addEventListener("click", () => {
        editProfileModal.style.display = "none";
    });

    window.addEventListener("click", (event) => {
        if (event.target === modal) {
            modal.style.display = "none";
        }else if (event.target === editProfileModal) {
            editProfileModal.style.display = "none";
        }
    });

    const passwordForm = document.getElementById("passwordForm");

    passwordForm.addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        console.log(formData);

        fetch("<?= ROOT ?>/admin/viewprofile", {
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

    // requestBtn.addEventListener("click", () => {
    //     window.location.href = "<?= ROOT ?>/lecturer/requestchange";
    // });

    <?php if (isset($_SESSION['error'])): ?>
        window.addEventListener("DOMContentLoaded", () => {
            document.getElementById("editProfileModal").style.display = "block";
         });
    <?php endif; ?>

});
</script>
</body>
</html>
