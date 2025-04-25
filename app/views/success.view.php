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

    <div class="success-container">
        <div class="success-icon">
            <div class="circle">
                <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                    <path fill="none" stroke="white" stroke-width="6" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                </svg>
            </div>
        </div>
        <h1 class="success-title">Success!</h1>
        <p class="success-message">Attendance Mark was successfull.</p>
        

        
        <div class="button-container">
            <button class="btn btn-secondary" onclick="continueAction()">Continue</button>
        </div>
    </div>

    <script>
  

        function continueAction() {
            window.history.back();
        }

        </script>
</body>
</html>