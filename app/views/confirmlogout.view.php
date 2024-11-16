<!DOCTYPE html>
<head>
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
        <img src="<?=ROOT?>/assets/images/logout-confirm.png" alt="Success" class="success-image">
        <h1>Do  u really want to Logout ? </h1>
        <button onclick="" class="yes">Yes</button>
        <button onclick="" class="no">No</button>

    </div>
    <script>
        const user= "<?= $user ?>";
        const root= "<?= ROOT ?>";
        const yesBtn = document.querySelector('.yes');
        const noBtn = document.querySelector('.no');
        noBtn.addEventListener('click', () => {
            window.location.href = `${root}/${user}`;
        });
        yesBtn.addEventListener('click', () => {
            window.location.href = `${root}/${user}/logout`;
        });
    </script>
</body>