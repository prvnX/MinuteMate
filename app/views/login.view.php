<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SignIn</title>
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/login.style.css">
</head>
<body>
    <div class="login-container">
        <div class="left-container">
            <img src="<?=ROOT?>/assets/images/login-img.png" alt="minutemate-logo" class="login-img ">
        </div>
        <div class="right-container"><h1>Sign In</h1>
        <img src="<?=ROOT?>/assets/images/img.png" alt="minutemate-logo" class="minutemate-logo">
            <form action="<?=ROOT?>/login" method="POST">
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" placeholder="Enter Your Username" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter Your Password" required>
                </div>
                <button type="submit" class="btn-long">Sign In</button>
            </form>
            <p>Don't have an account? <a href="<?=ROOT?>/register">Request One</a></p>
    </div>
</body>