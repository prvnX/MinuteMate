<?php 
// Include sidebar and other layout components
include '../app/views/components/admin_sidebar.php'; 

// Retrieve the user details passed from the controller
$userDetails = $data['userDetails']; // The fetched user details
$userId = $_GET['id'];

?>

<div class="content">
    <div class="profile-header">
        <img src="<?= ROOT ?>/assets/images/user.png" alt="Profile Image" class="profile-img">
        <h3><?php echo htmlspecialchars($userDetails->full_name); ?></h3>
    </div>
    <p>Email: <?php echo htmlspecialchars($userDetails->email); ?></p>
    <p>Lecture ID: <?php echo htmlspecialchars($userDetails->lec_stu_id); ?></p>
    <p>NIC: <?php echo htmlspecialchars($userDetails->nic); ?></p>
    <p>Role: <?php echo htmlspecialchars($userDetails->role); ?></p>
    <p>Contact No.: <?php echo htmlspecialchars($userDetails->tp_no); ?></p>
    <p>Additional Contact No. if any : <?php echo htmlspecialchars($userDetails->additional_tp_no); ?></p>


   <!-- Meeting Type Selection (allow multiple) -->
<label>Select Meeting Type(s):</label>
<div class="meeting-options">
    <div class="meeting-option rhd-option">
        <input type="checkbox" id="meetingRHD" name="meetingType[]" value="1"> <!-- RHD -->
        <label for="meetingRHD">RHD</label>
    </div>
    <div class="meeting-option iod-option">
        <input type="checkbox" id="meetingIOD" name="meetingType[]" value="2"> <!-- IOD -->
        <label for="meetingIOD">IOD</label>
    </div>
    <div class="meeting-option syn-option">
        <input type="checkbox" id="meetingSYN" name="meetingType[]" value="3"> <!-- SYN -->
        <label for="meetingSYN">SYN</label>
    </div>
    <div class="meeting-option bom-option">
        <input type="checkbox" id="meetingBOM" name="meetingType[]" value="4"> <!-- BOM -->
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
    const selectedMeetingTypes = Array.from(
        document.querySelectorAll('input[name="meetingType[]"]:checked')
    ).map(input => input.value);

    const userId = "<?= $userId ?>";

    fetch("<?= ROOT ?>/admin/handleRequest", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: action,
            id: userId,
            meetingTypes: selectedMeetingTypes,
        }),
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(action === 'accept' ? 'Request accepted and user added!' : 'Request declined.');
                window.location.href = '<?= ROOT ?>/admin/viewpendingRequests';
            } else {
                alert('An error occurred. Please try again.');
            }
        });
}

</script>
