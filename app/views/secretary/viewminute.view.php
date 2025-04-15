
<head>
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/secretary/viewsingleminute.style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="<?=ROOT?>/img.png" type="image">
    <title>Minute View</title>
</head>
<body>
<?php
    $user="secretary";
    $memocart="memocart";   //use memocart-dot if there is a memo in the cart change with db
    $notification="notification"; //use notification-dot if there's a notification
    $menuItems = [ "home" => ROOT."/secretary",$memocart => ROOT."/secretary/memocart", $notification => ROOT."/secretary/notifications", "profile" => ROOT."/secretary/viewprofile" , "logout" => ROOT."/secretary/confirmlogout"]; //pass the menu items here (key is the name of the page, value is the url)
    require_once("../app/views/components/new_navbar.php"); //call the navbar component
    require_once("../app/views/components/sec_sidebar.php"); //call the sidebar component
    $minuteDetails=$data['minuteDetails'][0];
    $attendees=$data['minuteDetails'][0]->attendence;
    $AgendaItems=$data['minuteDetails'][0]->agendaItems;
    
    
    ?>
<div class="tool-bar">

    <input type="text" placeholder="Search within this minute..." class="search-within-minute" id="search-box" oninput="searchContent()">
    <div class="btn-container">
        <button class="download-btn" onclick="trigDownload()"><i class="fas fa-download" ></i><span> Download</span></button>
        <button class="print-btn" onclick="printtrig()"><i class="fas fa-print"></i><span> Print</span></button>
        <button class="report-issue-btn"><i class="fas  fa-file-lines"></i><span>View Report</span></button>
        <button class="comment-btn"><i class="fas fa-comment"></i><span> Comments</span></button>
    </div>
</div>
<div class="minute-container" id="print-area">
    
    <h1 class="minute-heading">Minute of <?=htmlspecialchars(strtoupper($minuteDetails->meeting_type))?> Meeting on <?=htmlspecialchars(strtoupper($minuteDetails->date))?></h1>
    <div class="minute-details">
    <h1 class="sub-title">Minute Details</h1>
    <div class="detail-item">

            <p><span>Minute ID:</span> <?=htmlspecialchars($minuteDetails->Minute_id)?></p>
        </div>
        <div class="detail-item">
        <p><span>Created On:</span> <?=htmlspecialchars($minuteDetails->created_date)?> </p>
        </div>
        <div class="detail-item">
        <p><span>Created By:</span> <?=htmlspecialchars($minuteDetails->created_by)?> </p>
        </div>
        </div>
    <div class="minute-details">
    <h1 class="sub-title">Meeting Details</h1> 
    <div class="detail-item">
            <span>Meeting ID:</span> <?=htmlspecialchars(strtoupper($minuteDetails->meeting_id))?>  </p>
        </div>
        <div class="detail-item">
            <span>Meeting Type:</span> <?=htmlspecialchars(strtoupper($minuteDetails->meeting_type))?> Meeting </p>
        </div>

        <div class="detail-item">
        <p> <span>Date:</span> <?=htmlspecialchars(strtoupper($minuteDetails->date))?> </p>
        </div>
        <div class="detail-item">
        <p> <span>Time:</span> <?=htmlspecialchars(substr($minuteDetails->start_time,0,-3))." - ".htmlspecialchars(substr($minuteDetails->end_time,0,-3))?>   </p>
        </div>
        <div class="detail-item">
        <p> <span>Location:</span> <?=htmlspecialchars($minuteDetails->location)?> </p>
        </div>
        </div>

        <div class="minute-details ">
        <h1 class="sub-title">Participants</h1> 
        <div class="attendees">
            <?php
            foreach($attendees as $attendee){
                echo "<p>".$attendee->attendee."</p>";
            }
            ?>
        </div>
        </div>

        <div class="minute-details">
        <h1 class="sub-title">Agenda Items</h1>
        <div class="detail-item">
        <?php
            foreach($AgendaItems as $agenda){
                echo "<p>".$agenda->agenda_item."</p>";
            }
            ?>

        </div>



        </div>

        <div class="minute-details">
        <h1 class="sub-title">Contents</h1>
        <?php
        $contents=$data['contents'];
        if(count($contents)>0){
            foreach($contents as $content){
                echo "<div class='content-item'>";
                echo "<div class='discussed-title'>";
                echo "<p>".htmlspecialchars($content->title)."</p>";
                echo "</div>";
                echo "<div class='discussed-content'>";
                echo "<p>".html_entity_decode($content->content)."</p>";
                echo "</div>";
                echo "</div>";
            }
            if($data['isContentRestricted']){
                echo "<p class='hidden-content-note'> <i class='fas fa-angle-double-right'></i>&nbspSome contents are hidden for this user.</p>";
            }
        }
        ?>
        </div>
        <div class="minute-details">
        <h1 class="sub-title">Discussed Memos</h1>
        <div class="detail-item">
            <?php
            $memos=$data['minuteDetails'][0]->discussed_memos;
            if($memos!=null){
            if(count($memos)>0){
                foreach($memos as $memo){
                    echo "<a href='".ROOT."/secretary/viewmemodetails/?memo_id=".htmlspecialchars($memo->memo_id)."' target='_blank'><p>".htmlspecialchars($memo->memo_title)."</p></a>";
                }
            }
            }
            else{
                echo "<p class='hidden-content-note'> <i class='fas fa-angle-double-right'></i>&nbspNo Memos were discussed in this meeting.</p>";
            }
            ?>

        </div>
        </div>
        <?php 
        $linkedMinutes=$data['minuteDetails'][0]->linked_minutes;
        if($linkedMinutes!=null){
            if(count($linkedMinutes)>0){
                echo ' <div class="minute-details">
                         <h1 class="sub-title">Linked Minutes</h1>
                         <div class="detail-item">';
                foreach($linkedMinutes as $minute){
                    echo "<a href='".ROOT."/secretary/viewminute?minuteID=".htmlspecialchars($minute)."'target='_blank'><p> Minute ID : ".htmlspecialchars($minute)."</p></a>";
                }
                echo "</div>
                     </div>";
            }
        }
        $linkedMedia=$data['minuteDetails'][0]->linkedMediaFiles;
        if($linkedMedia!=null){
            if(count($linkedMedia)>0){
                echo ' <div class="minute-details">
                         <h1 class="sub-title">Linked Media</h1>
                         <div class="detail-item">';
                foreach($linkedMedia as $media){
                    echo "<a href=".$media->media_location." target='_blank'><p>".$media->Name." - ".$media->ext."</p></a>"; 
                }
                echo "</div>
                     </div>";
            }
        }

        ?>

        

</div>
<div class="footer-btns">

<button class="back-btn" onclick="window.history.back();"><span> Back</span></button>
<button class="go-top" onclick="  window.scrollTo({ top: 0, behavior: 'smooth' });">Scroll to Top</button>
</div>

<!-- <div class="chatbot">
    <div class="chatbot-icon">
        <i class="fas fa-comments"></i>
    </div>
    <div class="chatbot-window">
        <div class="chatbot-header">
            <h2>Chatbot</h2>
            <button class="close-chatbot"><i class="fas fa-times"></i></button>
        </div>
        <div class="chatbot-messages">
        </div>
        <input type="text" placeholder="Type your message..." class="chatbot-input">
    </div>
</div> -->




<script>
    function printtrig() {

    const printContents = document.getElementById('print-area').innerHTML;
    const originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    location.reload(); // optional: reload the page after printing
}
function searchContent() {
            var searchTerm = document.getElementById('search-box').value.toLowerCase();
            var content = document.getElementById('print-area');
            var paragraphs = content.getElementsByTagName('p');
            var headings = content.getElementsByTagName('h1');
            var subheadings = content.getElementsByTagName('h2');
            var subheadings2 = content.getElementsByTagName('h3');
            var subheadings3 = content.getElementsByTagName('h4');
            var subheadings4 = content.getElementsByTagName('h5');
            var subheadings5 = content.getElementsByTagName('h6');

            var paragraphs = [...paragraphs, ...headings, ...subheadings, ...subheadings2, ...subheadings3, ...subheadings4, ...subheadings5];
            
            // Clear previous highlights
            for (var i = 0; i < paragraphs.length; i++) {
                paragraphs[i].classList.remove('highlight');
            }

            // If the search term is empty, don't highlight anything
            if (searchTerm.trim() === '') {
                return;
            }
            if(searchTerm.length >= 3){
            // Loop through paragraphs to find the search term
            for (var i = 0; i < paragraphs.length; i++) {
                var text = paragraphs[i].innerText.toLowerCase();
                if (text.includes(searchTerm)) {
                    paragraphs[i].classList.add('highlight');
                }
            }
        }
        }

function trigDownload() {
    window.location.href = "<?=ROOT?>/download?minuteID=<?=$data['minuteDetails'][0]->Minute_id?>";
}
</script>
</body>
