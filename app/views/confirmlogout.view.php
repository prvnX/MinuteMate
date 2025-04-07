<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?=ROOT?>/img.png" type="image">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/logout.style.css">


    <title>Logout Confirmation</title>
</head>
<body>
    <div class="logout-container">
        <svg class="logout-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
        <h1 class="logout-title">Logout Confirmation</h1>
        <p class="logout-message">Are you sure you want to log out?</p>
        <div class="button-group">
            <button class="btn btn-primary" onclick="confirmLogout()">Confirm Logout</button>
            <button class="btn btn-secondary" onclick="cancelLogout()">Cancel</button>
        </div>
    </div>

    <script>
        const user= "<?= $user ?>";
        const root= "<?= ROOT ?>";
        function confirmLogout() {
            const container = document.querySelector('.logout-container');
            container.style.animation = 'fadeOut 0.3s ease-in-out forwards';

            setTimeout(() => {
                window.location.href = `${root}/${user}/logout`;
            }, 300);
        }

        function cancelLogout() {
            const container = document.querySelector('.logout-container');
            container.style.animation = 'fadeOut 0.3s ease-in-out forwards';
            
            setTimeout(() => {
                window.location.href = `${root}/${user}`;
            }, 300);
        }

        // Fade out animation
        const keyframes = `
        @keyframes fadeOut {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(-20px); }
        }`;

        const styleSheet = document.createElement("style");
        styleSheet.type = "text/css";
        styleSheet.innerText = keyframes;
        document.head.appendChild(styleSheet);
    </script>
</body>
</html>