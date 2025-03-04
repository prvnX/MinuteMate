<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warning</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/warningminute.style.css">
    <link rel="icon" href="<?=ROOT?>/img.png" type="image">

</head>
<body>
    <div class="warning-container">
        <div class="warning-icon">
            <div class="circle">
                <svg class="exclamation" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                    <path d="M26 16v16 M26 38v2"/>
                </svg>
            </div>
        </div>
        <h1 class="warning-title">Warning</h1>
        <p class="warning-message">The Minute is already created.</p>
        
        <div class="status-list">

            <div class="status-item">
                <svg class="status-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span class="status-text">Another meeting minute has already been recorded for this meeting. Please check the meeting ID</span>
            </div>

        </div>
        
        <div class="button-container">
        <button class="btn btn-secondary" onclick="continueAction()">Continue</button>
        </div>
    </div>

    <script>

        function continueAction() {
            window.location.href = 'dashboard.php';
        }
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.querySelector('.warning-container');
            container.style.animation = 'fadeIn 0.5s ease-in-out, bounce 0.5s ease-in-out 0.5s';
        });

        // Bounce animation
        const keyframes = `
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
            40% {transform: translateY(-20px);}
            60% {transform: translateY(-10px);}
        }`;

        const styleSheet = document.createElement("style");
        styleSheet.type = "text/css";
        styleSheet.innerText = keyframes;
        document.head.appendChild(styleSheet);
    </script>
</body>
</html>