<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/lecturer/entermemo.style.css">
    <link rel="icon" href="<?=ROOT?>/img.png" type="image">
    <link href="https://cdn.ckeditor.com/ckeditor5/37.0.1/classic/theme.css" rel="stylesheet">
    <title>Enter Memo</title>
</head>
<body>
<?php
    $user="lecturer";
    $notification="notification"; //use notification-dot if there's a notification
    $menuItems = [ "home" => ROOT."/studentrep" , $notification => ROOT."/studentrep/notifications", "profile" => ROOT."/studentrep/viewprofile"  ]; //pass the menu items here (key is the name of the page, value is the url)
    require_once("../app/views/components/new_navbar.php"); //call the navbar component
    require_once("../app/views/components/std_sidebar.php"); //call the sidebar component
    
?>
<h1 class="memo-heading"> Enter a memo </h1>
<div class="memo-sub-container">
    <form action="<?=ROOT?>/lecturer/submitmemo" method="post" id="memoForm">
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
        const editorContent = editor.getData();
        document.getElementById('editor-content').value = editorContent;

        // Submit the form
        document.getElementById('memoForm').submit();
    }

    function handleCancel() {
        window.location.href = "<?=ROOT?>/lecturer";
    }

    window.onload = function() {
        // Optionally, set some initial content
        editor.setData('<p>You can enter your memo<strong> with formatting</strong> <em>here</em>.</p><br><br><br><br><br><br><br>');
    };
</script>
</body>
</html>
