<?php 
// Include sidebar and other layout components
include '../app/views/admin/adminsidebar.view.php'; 

// Example dummy data for the selected member
$userId = $_GET['id'] ?? null;
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
    <div class="profile-header">
        <img src="<?= ROOT ?>/assets/images/user.png" alt="Profile Image" class="profile-img">
        <h3><?php echo htmlspecialchars($userData['name']); ?></h3>
    </div>
    <p>Email: <?php echo htmlspecialchars($userData['email']); ?></p>
    <p>Lecture ID: <?php echo htmlspecialchars($userData['lecturer_id']); ?></p>
    <p>NIC: <?php echo htmlspecialchars($userData['nic']); ?></p>
    <p>Role: <?php echo htmlspecialchars($userData['role']); ?></p>
    <p>Contact No.: <?php echo htmlspecialchars($userData['phone']); ?></p>

    <!-- Meeting Type Selection (allow multiple) -->
    <label>Select Meeting Type(s):</label>
    <div class="meeting-options">
        <?php
        // Normalize the meetingTypes to uppercase for comparison
        $userData['meetingTypes'] = array_map('strtoupper', $userData['meetingTypes']);
        $meetingTypes = ['RHD', 'IOD', 'SYN', 'BOM']; // List of all meeting types

        // Loop through all meeting types
        foreach ($meetingTypes as $type) {
            // Check if this meeting type is selected for the user
            $checked = in_array($type, $userData['meetingTypes']) ? 'checked' : '';

            // Assign the appropriate class for styling each meeting type
            $class = strtolower($type) . '-option';

            echo "<div class='meeting-option $class'>
                    <input type='checkbox' id='meeting$type' name='meetingType[]' value='$type' $checked>
                    <label for='meeting$type'>$type</label>
                  </div>";
        }
        ?>
    </div>

    <!-- Edit and Remove Buttons -->
    <div class="action-buttons">
        <button class="btn-edit" onclick="handleMemberAction('edit')">Edit</button>
        <button class="btn-remove" onclick="handleMemberAction('remove')">Remove</button>
    </div>
</div>

<!-- Include CSS for this page -->
<link rel="stylesheet" href="<?= ROOT ?>/assets/css/admin/viewMemberProfile.style.css">

<script>
    function handleMemberAction(action) {
        const selectedMeetingTypes = Array.from(document.querySelectorAll('input[name="meetingType[]"]:checked')).map(input => input.value);

        // AJAX request to send action, member ID, and selected meeting types to the backend
        fetch(`<?= ROOT ?>/admin/handleMemberAction`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ 
                id: <?php echo json_encode($userId); ?>, 
                action: action,
                meetingTypes: selectedMeetingTypes 
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(action === 'edit' ? 'Member updated successfully!' : 'Member removed.');
                window.location.href = '<?= ROOT ?>/admin/viewMembers';
            } else {
                alert('An error occurred. Please try again.');
            }
        });
    }
</script>
