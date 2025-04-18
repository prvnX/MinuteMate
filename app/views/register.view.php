<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Account</title>
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/register.style.css">
    <link rel="icon" href="<?=ROOT?>/img.png" type="image">

</head>
    <div class="flex-container">
        <div class="register-left-container">
            <img src="<?=ROOT?>/assets/images/signup.webp" alt="minutemate-logo" class="register-img">
        </div>
        <div class="register-right-container">
            <h1 class="sub-heading align-center">Request Account</h1>
            <form action="<?=ROOT?>/register" method="POST">

                <div class="input-group">
                    <label for="username">Full Name</label>
                    <input type="text" name="username" id="username" class="roundedge-input-text" placeholder="Enter your Full Name" required>
                </div>

                <p>Select Your Role</p>
                <div class="radio-group"> 
                <label>
                    <input type="checkbox" name="userType[]" value="secretary" id="secretaryCheck" class="radio-btn">
                    Secretary
                    </label>
                    <label>
                     <input type="checkbox" name="userType[]" value="student" id="studentCheck" class="radio-btn" >
                    Student Representative
                    </label>
                    <label>
                     <input type="checkbox" name="userType[]" value="lecturer" id="lecturerCheck" class="radio-btn">
                    Board Member (Lecturer)
                    </label>
                </div>
                <div class="input-group">
                <div class="dynamic-fields" id="dynamic-fields">
                <label for="lec-id">Lecturer ID</label>
                    <input type="text" name="lec-id" id='lec-id' class="roundedge-input-text" placeholder="Enter your Lecturer ID" required>
                
                </div>
                    <label for="nic">NIC</label>
                    <input type="text" name="nic" id="nic" class="roundedge-input-text" placeholder="Enter your Username" required>
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Enter your Email Address" class="roundedge-input-text"required>
                    <label for="tpno">Telephone Number</label>
                    <input type="text" name="tpno" id="tpno" placeholder="Enter your Telephone Address" class="roundedge-input-text"required>
                    <label for="tpno">Additional Telephone Number (If any)</label>
                    <input type="text" name="additional_tp_no" id="additional_tp_no" placeholder="Enter your Telephone Address" class="roundedge-input-text">
                </div>
                <button type="submit" class="btn-long">Request Account</button>
            </form>
    </div>

    <script src="<?=ROOT?>/assets/js/register.script.js"></script>

</body>

</html>

