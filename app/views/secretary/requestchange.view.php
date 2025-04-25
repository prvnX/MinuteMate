  
<!DOCTYPE html>
<html lang = "en">

<head>
    
    <meta charset= "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?=ROOT?>/img.png" type="image">
     <title>Request Change</title>
     <link rel="stylesheet" href="<?=ROOT?>/assets/css/requestchange.style.css">
</head>
<style>
  .meeting-options {
    margin-left: 20px;
    margin-bottom: 15px;
  }

  .meeting-option {
    margin-bottom: 5px;
  }

  .form label {
    display: block;
    font-weight: bold;
    margin-top: 10px;
  }

  .form input[type="checkbox"] {
    margin-right: 5px;
  }
</style>

<body>
 
  
<?php
    $user="secretary";
    $memocart="memocart";   //use memocart-dot if there is a memo in the cart change with db
    $notification="notification"; //use notification-dot if there's a notification
    $menuItems = [ "home" => ROOT."/secretary" , $notification => ROOT."/secretary/notifications", "profile" => ROOT."/secretary/viewprofile"  ]; //pass the menu items here (key is the name of the page, value is the url)
    require_once("../app/views/components/new_navbar.php"); //call the navbar component
    require_once("../app/views/components/std_sidebar.php"); //call the sidebar component
    ?>
    
    <h1>Request Change</h1>
    <div class="container">
         
        <form action="<?= ROOT ?>/secretary/requestchange" method="post">
            <div id="fieldsContainer">
                <div class="field-group">
                    <div class="form">
                        <label for="field">Field to change:</label>
                        <select class="field-select" name="field[]" required>
                            <option value="">Select a field</option>
                            <option value="new_fullname">Full Name</option>
                            <option value="new_email">Email</option>
                            <option value="new_nic">Nic</option>
                    
                            <option value="new_tp_no">Contact Number</option>
                            
                        </select>
                    </div>
                    <div class="form">
                        <label for="newValue">New Value:</label>
                        <input type="text" name="newValue[]" placeholder="Enter the new value" required>
                    </div>
                    

   
                </div>
            </div>

            <div>
                <button type="button" id="addfieldsbtn" class="addfieldbtn">Change one more field</button>
             
             </div>

             <div class="form">
  
  <div class="role-option">
    
  </div>
</div>

<!-- Meeting Types -->
<div class="form form-input-checkbox">
  <div id="secretaryMeetingTypesContainer" style="display: none;">
    <label><strong>Select Secretary Meeting Type:</strong></label>
    <div class="meeting-options">
      <?php foreach (['RHD', 'IOD', 'SYN', 'BOM'] as $type): ?>
        <div class="meeting-option">
          <label>
            <input type="checkbox" name="secretaryMeetingType[]" value="<?= $type ?>">
            <?= $type ?>
          </label>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div id="lecturerMeetingTypesContainer" style="display: none;">
    <label><strong>Select Lecturer Meeting Type:</strong></label>
    <div class="meeting-options">
      <?php foreach (['RHD', 'IOD', 'SYN', 'BOM'] as $type): ?>
        <div class="meeting-option">
          <label>
            <input type="checkbox" name="lecturerMeetingType[]" value="<?= $type ?>">
            <?= $type ?>
          </label>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div id="meetingTypeContainer" style="display: none;">
    <label><strong>Select Meeting Type(s):</strong></label>
    <div class="meeting-options">
      <?php foreach (['RHD', 'IOD', 'SYN', 'BOM'] as $type): ?>
        <div class="meeting-option">
          <label>
            <input type="checkbox" name="meetingType[]" value="<?= $type ?>">
            <?= $type ?>
          </label>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<script>
  function handleRoleSections() {
    const secretary = document.getElementById('secretary');
    const lecturer = document.getElementById('lecturer');
    const student = document.getElementById('student');

    const secretaryContainer = document.getElementById('secretaryMeetingTypesContainer');
    const lecturerContainer = document.getElementById('lecturerMeetingTypesContainer');
    const studentContainer = document.getElementById('meetingTypeContainer');

    // Reset visibility
    secretaryContainer.style.display = 'none';
    lecturerContainer.style.display = 'none';
    studentContainer.style.display = 'none';

    // Role logic
    if (student.checked) {
      secretary.disabled = true;
      lecturer.disabled = true;
      studentContainer.style.display = 'block';
    } else {
      secretary.disabled = false;
      lecturer.disabled = false;
    }

    if (secretary.checked || lecturer.checked) {
      student.disabled = true;
    } else {
      student.disabled = false;
    }

    if (secretary.checked) {
      secretaryContainer.style.display = 'block';
    }

    if (lecturer.checked) {
      lecturerContainer.style.display = 'block';
    }
  }

  // Initialize state on page load
  document.addEventListener('DOMContentLoaded', handleRoleSections);
</script>

            <div class="form">
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="4" placeholder="Enter your message"></textarea>
            </div>

            <div class="form-buttons">
                <button type="submit" class="submit-btn">Submit</button>
                <button type="button" class="cancel-btn" onclick="handleCancel()">Cancel</button>
            </div>
        </form>

    <div id="alertModal" class="modal">
    <div class="modal-contents">
        <div class="modal-body">
            <p id="Message">Your action was successful!</p>
        </div>
        <div class="modal-actions">
            <button type="button" id="successOk" class="btn btn-ok">OK</button>
        </div>
    </div>
</div>

        <script>

function handleCancel() {
        window.location.href = "<?= ROOT ?>/secretary/viewprofile";
    
}


    document.addEventListener("DOMContentLoaded", () => {
    const addFieldsButton = document.getElementById("addfieldsbtn");
    const fieldsContainer = document.getElementById("fieldsContainer");
    let fieldClicks = 1;
    let successMessage = "<?= $responseStatus ?>";
    if (successMessage==="success"){
        showAlert("Request submitted successfully");
    }
    else if (successMessage==="error"){
        showAlert("Request submission failed");
    }

    function updateDropdownOptions() {
        const selectedValues = Array.from(document.querySelectorAll(".field-select"))
            .map(select => select.value)
            .filter(value => value);

        document.querySelectorAll(".field-select").forEach(select => {
            const currentValue = select.value;
            const options = select.querySelectorAll("option");

            options.forEach(option => {
                if (selectedValues.includes(option.value) && option.value !== currentValue) {
                    option.disabled = true; // Disable options already selected
                } else {
                    option.disabled = false; // Re-enable unselected options
                }

            });
        });
    }

    function validateAllFieldGroups() {
        const fieldGroups = document.querySelectorAll(".field-group");

        for (const fieldGroup of fieldGroups) {
            const fieldSelect = fieldGroup.querySelector(".field-select");
            const newValueInput = fieldGroup.querySelector("input[name='newValue[]']");

            // If any field group is incomplete, validation fails
            if (!fieldSelect.value.trim() || !newValueInput.value.trim()) {
                return false;
            }
        }

        return true; // All field groups are valid
    }

    function toggleAddFieldsButton() {
        addFieldsButton.disabled = !validateAllFieldGroups();
    }

    addFieldsButton.addEventListener("click", () => {
        if (fieldClicks >= 4) {
            showAlert("You can only change up to 4 fields at a time.");
            return;
        }

        const fieldGroup = document.querySelector(".field-group");
        const newFieldGroup = fieldGroup.cloneNode(true);

        // Clear cloned inputs
        const inputs = newFieldGroup.querySelectorAll("input, select");
        inputs.forEach(input => {
            input.value = ""; // Clear the value
        });

        // Append the cloned field group to the container
        fieldsContainer.appendChild(newFieldGroup);

        // Add event listeners to the new dropdown and input
        const newFieldSelect = newFieldGroup.querySelector(".field-select");
        const newInput = newFieldGroup.querySelector("input[name='newValue[]']");

        newFieldSelect.addEventListener("change", () => {
            updateDropdownOptions();
            toggleAddFieldsButton();
        });

        newInput.addEventListener("input", toggleAddFieldsButton);

        updateDropdownOptions();
        toggleAddFieldsButton(); // Re-check after adding new fields
        fieldClicks++;
    });

    // Add change event listeners to existing dropdowns and inputs
    document.querySelectorAll(".field-select").forEach(select => {
        select.addEventListener("change", () => {
            updateDropdownOptions();
            toggleAddFieldsButton();
        });
    });

    document.querySelectorAll("input[name='newValue[]']").forEach(input => {
        input.addEventListener("input", toggleAddFieldsButton);
    });

    toggleAddFieldsButton(); // Initial check
});
    function showAlert(message) {
            const modal = document.getElementById('alertModal');
            const messageElement = document.getElementById('Message');
            messageElement.textContent = message;
            modal.style.display = 'block';
            document.getElementById('successOk').onclick = function () {
            modal.style.display = 'none';
            window.location.href = "<?= ROOT ?>/secretary/requestchange";
                
        }
        }
       

        </script>
    </div>
</body>

</html>
