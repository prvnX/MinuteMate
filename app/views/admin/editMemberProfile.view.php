<?php 
// Include sidebar and other layout components
include '../app/views/admin/adminsidebar.view.php'; 

// Get member ID from URL
$userId = $_GET['id'] ?? null;

// Fetch member data from the database based on the userId
// Example dummy data (replace with actual database query)
$userData = [
    'name' => 'John Doe',
    'email' => 'johndoe@example.com',
    'lecturer_id' => 'L12345',
    'nic' => '123456789V',
    'role' => 'Lecturer',
    'phone' => '0771234567',
    'meetingTypes' => ['RHD', 'SYN'] // Example of meeting types this member belongs to
];
?>

<div class="content">
    <h3>Edit Member Profile</h3>
    
    <!-- Editable Form for Member Info -->
    <form id="editMemberForm">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($userData['name']) ?>">

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($userData['email']) ?>">

        <label for="lecturer_id">Lecture ID:</label>
        <input type="text" id="lecturer_id" name="lecturer_id" value="<?= htmlspecialchars($userData['lecturer_id']) ?>">

        <label for="nic">NIC:</label>
        <input type="text" id="nic" name="nic" value="<?= htmlspecialchars($userData['nic']) ?>">

        <label for="role">Role:</label>
        <input type="text" id="role" name="role" value="<?= htmlspecialchars($userData['role']) ?>">

        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($userData['phone']) ?>">

        <!-- Meeting Type Selection (allow multiple) -->
        <label>Select Meeting Type(s):</label>
        <div class="meeting-options">
            <?php
            $userData['meetingTypes'] = array_map('strtoupper', $userData['meetingTypes']);
            $meetingTypes = ['RHD', 'IOD', 'SYN', 'BOM']; // List of all meeting types

            foreach ($meetingTypes as $type) {
                $checked = in_array($type, $userData['meetingTypes']) ? 'checked' : '';
                echo "<div class='meeting-option'>
                        <input type='checkbox' id='meeting$type' name='meetingType[]' value='$type' $checked>
                        <label for='meeting$type'>$type</label>
                      </div>";
            }
            ?>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-save">Save Changes</button>
    </form>
</div>

<!-- Include CSS for this page -->
<link rel="stylesheet" href="<?= ROOT ?>/assets/css/admin/editMemberProfile.style.css">

<script>
    document.getElementById('editMemberForm').addEventListener('submit', function(event) {
        event.preventDefault();

        // Collect the form data
        const formData = new FormData(this);
        const updatedData = {
            name: formData.get('name'),
            email: formData.get('email'),
            lecturer_id: formData.get('lecturer_id'),
            nic: formData.get('nic'),
            role: formData.get('role'),
            phone: formData.get('phone'),
            meetingTypes: Array.from(document.querySelectorAll('input[name="meetingType[]"]:checked')).map(input => input.value)
        };

        // Send the updated data to the backend
        fetch(`<?= ROOT ?>/admin/handleMemberAction`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: <?php echo json_encode($userId); ?>,
                action: 'edit',
                updatedData: updatedData
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Member updated successfully!');
                window.location.href = '<?= ROOT ?>/admin/viewMembers'; // Redirect after saving
            } else {
                alert('An error occurred. Please try again.');
            }
        });
    });
</script>
