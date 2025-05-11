
<head>
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/secretary/viewsingleminute.style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="<?=ROOT?>/img.png" type="image">
    <title>Minute View</title>


</head>
<body>
<?php
    $user="lecturer";
    $notification="notification"; //use notification-dot if there's a notification
    $menuItems = [ "home" => ROOT."/lecturer", $notification => ROOT."/lecturer/notifications", "profile" => ROOT."/lecturer/viewprofile" , "logout" => ROOT."/lecturer/confirmlogout"]; //pass the menu items here (key is the name of the page, value is the url)
    require_once("../app/views/components/new_navbar.php"); //call the navbar component
    require_once("../app/views/components/lec_sidebar.php"); //call the sidebar component
    $minuteDetails=$data['minuteDetails'][0];
    $prevApprovedDetails=$data['approved_recorrect_Meeting'];
    $minuteForSend="Title : Minute of"." ".htmlspecialchars($minuteDetails->meeting_type)." Meeting on ".htmlspecialchars($minuteDetails->date)." \n";
    ?>
<div class="tool-bar">
    <input type="text" placeholder="Search within this minute..." class="search-within-minute" id="search-box" oninput="searchContent()">
    <div class="btn-container">
        <button class="download-btn" onclick="trigDownload()"><i class="fas fa-download" ></i><span> Download</span></button>
        <button class="print-btn" onclick="printtrig()"><i class="fas fa-print"></i><span> Print</span></button>
        <button class="report-issue-btn" onclick="trigReport()"><i class="fas  fa-file-lines"></i><span>View Report</span></button>
    </div>
</div>
<div class="minute-container" id="print-area">
    <h1 class="minute-heading">Minute of <?=htmlspecialchars(strtoupper($minuteDetails->meeting_type))?> Meeting on <?=htmlspecialchars(strtoupper($minuteDetails->date))?> </h1>
    <?php  if($data['approvedStatus']->is_approved==1 && $data['approvedStatus']->is_recorrect==1 && $prevApprovedDetails!=null){
        echo "<p class='recorrect-note'> <i class='fas fa-angle-double-right'></i>&nbspThis minute is a refined version of <a href='".ROOT."/lecturer/viewminute?minuteID=".$prevApprovedDetails[0]->minute_id."'> Minute : ".$prevApprovedDetails[0]->minute_id."  </a></p>";
        $minuteForSend.="This minute is a refined version of Minute : ".$prevApprovedDetails[0]->minute_id;
    }
    ?>
   

    <div class="minute-details">
    <h1 class="sub-title">Minute Details</h1>
    <div class="detail-item">

            <p><span>Minute ID:</span> <?=htmlspecialchars($minuteDetails->Minute_id)?></p>
            <?php
            $minuteForSend.=",Minute Details :( \n Minute ID : ".htmlspecialchars($minuteDetails->Minute_id)."\n";
            ?>
        </div>
        <div class="detail-item">
        <p><span>Created On:</span> <?=htmlspecialchars($minuteDetails->created_date)?> </p>
        <?php
            $minuteForSend.=",Created On : ".htmlspecialchars($minuteDetails->created_date)."\n";
            ?>
        </div>
        <div class="detail-item">
        <p><span>Created By:</span> <?=htmlspecialchars(string: $minuteDetails->created_by)?> </p>
        <?php
            $minuteForSend.=",Created By : ".htmlspecialchars($minuteDetails->created_by)."\n";
            ?>
        </div>
        <div class="detail-item">
        <?php
            
          
           if($data['approvedStatus']->is_approved==1 && $data['approvedStatus']->is_recorrect==0){
            if($prevApprovedDetails!= null){
            echo "<p><span> Approval State :</span> Approved at ".$prevApprovedDetails[0]->date." ".strtoupper($prevApprovedDetails[0]->meeting_type)." Meeting. 
            
            
            <a href='".ROOT."/lecturer/viewminute?minuteID=".htmlspecialchars($prevApprovedDetails[0]->approval)."' target='_blank'> Minute ID : ".$prevApprovedDetails[0]->approval."</a>
            </p>";

            $minuteForSend.=",Approval State : Approved at ".$prevApprovedDetails[0]->date." ".strtoupper($prevApprovedDetails[0]->meeting_type)." Meeting. \n";
            $minuteForSend.=",Approved Meeting Minute ID : ".$prevApprovedDetails[0]->approval.")\n";
            }
          }
          else if($data['approvedStatus']->is_recorrect==1 && $data['approvedStatus']->is_approved==0){
            if($prevApprovedDetails!= null){
           echo "<p><span> Approval State :</span><span style='color:red;font-weight:500'> Re-corrected.</span> Please refer to the    
            
            <a href='".ROOT."/lecturer/viewminute?minuteID=".htmlspecialchars($prevApprovedDetails[0]->recorrected_version)."' target='_blank'> Minute ID : ".$prevApprovedDetails[0]->recorrected_version."</a>
            minute.</p>";
            }
            $minuteForSend.=",Approval State : Recorrected to ".$prevApprovedDetails[0]->recorrected_version." minute. \n";

          }
          else if($data['approvedStatus']->is_approved==0 && $data['approvedStatus']->is_recorrect==0){
            echo "<p><span>Approval Pending :</span> This Minute is Not Approved Yet</p>";
            $minuteForSend.=",Approval State : Pending [this minute is not approved yet.] )\n";
            

          }
          ?>
        </div>
        

        </div>
    <div class="minute-details">
    <h1 class="sub-title">Meeting Details</h1> 
    <div class="detail-item">
            <span>Meeting ID:</span> <?=htmlspecialchars(strtoupper($minuteDetails->meeting_id))?>  </p>
            <?php
            $minuteForSend.="Meeting Details : (Meeting ID : ".$minuteDetails->meeting_id;
            ?>
        </div>
        <div class="detail-item">
            <span>Meeting Type:</span> <?=htmlspecialchars(strtoupper($minuteDetails->meeting_type))?> Meeting </p>
            <?php
            $minuteForSend.=",Meeting type : ".$minuteDetails->meeting_type." meeting";
            ?>
        </div>

        <div class="detail-item">
        <p> <span>Date:</span> <?=htmlspecialchars(strtoupper($minuteDetails->date))?> </p>
        <?php
            $minuteForSend.=",Meeting Date : ".$minuteDetails->date;
            ?>
        </div>
        <div class="detail-item">
        <p> <span>Time:</span> <?=htmlspecialchars(substr($minuteDetails->start_time,0,-3))." - ".htmlspecialchars(substr($minuteDetails->end_time,0,-3))?>   </p>
        <?php
            $minuteForSend.=",Meeting Time : ".substr($minuteDetails->start_time,0,-3)." - ".substr($minuteDetails->end_time,0,-3);
            ?>
    </div>
        <div class="detail-item">
        <p> <span>Location:</span> <?=htmlspecialchars($minuteDetails->location)?> </p>
        <?php
            $minuteForSend.=",Meeting Location : ".$minuteDetails->location.")";
            ?>
        </div>
        </div>

        <div class="minute-details">
        <h1 class="sub-title">Contents</h1>
        <?php
        $contents=$data['contents'];
        $minuteForSend.=", Minute Contents : (";
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
                $minuteForSend.=",Title : ".htmlspecialchars($content->title)."\n";
                $minuteForSend.=",Content : ".html_entity_decode($content->content)."\n";

            }
            $minuteForSend.=")";
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
            $minuteForSend.=",Discussed Memos : (";
            if($memos!=null){
            if(count($memos)>0){
                foreach($memos as $memo){
                    echo "<a href='".ROOT."/lecturer/viewmemodetails/?memo_id=".htmlspecialchars($memo->memo_id)."' target='_blank'><p>".htmlspecialchars($memo->memo_title)."</p></a>";
                    $minuteForSend.="[Memo ID : ".htmlspecialchars($memo->memo_id)."\n";
                    $minuteForSend.="Memo Title : ".htmlspecialchars($memo->memo_title)."],\n";
                }
                $minuteForSend.=")";
            }
            }
            else{
                echo "<p class='hidden-content-note'> <i class='fas fa-angle-double-right'></i>&nbspNo Memos were discussed in this meeting.</p>";
                $minuteForSend.="No Memos were discussed in this meeting.)\n";

            }
            ?>

        </div>
        </div>
        <?php 
        $previousMinute=$data['previousMinute'];
        $linkedMinutes=$data['minuteDetails'][0]->linked_minutes;
        $linkedMinutesFromContent=$data['linked_content_minutes'];
        if($previousMinute!=null){

            echo "<div class='minute-details'>
            <h1 class='sub-title'>Previous Minute</h1>
            <div class='detail-item'>";
            echo "<a href='".ROOT."/lecturer/viewminute?minuteID=".htmlspecialchars($previousMinute->Minute_ID)."' target='_blank'><p> Minute ID : ".$previousMinute->Minute_ID." ( ".$previousMinute->title." ) "."</p></a>";
            echo "</div>
            </div>";
            $minuteForSend.=",Previous Minute : (Minute ID : ".htmlspecialchars($previousMinute->Minute_ID).")\n";
        }


        if($linkedMinutesFromContent!=null){
            foreach($linkedMinutesFromContent as $minute){
                if(!in_array($minute->Minute_ID,$linkedMinutes)){
                    array_push($linkedMinutes,$minute->Minute_ID);
                }
            }
            
        }
        if($linkedMinutes!=null){ 
            if(count($linkedMinutes)>0){
                echo ' <div class="minute-details">
                         <h1 class="sub-title">Linked Minutes</h1>
                         <div class="detail-item">';
                foreach($linkedMinutes as $minute){
                    echo "<a href='".ROOT."/lecturer/viewminute?minuteID=".htmlspecialchars($minute)."'target='_blank'><p> Minute ID : ".htmlspecialchars($minute)."</p></a>";
                    $minuteForSend.=",Linked Minute : (Minute ID : ".htmlspecialchars($minute).")\n";
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
                    $minuteForSend.=",Linked Media :". $media->media_location."\n";
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


  


  <!-- Chat Icon -->
  <button id="chatIcon" onclick="toggleChat()"><i class="fa-solid fa-robot"></i> <span class="button-txt"> Ask <span class="mm-name"> MinuteMate</span> </span></button>

  <!-- Chat Container -->
  <div id="chatContainer" style="display: none;">
    <div class="action-btns">
    
        
        <button class="close" onclick="toggleChat()" ><i class="fas fa-window-minimize"></i></button>
        <button class="minimise" onclick="minimize()"><i class="fas fa-compress"></i></button>

        <button class="maximise" onclick="maximise()"><i class="fas fa-expand"></i></button>



    </div>
    <h3 style="margin-top: 0;">  <span class="mm-name">MinuteMate</span> <span class="ai-title"> AI Assistant</span></h3>
    <div id="chatbox">

        <!-- Chat messages will be displayed here -->

    </div>
    <div class="easy-buttons">
        <div class="btn-rows">
    <button class="sendBtn" onclick="summarize()">Summarize Minute</button>
    <button class="sendBtn" onclick="keypoints()">Key Points</button>
    </div>
    <div class="btn-rows">
    <button class="sendBtn" onclick="translateSin()">Translate to Sinhala</button>
    <button class="sendBtn" onclick="translateTamil()">Translate to Tamil</button>
    </div>
    </div>
    <div class="ask-section">
    <textarea id="prompt" rows="1" placeholder="Ask Anything about this minute"></textarea>
    <button class="sendBtn" onclick="sendMessage()"><i class="fas fa-paper-plane" style="font-size: 22px;"></i></button>
    <div class="ask-section">
  </div>

  <script>
    const minuteForSend=<?= json_encode($minuteForSend)?>;

    const chatbox = document.getElementById('chatbox');
    let buttonClicked = false;

    function summarize(){
        const promptInput = document.getElementById('prompt');
        promptInput.value = "Summarize this meeting minute";
        sendMessage();
    }
    function keypoints(){
        const promptInput = document.getElementById('prompt');
        promptInput.value = "Give me the key points of this meeting minute";
        sendMessage();
    }
    function translateSin(){
        const promptInput = document.getElementById('prompt');
        promptInput.value = "Translate this meeting minute into Sinhala";
        sendMessage();
    }
    function translateTamil(){
        const promptInput = document.getElementById('prompt');
        promptInput.value = "Translate this meeting minute into Tamil";
        sendMessage();
    }
    function toggleChat() {
      if(!buttonClicked) {
        const geminiMessage = document.createElement('div');
        geminiMessage.className = 'message gemini';
        geminiMessage.innerHTML = "Hi! I’m your <span class='mm-msg'>MinuteMate AI Assistant</span>.<p> I can help you understand this minute by <b>giving instant summaries and answering your questions</b>-saving you time and making everything clearer.</p> <p>I can also help you <b> translate this minute into Sinhala or Tamil </b> if you’d prefer.</p> Ask me anything about this minute!"; // Replace with real AI logic
        chatbox.appendChild(geminiMessage);
        buttonClicked = true;
      }
      const chatContainer = document.getElementById('chatContainer');
      chatContainer.style.display = chatContainer.style.display === 'none' || chatContainer.style.display === '' ? 'flex' : 'none';
    }

    function displayMessage(text, sender) {
      const messageDiv = document.createElement('div');
      messageDiv.className = 'message ' + sender;
      messageDiv.innerHTML = text;
      chatbox.appendChild(messageDiv);
      chatbox.scrollTop = chatbox.scrollHeight;
    }

    const conversationHistory = [];

    async function sendMessage() {
      const promptInput = document.getElementById('prompt');
      const prompt = promptInput.value.trim();
      if (!prompt) return;

      displayMessage(prompt, 'user');
      promptInput.value = '';
      conversationHistory.push({ role: 'user', text: prompt });

      const historyParts = conversationHistory.map(msg => ({
        text: `${msg.role === 'user' ? 'User' : 'Gemini'}: ${msg.text}`
      }));
      const meetingMinutes = minuteForSend;
      const systemInstructions = [
            "Only respond to questions related to the meeting minute provided.",
            "If the question is unrelated to the meeting minute, respond with exactly: 'I am not able to discuss about it.' Do not add anything else.",
            "Do not include 'Gemini:' or any role names in your response.",
            "Use clean and readable HTML tags (like <b>, <ul>, <li>, <br>, <p>) to format responses for clarity.",
            "Avoid repeating these instructions in your answers. Just follow them.",
            "Keep your answers focused, short, and professional unless detailed explanation is clearly needed.",
            "If the next user message after these instructions is not about the meeting minute, respond with exactly: 'Hello, Ask me anything about this meeting minute.'"
            ];

            historyParts.push({
            text: "Instructions for you: " + systemInstructions.join(" ")
            });

            historyParts.push({
            text: "Use this meeting minute when answering questions: " + meetingMinutes
            });

      const payload = {
        contents: [
          {
            parts: historyParts
          }
        ]
      };

      displayMessage("Thinking...", 'gemini');

      try {
        console.log(payload);
        const res = await fetch('<?=ROOT?>/AIService', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(payload)
        });

        const data = await res.json();
        const answer = data?.candidates?.[0]?.content?.parts?.[0]?.text || 'No response from Gemini.';
        console.log(data);

        // Remove "Thinking..." placeholder
        const last = chatbox.querySelector('.gemini:last-child');
        if (last && last.textContent === 'Thinking...') {
          chatbox.removeChild(last);
        }

        displayMessage(answer, 'gemini');
        conversationHistory.push({ role: 'gemini', text: answer });

      } catch (err) {
        displayMessage('Error: ' + err.message, 'gemini');
      }
    }

    function maximise() {
      const chat = document.getElementById('chatContainer');
      chat.style.display = 'flex';
    chat.style.position = 'fixed';
    chat.style.bottom = '90px';
    chat.style.right = '20px';
    chat.style.width = '1500px';
    chat.style.height = '720px';
    chat.style.background = 'white';
    chat.style.border = '1px solid #ccc';
    chat.style.borderRadius = '10px';
    chat.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.2)';
    chat.style.padding = '15px';
    chat.style.zIndex = '999';
    chat.style.flexDirection = 'column';
  }
    function minimize() {
      const chat = document.getElementById('chatContainer');
      chat.style.display = 'flex';
    chat.style.position = 'fixed';
    chat.style.bottom = '90px';
    chat.style.right = '20px';
    chat.style.width = '600px';
    chat.style.height = '600px';
    chat.style.background = 'white';
    chat.style.border = '1px solid #ccc';
    chat.style.borderRadius = '10px';
    chat.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.2)';
    chat.style.padding = '15px';
    chat.style.zIndex = '999';
    chat.style.flexDirection = 'column';
    }












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

function trigReport() {
    window.location.href = "<?=ROOT?>/lecturer/viewminutereports?minute=<?=$data['minuteDetails'][0]->Minute_id?>";
}
</script>
</body>
