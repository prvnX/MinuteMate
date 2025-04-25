<head>
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/secretary/entermemo.style.css">
    <link href="https://cdn.ckeditor.com/ckeditor5/37.0.1/classic/theme.css" rel="stylesheet">
    <link rel="icon" href="<?=ROOT?>/img.png" type="image">


    <title>Enter Memo</title>
    
    
</head>
<body>
<?php
    $user="secretary";
    $memocart="memocart";   //use memocart-dot if there is a memo in the cart change with db
    $notification="notification"; //use notification-dot if there's a notification
    $menuItems = [ "home" => ROOT."/secretary",$memocart => ROOT."/secretary/memocart", $notification => ROOT."/secretary/notifications", "profile" => ROOT."/secretary/viewprofile"]; //pass the menu items here (key is the name of the page, value is the url)
    require_once("../app/views/components/new_navbar.php"); //call the navbar component
    require_once("../app/views/components/sec_sidebar.php");
   
    ?>
<div class="memo-sub-container">
    <form action="<?=ROOT?>/secretary/submitmemo" method="post" id="memoForm">
        <div class="field">
            <label for="meeting" class="input-label">Meeting : </label>
            <select name="meeting" id="meeting" class="select-meeting" required>
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

        <!-- Hidden field to store CKEditor content -->
        <textarea id="editor-content" name="memo-content" style="display:none;"></textarea>
        
        <div id="content-sections">
            <!-- Dynamic content sections will be displayed here -->
        </div>

        <div class="form-buttons">
            <button type="button" class="cancel-btn" onclick="handleCancel()">Cancel</button>
            <button type="submit" class="submit-btn" onclick="submitForm()">Submit</button>
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
        // Update the hidden textarea with the CKEditor content

        const meeting = document.getElementById('meeting').value;
        const subject = document.getElementById('subject').value.trim();
        const content = editor.getData().trim();

        if (!meeting || !subject || !content) {
            Swal.fire({
                icon: 'warning',
                title: 'All Fields Required!',
                text: 'Please fill in the meeting, subject, and memo content before submitting.',
                confirmButtonColor: '#3085d6'
            });
            return;
        }
        const editorContent = editor.getData();
        document.getElementById('editor-content').value = editorContent;

        // Submit the form
        document.getElementById('memoForm').submit();
    }

    function handleCancel() {
        window.location.href = "<?=ROOT?>/secretary";
    }

    window.onload = function() {
        // Optionally, set some initial content
        editor.setData('<p>You can enter your memo<strong> with formatting</strong> <em>here</em>.</p><br><br><br><br><br><br><br>');
    };
</script>
</body>
</html>
