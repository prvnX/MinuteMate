<?php 
// Include sidebar and other layout components
include '../app/views/admin/adminsidebar.view.php'; 

// Example dummy data to populate the fields
$userId = $_GET['id'];
$userData = [
    'name' => 'John Doe',
    'email' => 'johndoe@example.com',
    'lecturer_id' => 'L12345',
    'nic' => '123456789V',
    'role' => 'Lecturer',
    'phone' => '0771234567'
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
        <div class="meeting-option rhd-option">
            <input type="checkbox" id="meetingRHD" name="meetingType[]" value="RHD">
            <label for="meetingRHD">RHD</label>
        </div>
        <div class="meeting-option iod-option">
            <input type="checkbox" id="meetingIOD" name="meetingType[]" value="IOD">
            <label for="meetingIOD">IOD</label>
        </div>
        <div class="meeting-option syn-option">
            <input type="checkbox" id="meetingSYN" name="meetingType[]" value="SYN">
            <label for="meetingSYN">SYN</label>
        </div>
        <div class="meeting-option bom-option">
            <input type="checkbox" id="meetingBOM" name="meetingType[]" value="BOM">
            <label for="meetingBOM">BOM</label>
        </div>
    </div>

    <!-- Accept and Decline Buttons -->
    <div class="action-buttons">
        <button class="btn-accept" onclick="handleRequest('accept')">Accept</button>
        <button class="btn-decline" onclick="handleRequest('decline')">Decline</button>
    </div>
</div>

<!-- Include CSS for this page -->
<link rel="stylesheet" href="<?= ROOT ?>/assets/css/admin/viewRequestDetails.style.css">

<script>
    function handleRequest(action) {
        // Get all selected meeting types
        const selectedMeetingTypes = Array.from(document.querySelectorAll('input[name="meetingType[]"]:checked')).map(input => input.value);

        // Example AJAX request to send action and meeting types to the backend
        fetch(`<?= ROOT ?>/admin/handleRequest`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ 
                id: <?php echo json_encode($userId); ?>, 
                action: action,
                meetingTypes: selectedMeetingTypes // Send selected meeting types as an array
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(action === 'accept' ? 'Request accepted!' : 'Request declined.');
                window.location.href = '<?= ROOT ?>/admin/viewpendingRequests';
            } else {
                alert('An error occurred. Please try again.');
            }
        });
    }
</script>
