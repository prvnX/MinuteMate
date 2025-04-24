<?php 
include '../app/views/components/admin_sidebar.php'; 

$userDetails = $data['userDetails']; 
$userId = $_GET['id'];

$isLecturer = strpos($userDetails->role, 'lecturer') !== false;
$isSecretary = strpos($userDetails->role, 'secretary') !== false;
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

    <!-- Meeting Type Selection -->
    <?php if ($isSecretary && $isLecturer): ?>
        <!-- Secretary Meeting Type -->
        <label>Select Secretary Meeting Type:</label>
        <div class="meeting-options">
            <?php foreach (['RHD', 'IOD', 'SYN', 'BOM'] as $type): ?>
                <div class="meeting-option <?= strtolower($type) ?>-option">
                    <input type="checkbox" id="secretary<?= $type ?>" name="secretaryMeetingType[]" value="<?= $type ?>">
                    <label for="secretary<?= $type ?>"><?= $type ?></label>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Lecturer Meeting Type -->
        <label>Select Lecturer Meeting Type:</label>
        <div class="meeting-options">
            <?php foreach (['RHD', 'IOD', 'SYN', 'BOM'] as $type): ?>
                <div class="meeting-option <?= strtolower($type) ?>-option">
                    <input type="checkbox" id="lecturer<?= $type ?>" name="lecturerMeetingType[]" value="<?= $type ?>">
                    <label for="lecturer<?= $type ?>"><?= $type ?></label>
                </div>
            <?php endforeach; ?>
        </div>

    <?php elseif ($isSecretary): ?>
        <!-- Only Secretary -->
        <label>Select Secretary Meeting Type:</label>
        <div class="meeting-options">
            <?php foreach (['RHD', 'IOD', 'SYN', 'BOM'] as $type): ?>
                <div class="meeting-option <?= strtolower($type) ?>-option">
                    <input type="checkbox" id="secretary<?= $type ?>" name="secretaryMeetingType[]" value="<?= $type ?>">
                    <label for="secretary<?= $type ?>"><?= $type ?></label>
                </div>
            <?php endforeach; ?>
        </div>

    <?php elseif ($isLecturer): ?>
        <!-- Only Lecturer -->
        <label>Select Lecturer Meeting Type:</label>
        <div class="meeting-options">
            <?php foreach (['RHD', 'IOD', 'SYN', 'BOM'] as $type): ?>
                <div class="meeting-option <?= strtolower($type) ?>-option">
                    <input type="checkbox" id="lecturer<?= $type ?>" name="lecturerMeetingType[]" value="<?= $type ?>">
                    <label for="lecturer<?= $type ?>"><?= $type ?></label>
                </div>
            <?php endforeach; ?>
        </div>

    <?php else: ?>
        <!-- Other Roles -->
        <label>Select Meeting Type(s):</label>
        <div class="meeting-options">
            <?php foreach (['RHD', 'IOD', 'SYN', 'BOM'] as $type): ?>
                <div class="meeting-option <?= strtolower($type) ?>-option">
                    <input type="checkbox" id="meeting<?= $type ?>" name="meetingType[]" value="<?= $type ?>">
                    <label for="meeting<?= $type ?>"><?= $type ?></label>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Accept and Decline Buttons -->
    <div class="action-buttons">
        <button class="btn-accept" onclick="handleRequest('accept')">Accept</button>
        <button class="btn-decline" onclick="handleRequest('decline')">Decline</button>
    </div>
</div>

<link rel="stylesheet" href="<?= ROOT ?>/assets/css/admin/viewRequestDetails.style.css">

<script>
    function handleRequest(action) {
        const generalTypes = Array.from(document.querySelectorAll('input[name="meetingType[]"]:checked')).map(input => input.value);
        const lecturerTypes = Array.from(document.querySelectorAll('input[name="lecturerMeetingType[]"]:checked')).map(input => input.value);
        const secretaryTypes = Array.from(document.querySelectorAll('input[name="secretaryMeetingType[]"]:checked')).map(input => input.value);

        const userId = "<?= $userId ?>";

        let declineReason = '';
    if (action === 'decline') {
        declineReason = prompt("Please enter the reason for declining this request:");
        if (!declineReason) {
            alert("Decline reason is required.");
            return;
        }
    }

        fetch("<?= ROOT ?>/admin/handleRequest", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: action,
                id: userId,
                meetingTypes: generalTypes, // For roles other than lecturer/secretary
                lecturerMeetingType: lecturerTypes,
                secretaryMeetingType: secretaryTypes,
                declineReason: declineReason
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(action === 'accept' ? 'Request accepted and user added!' : 'Request declined.');
                window.location.href = '<?= ROOT ?>/admin/viewpendingRequests';
            } else {
                alert('An error occurred: ' + data.message);
            }
        });
    }
</script>
