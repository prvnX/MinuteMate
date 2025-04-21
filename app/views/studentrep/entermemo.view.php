<head>
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/studentrep/entermemo.style.css">
    <link rel="icon" href="<?=ROOT?>/img.png" type="image">
    <link href="https://cdn.ckeditor.com/ckeditor5/37.0.1/classic/theme.css" rel="stylesheet">

    <title>Enter Memo</title>
    
    
</head>
<body>
 
<?php
    $user="studentrep";
    $memocart="memocart";   //use memocart-dot if there is a memo in the cart change with db
    $notification="notification"; //use notification-dot if there's a notification
    $menuItems = [ "home" => ROOT."/studentrep" , $notification => ROOT."/studentrep/notifications", "profile" => ROOT."/studentrep/viewprofile"  ]; //pass the menu items here (key is the name of the page, value is the url)
    require_once("../app/views/components/new_navbar.php"); //call the navbar component
    require_once("../app/views/components/std_sidebar.php"); //call the sidebar component
    ?>

<h1 class="memo-heading">   </h1>
<div class="memo-sub-container">
    <form action="<?=ROOT?>/studentrep/submitmemo" method="post" id="memoForm" onsubmit = "return submitForm(event);">
        <div class="field">
            <label for="meeting" class="input-label">Meeting : </label>
            <select name="meeting" id="meeting" class="select-meeting" required onchange="fetchMeetingUsers(this.value)">
                <option value="" disabled selected>Select a meeting</option>

                <?php
                    if(isset($meetings)&& !empty($meetings)){
                        foreach($meetings as $meeting) {
                            
                            $id=$meeting->meeting_id;
                            $date = htmlspecialchars($meeting->date); 
                            $type = htmlspecialchars($meeting->meeting_type);
                            echo "<option value='$id'>".ucfirst($type)." Meeting On ".$date."</option>";

                        }
                    }else{
                        echo "<option value= '' disabled> NO Future meetings available </option>";
                    }        
                ?>
            </select>


        </div>
        <div class="field">
            <label for="subject" class="input-label">Subject &nbsp;: </label>
            <input type="text" id="subject" name="memo-subject" placeholder="Enter the subject of the meeting" required>
        </div>

        <div class="field">
            <label for="Reviewedby" class="input-label">To be Reviewed by : </label>
            <select name="Reviewedby" id="Reviewedby" class="select-meeting" required>
                <option value="" disabled selected>Select Board Member</option>

                <?php if (isset($selectedMeetingUsers)): ?>
                    <?php foreach ($selectedMeetingUsers as $user): ?>
                        <option value="<?= htmlspecialchars($user->full_name) ?>">
                            <?= htmlspecialchars($user->full_name) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>


            
        </div>

        <!-- Hidden field to store CKEditor content -->
        <textarea id="editor-content" name="memo-content" style="display:none;"></textarea>
        
        <div id="content-sections">
            <!-- Dynamic content sections will be displayed here -->
        </div>

        <div class="form-buttons">
            <button type="button" class="cancel-btn" onclick="handleCancel()">Cancel</button>
            <button type="submit" class="submit-btn">Submit</button>
        </div>
    </form>

    <?php 
        if (!empty($_SESSION['flash_error'])): ?>
        <div class="flash-error"><?= $_SESSION['flash_error']; ?></div>
         <?php 
            unset($_SESSION['flash_error']); ?>
         <?php endif; 
    ?>

</div>

<script src="https://cdn.ckeditor.com/ckeditor5/37.0.1/classic/ckeditor.js"></script>

<script>
    let editor;

    // Initialize CKEditor
    ClassicEditor.create(document.querySelector('#content-sections'), {
        toolbar: [
            'heading', '|', 'bold', 'italic', 'link',
            'bulletedList', 'numberedList', 'blockQuote',
            'insertTable', 'mediaEmbed', 'undo', 'redo'
        ],
        image: {
            toolbar: [
                'imageTextAlternative',
                'imageStyle:full',
                'imageStyle:side'
            ]
        }
    })
    .then(ckeditorInstance => {
        editor = ckeditorInstance;
    })
    .catch(error => {
        console.error('Error initializing CKEditor:', error);
    });

    // Sync CKEditor data to the hidden textarea when submitting the form
    function submitForm(event) {
        event.preventDefault(); // Prevent default form submission

        if (editor) {
      // Update the hidden textarea with the CKEditor content
        const editorContent = editor.getData();
        document.getElementById('editor-content').value = editorContent;

        //  submit the form
        document.getElementById('memoForm').submit();
        } else {
            alert("Editor not ready. Please try again.");
        }
    }

    function handleCancel() {
        window.location.href = "<?=ROOT?>/studentrep";
    }

    window.onload = function() {
        // Optionally, set some initial content
        editor.setData('<p>You can enter your memo<strong> with formatting</strong> <em>here</em>.</p><br><br><br><br><br><br><br>');
    };
</script>

<script>
    document.getElementById("meeting").addEventListener("change", function () {
        const meetingId = this.value;
        const reviewedBySelect = document.getElementById("Reviewedby");

        // Clear current options
        reviewedBySelect.innerHTML = '<option value="" disabled selected>Loading...</option>';

        fetch("<?=ROOT?>/studentrep/fetchUsersByMeeting", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `meeting_id=${meetingId}`,
        })
        .then(response => response.json())
        .then(data => {
            reviewedBySelect.innerHTML = '<option value="" disabled selected>Select Board Member</option>';
            data.forEach(user => {
                const option = document.createElement("option");
                option.value = user.full_name;
                option.textContent = user.full_name;
                reviewedBySelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error("Error fetching users:", error);
            reviewedBySelect.innerHTML = '<option value="" disabled selected>Error loading members</option>';
        });
    });
</script>

</body>
</html>
