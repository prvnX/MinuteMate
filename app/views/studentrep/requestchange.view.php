<!DOCTYPE html>
<html lang="en">

<head>
    <title>Request Change</title>
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/requestchange.style.css">
</head>

<body>
    <div class="navbar">
    <?php
        $user="studentrep";
        $memocart="memocart";   //use memocart-dot if there is a memo in the cart change with db
        $notification="notification"; //use notification-dot if there's a notification
        $menuItems = [ "home" => ROOT."/studentrep", $notification => ROOT."/studentrep/notifications", "profile" => ROOT."/studentrep/viewprofile","logout"=> ROOT."/studentrep/confirmlogout"]; //pass the menu items here (key is the name of the page, value is the url)
        require_once("../app/views/components/navbar.php"); //call the navbar component
        ?>
    </div>
    <div class="container">
        <h1>Request Change</h1>
        <form action="<?= ROOT ?>/studentrep/requestchange" method="post">
            <div id="fieldsContainer">
                <div class="field-group">
                    <div class="form">
                        <label for="field">Field to change:</label>
                        <select class="field-select" name="field[]" required>
                            <option value="">Select a field</option>
                            <option value="Username">Username</option>
                            <option value="Email">Email</option>
                            <option value="contact">Telephone number</option>
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
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="4" placeholder="Enter your message"></textarea>
            </div>

            <div class="form-buttons">
                <button type="submit" class="submit-btn" onclick="submitForm()">Submit</button>
                <button type="button" class="cancel-btn" onclick="handleCancel()">Cancel</button>
            </div>
        </form>

        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const addFieldsButton = document.getElementById("addfieldsbtn");
                const fieldsContainer = document.getElementById("fieldsContainer");

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

                addFieldsButton.addEventListener("click", () => {
                    const fieldGroup = document.querySelector(".field-group");
                    const newFieldGroup = fieldGroup.cloneNode(true);

                    newFieldGroup.removeAttribute('required');

                    // Clear cloned inputs
                    const inputs = newFieldGroup.querySelectorAll("input, select");
                    inputs.forEach(input => {
                        input.value = "";          // Clear the value
                        input.removeAttribute("required"); // Remove the required attribute
                    });

                    // Append the cloned field group to the container
                    fieldsContainer.appendChild(newFieldGroup);

                    // Add change event listener to the new select dropdown
                    newFieldGroup.querySelector(".field-select").addEventListener("change", updateDropdownOptions);
                    updateDropdownOptions();
                });

                // Add change event listeners to existing dropdowns
                document.querySelectorAll(".field-select").forEach(select => {
                    select.addEventListener("change", updateDropdownOptions);
                });
            });

            function handleCancel() {
                window.location.href = "<?= ROOT ?>/studentrep/viewprofile";
            }

            function submitForm() {
                const form = document.querySelector("form");

                if (validateForm()) {
                    form.submit();
                    alert("Request sent successfully");
                }
            }

            function validateForm() {
                const fields = document.querySelectorAll(".field-select");
                const values = document.querySelectorAll("input[name='newValue[]']");

                for (let i = 0; i < fields.length; i++) {
                    if (!fields[i].value || !values[i].value) {
                        alert("Please fill in all required fields.");
                        return false;
                    }
                }
                return true;
            }
        </script>
    </div>
</body>

</html>
