<!DOCTYPE html>
<html lang="en">
<head>
<<<<<<< HEAD
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Ubuntu', sans-serif;
        }
    </style>
    <title>You Sure ? </title>
    <style>
        .state-container {
            display: flex;
            margin-top: 50px;
            flex-direction: column;
            align-items: center;
        }

        .state-container img {
            width: 550px;
            height: 550px;
            margin: 0;
        }

        .state-container h1 {
            font-size: 4rem;
            margin: 0 1rem 0.5rem ;
            color:#1e40af;
        }
        .state-container p {
            font-size: 1.5rem;
            font-weight: 100;
            margin: 1rem;
        }
        .state-container button {
            width: 200px;
            padding: 1rem 2rem;
            font-size: 1rem;
            margin: 5px;
            border: none;
            background-color: white;
            border:solid 1px #3b82f6;
            color: #3b82f6;
            cursor: pointer;
            border-radius: 15px;
        }
        .state-container button:hover {
            background-color: #3b82f6;
            color: white;
            transition: 0.5s ease;
=======
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?=ROOT?>/img.png" type="image">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/logout.style.css">
>>>>>>> 5fa4e763e1007cf064ffb40517a08c03fc076e0f


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