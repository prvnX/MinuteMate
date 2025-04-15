<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unsuccessful</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/minuteunsuccess.style.css">

</head>
<body>
    <div class="unsuccessful-container">
        <div class="unsuccessful-icon">
            <div class="circle">
                <svg class="x-mark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                    <path d="M16 16 36 36 M36 16 16 36" />
                </svg>
            </div>
        </div>
        <h1 class="unsuccessful-title">Unsuccessful</h1>
        <p class="unsuccessful-message">We encountered an issue while processing the Minute.</p>
        
        <div class="status-list">
            <div class="status-item">
                <svg class="status-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="status-text">Please Check your internet connection</span>
            </div>

        </div>
        
        <div class="button-container">
            <button class="btn btn-primary" onclick="tryAgain()">Try Again</button>
            <button class="btn btn-secondary" onclick="contactSupport()">Contact Support</button>
            <button class="btn btn-secondary" onclick="gotodashboard()">Go to the Dashboard</button>

        </div>
    </div>

    <script>
        function tryAgain() {
           
            window.history.back();
        }

        function contactSupport() {
            window.location.href = 'mailto:minutemate111@gmail.com?subject=Support Request On Creating Minute&body=Describe your issue here...(Add some screenshots if possible)';

        }
        function gotodashboard(){
            window.location.href='dashboard';
        }


        // Add shake animation on load
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.querySelector('.unsuccessful-container');
            container.style.animation = 'shake 0.82s cubic-bezier(.36,.07,.19,.97) both';
        });

        // Shake animation
        const keyframes = `
        @keyframes shake {
            10%, 90% {
                transform: translate3d(-1px, 0, 0);
            }
            
            20%, 80% {
                transform: translate3d(2px, 0, 0);
            }

            30%, 50%, 70% {
                transform: translate3d(-4px, 0, 0);
            }

            40%, 60% {
                transform: translate3d(4px, 0, 0);
            }
        }`;

        const styleSheet = document.createElement("style");
        styleSheet.type = "text/css";
        styleSheet.innerText = keyframes;
        document.head.appendChild(styleSheet);
    </script>
</body>
</html>