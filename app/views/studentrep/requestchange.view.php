<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
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
  
  .error-message {
    color: red;
    font-size: 0.85em;
    margin-top: 5px;
    display: none;
  }
  
  input.invalid {
    border: 1px solid red;
  }
</style>

<body>
  
<?php
    $user="studentrep";
    $memocart="memocart";   //use memocart-dot if there is a memo in the cart change with db
    $notification="notification"; //use notification-dot if there's a notification
    $menuItems = [ "home" => ROOT."/studentrep" , $notification => ROOT."/studentrep/notifications", "profile" => ROOT."/studentrep/viewprofile"  ]; //pass the menu items here (key is the name of the page, value is the url)
    require_once("../app/views/components/new_navbar.php"); //call the navbar component
    require_once("../app/views/components/std_sidebar.php"); //call the sidebar component
?>
    
<h1>Request Change</h1>
<div class="container">
     
    <form action="<?= ROOT ?>/studentrep/requestchange" method="post" id="requestChangeForm">
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
                    <div class="error-message"></div>
                </div>
            </div>
        </div>

        <div>
            <button type="button" id="addfieldsbtn" class="addfieldbtn">Change one more field</button>
        </div>

        <div class="form">
            <div class="role-option"></div>
        </div>

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
        window.location.href = "<?= ROOT ?>/studentrep/viewprofile";
    }

    document.addEventListener("DOMContentLoaded", () => {
        const addFieldsButton = document.getElementById("addfieldsbtn");
        const fieldsContainer = document.getElementById("fieldsContainer");
        const form = document.getElementById("requestChangeForm");
        let fieldClicks = 1;
        let successMessage = "<?= $responseStatus ?>";
        
        if (successMessage === "success") {
            showAlert("Request submitted successfully");
        } else if (successMessage === "error") {
            showAlert("Request submission failed");
        }

        // Validation rules for each field
        const validationRules = {
            new_fullname: {
                pattern: /^[A-Za-z\s.'-]{2,100}$/,
                message: "Full name should only contain letters, spaces and basic punctuation (2-100 characters)"
            },
            new_email: {
                pattern: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
                message: "Please enter a valid email address"
            },
            new_nic: {
                pattern: /^(\d{8}[vVxX]|\d{12})$/,
                message: "NIC should be in format 12345678V or 12 digits"
            },
            new_tp_no: {
                pattern: /^(?:\+94|0)?[0-9]{9,10}$/,
                message: "Contact number should be a valid Sri Lankan phone number"
            }
        };

        // Function to validate input based on selected field
        function validateInput(fieldSelect, input, errorElement) {
            const selectedField = fieldSelect.value;
            const inputValue = input.value.trim();
            
            // Clear previous validation
            input.classList.remove("invalid");
            errorElement.style.display = "none";
            
            if (!selectedField || !inputValue) {
                return true; // Skip validation if empty
            }
            
            if (validationRules[selectedField]) {
                const rule = validationRules[selectedField];
                if (!rule.pattern.test(inputValue)) {
                    input.classList.add("invalid");
                    errorElement.textContent = rule.message;
                    errorElement.style.display = "block";
                    return false;
                }
            }
            
            return true;
        }

        // Update dropdown options to prevent duplicate selections
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

        // Validate all field groups
        function validateAllFieldGroups() {
            const fieldGroups = document.querySelectorAll(".field-group");
            let isValid = true;

            for (const fieldGroup of fieldGroups) {
                const fieldSelect = fieldGroup.querySelector(".field-select");
                const newValueInput = fieldGroup.querySelector("input[name='newValue[]']");
                const errorElement = fieldGroup.querySelector(".error-message");

                // Check if fields are filled
                if (!fieldSelect.value.trim() || !newValueInput.value.trim()) {
                    isValid = false;
                }

                // Validate input based on selected field
                if (!validateInput(fieldSelect, newValueInput, errorElement)) {
                    isValid = false;
                }
            }

            return isValid;
        }

        // Toggle add fields button based on validation
        function toggleAddFieldsButton() {
            addFieldsButton.disabled = !validateAllFieldGroups();
        }

        // Add form submission handler
        form.addEventListener("submit", function(event) {
            if (!validateAllFieldGroups()) {
                event.preventDefault();
                showAlert("Please correct the errors before submitting.");
            }
        });

        // Add event handler for adding new fields
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

            // Reset error messages
            const errorElement = newFieldGroup.querySelector(".error-message");
            errorElement.textContent = "";
            errorElement.style.display = "none";

            // Append the cloned field group to the container
            fieldsContainer.appendChild(newFieldGroup);

            // Add event listeners to the new elements
            setupFieldGroupListeners(newFieldGroup);

            updateDropdownOptions();
            toggleAddFieldsButton();
            fieldClicks++;
        });

        // Setup event listeners for a field group
        function setupFieldGroupListeners(fieldGroup) {
            const fieldSelect = fieldGroup.querySelector(".field-select");
            const newValueInput = fieldGroup.querySelector("input[name='newValue[]']");
            const errorElement = fieldGroup.querySelector(".error-message");

            fieldSelect.addEventListener("change", () => {
                updateDropdownOptions();
                validateInput(fieldSelect, newValueInput, errorElement);
                toggleAddFieldsButton();
            });

            newValueInput.addEventListener("input", () => {
                validateInput(fieldSelect, newValueInput, errorElement);
                toggleAddFieldsButton();
            });
            
            newValueInput.addEventListener("blur", () => {
                validateInput(fieldSelect, newValueInput, errorElement);
            });
        }

        // Set up event listeners for existing field groups
        document.querySelectorAll(".field-group").forEach(setupFieldGroupListeners);

        // Initial check
        updateDropdownOptions();
        toggleAddFieldsButton();
    });

    function showAlert(message) {
        const modal = document.getElementById('alertModal');
        const messageElement = document.getElementById('Message');
        messageElement.textContent = message;
        modal.style.display = 'block';
        document.getElementById('successOk').onclick = function() {
            modal.style.display = 'none';
            // Only redirect on success message
            if (message === "Request submitted successfully") {
                window.location.href = "<?= ROOT ?>/studentrep/requestchange";
            }
        }
    }
    </script>
</div>
</body>
</html>