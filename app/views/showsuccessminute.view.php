<!DOCTYPE html>
<head>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Ubuntu', sans-serif;
        }
    </style>
    <title>Success</title>
    <style>
        .state-container {
            display: flex;
            margin-top: 50px;
            flex-direction: column;
            align-items: center;
        }

        .state-container img {
            width: 500px;
            height: 500px;
            margin: 0;
        }

        .state-container h1 {
            font-size: 4rem;
            margin: 1rem;
            color:#65558F;
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
            border:solid 1px #65558F;
            color: #65558F;
            cursor: pointer;
            border-radius: 15px;
        }
        .state-container button:hover {
            background-color: #65558F;
            color: white;
            transition: 0.5s ease;


        }


    </style>
</head>
<body>
    <div class="state-container">     
        <img src="<?=ROOT?>/assets/images/success.png" alt="Success" class="success-image">
        <h1>Success</h1>
        <p>Your Minute has been successfully added to the system.</p>
        <button onclick="" class="view-minute-btn">View the Minute</button>
        <button onclick="" class="continue-btn">Continue</button>

    </div>
    <script>
        const user= "<?= $user ?>";
        const root= "<?= ROOT ?>";
        const viewMinuteBtn = document.querySelector('.view-minute-btn');
        const continueBtn = document.querySelector('.continue-btn');
        continueBtn.addEventListener('click', () => {
            window.location.href = `${root}/${user}`;
        });
        viewMinuteBtn.addEventListener('click', () => {
            window.location.href = `${root}/${user}/viewminute?minuteid=<?= $minuteid ?>`;
        });
    </script>
</body>