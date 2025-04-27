

<?php
// Assume $meetingData is available

$meetingData=$data['meetingDetails'];
// show($meetingData );
// Extract data safely
$meetingInfo = $meetingData[0][0] ?? null;
$attendees = $meetingData[1] ?? [];
$agendas = $meetingData[2] ?? [];
$memos = $meetingData[3] ?? [];
$minutes = $meetingData[4] ?? [];
?>

<head>
    <meta charset="UTF-8">
    <title>Meeting Report</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/viewmeetingreport.style.css"> 
    <link rel="icon" href="<?=ROOT?>/img.png" type="image">
   
</head>
<body>
<?php
       $userType=($_SESSION['userDetails']->role);
       $user = $userType;
       $notification = "notification";
       $menuItems = [
           "home" => ROOT . "/".$user,
           $notification => ROOT . "/".$user."/notifications",
           "profile" => ROOT . "/".$user."/viewprofile"
       ];
   
   
       echo "<div class='minute-list-navbar'>";
       require_once("../app/views/components/new_navbar.php");
       echo "</div>";
       switch ($userType) {
           case 'secretary':
               require_once("../app/views/components/sec_sidebar.php");
               break;
           case 'lecturer':
               require_once("../app/views/components/lec_sidebar.php");
               break;
           case 'studentrep':
               require_once("../app/views/components/std_sidebar.php");
               break;
            default:
                require_once("../app/views/components/admin_sidebar.php");
                break;
       }

       ?>
<div class="report-container">
    <h2 class="report-title">Meeting Report</h2>
    <h4>Meeting Details</h4>

    <table>
        <tr>
            <td><strong>Date:</strong></td>
            <td><?= $meetingInfo ? htmlspecialchars($meetingInfo->date) : 'N/A'; ?></td>
            <td><strong>Start Time:</strong></td>
            <td><?= $meetingInfo ? htmlspecialchars($meetingInfo->start_time) : 'N/A'; ?></td>
        </tr>
        <tr>
            <td><strong>End Time:</strong></td>
            <td><?= $meetingInfo ? htmlspecialchars($meetingInfo->end_time) : 'N/A'; ?></td>
            <td><strong>Location:</strong></td>
            <td><?= $meetingInfo ? htmlspecialchars($meetingInfo->location) : 'N/A'; ?></td>
        </tr>
        <tr>
            <td><strong>Meeting Type:</strong></td>
            <td><?= $meetingInfo ? htmlspecialchars($meetingInfo->meeting_type) : 'N/A'; ?></td>
            <td><strong>Additional Note:</strong></td>
            <td><?= $meetingInfo ? htmlspecialchars($meetingInfo->additional_note) : 'N/A'; ?></td>
        </tr>
    </table>

    <hr>

    <h4>Attendees</h4>
    <?php if (!empty($attendees)): ?>
        <ul>
            <?php foreach ($attendees as $attendee): ?>
                <li><?= htmlspecialchars($attendee->attendee); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No attendees available.</p>
    <?php endif; ?>

    <hr>

    <h4>Agenda Items</h4>
    <?php if (!empty($agendas)): ?>
        <ul>
            <?php foreach ($agendas as $agenda): ?>
                <li><?= htmlspecialchars($agenda->agenda_item); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No agenda items available.</p>
    <?php endif; ?>

    <hr>

    <h4>Discussed Memos</h4>
    <?php if (!empty($memos)): ?>
        <ul>
            <?php foreach ($memos as $memo): ?>
               <a href='<?=ROOT.'/'.$_SESSION['userDetails']->role?>/viewmemodetails/?memo_id=<?=$memo->memo_id?>'> <li>Memo ID: <?= htmlspecialchars($memo->memo_id); ?></a></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No linked memos.</p>
    <?php endif; ?>

    <hr>

    <h4> Minutes of this meeting</h4>
    <?php if (!empty($minutes)): ?>
        <ul>
            <?php foreach ($minutes as $minute): ?>
              <li>  <a href='<?=ROOT.'/'.$_SESSION['userDetails']->role?>/viewminute?minuteID=<?=$minute->Minute_ID?>'>Minute ID: <?= htmlspecialchars($minute->Minute_ID); ?></a></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No linked minutes.</p>
    <?php endif; ?>
    <a href="javascript:history.back()" class="back-button">Back</a>



   
</div>
<script>
    function triggerback() {
        window.history.back();
    }
</script>

</body>
</html>