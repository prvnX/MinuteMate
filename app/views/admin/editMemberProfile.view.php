<?php
// Include sidebar and other layout components
include '../app/views/components/admin_sidebar.php'; 

// Use the $userData object passed from the controller
$userData = $data['userData'];


?>

<div class="content">
    <h3>Edit Member Profile</h3>
    
    <!-- Editable Form for Member Info -->
    <form id="editMemberForm" method="POST" action="<?= ROOT ?>/admin/updateMember">
    <input type="hidden" name="id" value="<?= $userData->id ?>">
    
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" value="<?= htmlspecialchars($userData->username) ?>" required>

    <label for="full_name">Full Name:</label>
    <input type="text" id="full_name" name="full_name" value="<?= htmlspecialchars($userData->full_name) ?>" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($userData->email) ?>" required>

    <label for="nic">NIC:</label>
    <input type="text" id="nic" name="nic" value="<?= htmlspecialchars($userData->nic) ?>" required>

    <label for="role">Role:</label>
    <input type="text" id="role" name="role" value="<?= htmlspecialchars($userData->role) ?>" required>

    <label for="phone">Phone:</label>
    <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($userData->contact_no) ?>" required>

    <!-- Meeting Type Selection -->
    <label>Select Meeting Type(s):</label>
    <div class="meeting-options">
        <?php
        $meetingTypes = ['RHD', 'IOD', 'SYN', 'BOM'];
        foreach ($meetingTypes as $type) {
            $checked = in_array($type, $userData->meetingTypes) ? 'checked' : '';
            echo "<div class='meeting-option'>
                    <input type='checkbox' id='meeting$type' name='meeting_types[]' value='$type' $checked>
                    <label for='meeting$type'>$type</label>
                  </div>";
        }
        ?>
    </div>

    <button type="submit" class="btn-save">Save Changes</button>
</form>




<!-- Include CSS for this page -->
<link rel="stylesheet" href="<?= ROOT ?>/assets/css/admin/editMemberProfile.style.css">


<script>
    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        // Create a new FormData object from the form
        const formData = new FormData(form);

        // Send the form data via AJAX
        fetch("<?= ROOT ?>/admin/updateMember", {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())  // Parse the JSON response
        .then(data => {
            if (data.status === 'success') {
                // Show success alert
                alert('Member updated successfully');

                // Redirect to the member's profile page after a successful update
                window.location.href = "<?= ROOT ?>/admin/viewMemberProfile?id=" + encodeURIComponent(form.username.value);
            } else {
                // Show the error message from the server if something went wrong
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Something went wrong. Please try again.');
        });
    });
</script>
