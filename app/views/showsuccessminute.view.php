<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/minutesuccess.style.css">
    <link rel="icon" href="<?=ROOT?>/img.png" type="image">

</head>
<body>
    <?php
    $minuteID = $data['minuteid'];
    ?>
    <div class="success-container">
        <div class="success-icon">
            <div class="circle">
                <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                    <path fill="none" stroke="white" stroke-width="6" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                </svg>
            </div>
        </div>
        <h1 class="success-title">Success!</h1>
        <p class="success-message">Your Minute has been successfully added to the system.</p>
        
        <div class="status-list">
            <div class="status-item">
                <svg class="status-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span class="status-text">All media files have been uploaded to the cloud</span>
            </div>
            <div class="status-item">
                <svg class="status-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span class="status-text">All contents have been forwarded to the relevant departments by email</span>
            </div>
            <div class="status-item">
                <svg class="status-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span class="status-text">Minute has created with the ID : <?=$minuteID?></span>
            </div>
        </div>
        
        <div class="button-container">
            <button class="btn btn-primary" onclick="viewMinute()">View the Minute</button>
            <button class="btn btn-secondary" onclick="continueAction()">Continue</button>
        </div>
    </div>

    <script>
        function viewMinute() {
            
            window.location.href = 'viewminute?minuteID=<?= $minuteID ?>';
        }

        function continueAction() {
            window.location.href = 'dashboard.php';
        }

        </script>
</body>
</html>