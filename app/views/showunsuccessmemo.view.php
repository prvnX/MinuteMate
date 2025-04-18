<!DOCTYPE html>
<head>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" href="<?=ROOT?>/img.png" type="image">

    <style>
        body {
            font-family: 'Ubuntu', sans-serif;
        }
    </style>
    <title>UnSuccess</title>
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
        <img src="<?=ROOT?>/assets/images/unsuccess.png" alt="Success" class="success-image">
        <h1>UnSuccess</h1>
        <p>Your memo could not be submitted. Please try again.</p>
        <button onclick=tryAgain() class="try-again-btn">Try Again</button>
        <button onclick="" class="dashboard-btn">Go to Dashboard</button>

    </div>
    <script>
        const user= "<?= $user ?>";
        const root= "<?= ROOT ?>";
        const goToDashboardBtn = document.querySelector('.dashboard-btn');

        function tryAgain() {
           
           window.history.back();
       }

       goToDashboardBtn.addEventListener('click', () => {
            window.location.href = `${root}/${user}`;
        });

    </script>
</body>