<head>
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/secretary/createminute.style.css">
    <link href="https://cdn.ckeditor.com/ckeditor5/37.0.1/classic/theme.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <title>Create a Minute</title>
    <link rel="icon" href="<?=ROOT?>/img.png" type="image">
</head>
<body>
    <?php
    $user = "secretary";
    $memocart = "memocart";   //use memocart-dot if there is a memo in the cart change with db
    $notification = "notification"; //use notification-dot if there's a notification
    $menuItems = [
        "home" => ROOT."/secretary",
        $memocart => ROOT."/secretary/memocart",
        $notification => ROOT."/secretary/notifications",
        "profile" => ROOT."/secretary/viewprofile"
    ];
    require_once("../app/views/components/new_navbar.php");
    require_once("../app/views/components/sec_sidebar.php");
    $draftStatus=$data['minuteDraft'][0]->is_present;
    $isPrev=0 ;
    $previousMeetingId=0;

     
    ?>
    <!-- Loading Overlay -->
      <div id="loadingOverlay">
        <div class="spinner"></div>
    </div>

    <div class="load-draft-msg" id="loadDraftPopup">
    <div class="load-draft-content">
    <i class="fa-solid fa-file-import"></i>
        <h1>Draft Minute Found</h1>
        <p>Do you want to load the draft minute?</p>
        <button id="loadDraftBtn" onclick="handleLoadDraft()">Load Draft</button>
        <button id="discardDraftBtn" onclick="handleCancelDraft()">Cancel</button>
    </div>
</div>

    <div class="tab-container">
                <div class="tab active" data-tab="meeting-details">Meeting Details</div>
                <div class="tab" data-tab="minute-content">Minute Content</div>
                <div class="tab" data-tab="attachments">Attachments</div>
                <div class="draft-btns">
                <button class="load-draft-btn" id="autoSaveBtn" onclick="handleAutoSave()">
                <i class="fa-solid fa-toggle-on"></i> &nbsp; Auto Save is On
            </button>
            <button class="save-draft-btn" onclick="saveDraft()">
                <i class="fas fa-save"></i> &nbsp; Save to Drafts
            </button>

            </div>

            </div>
    <div class="minute-form-container">
        <form action="<?=ROOT?>/secretary/submitminute" method="post" id="minuteForm" enctype="multipart/form-data">
            <?php
            $memoCount=0;
            $meetingMembers=$data['participants'];
            $memos=$data['memos'];
            $minuteList=$data['minutes'];
            $departments=$data['departments'];
            $meetingDetails=$data['meetingDetails'];
            $AgendaItems=$data['agendaItems'];

            if($AgendaItems==null){
                $AgendaItems=[];
            }
            if($memos==null){
                $memos=[];
            }
            else{
                $memoCount=count($memos);
            }
            if($fwdmemos==null){
                $fwdmemos=[];
            }
            else{
                $memoCount+=count($fwdmemos);
            }
            ?>
            <input type="hidden" name="meetingID" value="<?= $meetingId ?>">
            <input type="hidden" name="minuteTitle" value="<?= $meetingId ?>-<?= strtoupper($meetingDetails[0]->meeting_type) ?> Meeting ">
            
            <!-- Page 1 -->
            <div class="minute-page minute-page-1">
                <div class="sub-container meeting-details">
                    <h1 class="form-sub-title">Meeting Details</h1>
                    <div class="row-A">
                        <div class="col">
                            Meeting  <span style="color:var(--primary-color); margin-left:95px" ><?= $meetingId ?> - <?= strtoupper($meetingDetails[0]->meeting_type) ?> Meeting </span>
                            
                        </div>

                        <div class="col">
                            <label for="location">Location of the meeting</label>
                            <input type="text" name="location" id="location" value="<?= $meetingDetails[0]->location ?>" readonly>
                        </div>  
                    </div>
                    <div class="row-B">
                        <div class="col">
                            <label for="meeting-date">Date</label>
                            <input type="date" name="meeting-date" id="meeting-date" value="<?= $meetingDetails[0]->date ?>" readonly>
                        </div>
                        <div class="col">
                            <label for="meeting-start-time">Meeting Started Time</label>
                            <input type="time" name="meeting-start-time" id="meeting-start-time" value="<?= $meetingDetails[0]->start_time ?>" readonly >
                            <label for="meeting-end-time" >Meeting Ended Time</label>
                            <input type="time" name="meeting-end-time" id="meeting-end-time" value="<?= $meetingDetails[0]->end_time ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="sub-container meeting-details">
                    <h1 class="form-sub-title">Approval of Previous Minute</h1>
                    
                    <?php
                    if(isset($data['recentMinute']) && is_array($data['recentMinute']) && count($data['recentMinute']) > 0): ?>
                        

                   <?php $prevMin=$data['recentMinute'][0];
                    $isPrev=1 ;
                    $previousMeetingId=$prevMin->meeting_id;
                    
                    ?>

                    
            
                    <div class="previous-minute-section">

                    <div class="minute-content-box" id="previousMinuteContent">
                        <p><strong>Meeting :</strong> <?=$prevMin->title?> </p>
                        <p><strong>Meeting Date:</strong> <?=$prevMin->date?> </p>
                        <p><strong>Minute Created Date:</strong> <?=$prevMin->created_date?></p>

                    <div class="pdf-link">
                        <a href="<?=ROOT?>/secretary/viewminute?minuteID=<?=$prevMin->Minute_ID?>" target="_blank"> Click here to view the minute</a>
                    </div>
                    </div>

                    <input type="hidden" name="previousMinute" id="previousMinuteID" value="<?=$prevMin->Minute_ID?>">

                    <div class="radio-group">
                        <label>
                            <input type="radio" name="previousMinuteStatus" id='acceptRadioBtn' value="accept">
                            Previous Minute was accepted in this Meeting
                        </label>
                    </div>

                    <div class="radio-group">
                        <label>
                            <input type="radio" name="previousMinuteStatus" id='rejectRadioBtn' value="reject">
                            Previous Minute is not accepted and it is recorrected
                        </label>
                        <button type="button" class="recorrect-button" onclick="recorrectMinute()">Recorrect Minute</button>
                    </div>

                </div>
                <?php else: ?>
                    <?php $previousMeetingId=0; ?>
                    <div class="previous-minute-section">
                        <p>No previous minute found for this meeting.</p>
                    </div>

                <?php endIf; ?>
                    

                </div>



            </div>
            
            <!-- Page 2 -->
            <div class="minute-page minute-page-2" style="display: none;">
                <div class="sub-container minute-content">
                    <h1 class="form-sub-title">Minute Content</h1>
                    <div id="content-sections">
                        <!-- Dynamic content sections will be displayed here -->
                    </div>
                    <button type="button" class="add-button" onclick="addContentSection()">+ Add Section</button>
                </div>
                
            </div>

<!-- Page 3 -->
            <div class="minute-page minute-page-3" style="display: none;">

                <div class="sub-container memo-linking">
                    <h1 class="form-sub-title">Link Memos</h1>
                    <div id="memoSections">
                     <table>
                        <tr>
                            <th>Memo</th>
                            <th>Discussed</th>
                            <th>Under Discussion</th>
                            <th>Parked</th>
                        </tr>
                        <?php foreach($memos as $memo){
                            echo "<tr id='row-".$memo->memo_id."'>
                            <td>$memo->memo_id - $memo->memo_title</td>
                            <td><input type='checkbox' class='rowID-".$memo->memo_id."'name='discussed[]' value='$memo->memo_id' onClick='toggleCheckBox(".$memo->memo_id.",this)'> </td>
                            <td><input type='checkbox' class='rowID-".$memo->memo_id."'name='underdiscussion[]' value='$memo->memo_id' onClick='toggleCheckBox(".$memo->memo_id.",this)' ></td>
                            <td><input type='checkbox' class='rowID-".$memo->memo_id."'name='parked[]' value='$memo->memo_id' onClick='toggleCheckBox(".$memo->memo_id.",this)' ></td>";}?>

                            <?php foreach($fwdmemos as $memo){
                            echo "<tr id='row-".$memo->memo_id."'>
                            <td>$memo->memo_id - $memo->memo_title</td>
                            <td><input type='checkbox' class='rowID-".$memo->memo_id."'name='discussed[]' value='$memo->memo_id' onClick='toggleCheckBox(".$memo->memo_id.",this)'> </td>
                            <td><input type='checkbox' class='rowID-".$memo->memo_id."'name='underdiscussion[]' value='$memo->memo_id' onClick='toggleCheckBox(".$memo->memo_id.",this)' ></td>
                            <td><input type='checkbox' class='rowID-".$memo->memo_id."'name='parked[]' value='$memo->memo_id' onClick='toggleCheckBox(".$memo->memo_id.",this)' ></td>";}?>
                     </table>
                     </div>
                </div>

                <div class="sub-container minute-Linking">
                    <h1 class="form-sub-title">Link Previous Minutes (If applicable)</h1>
                    <div id="LinkedminuteSection">
                        <?php if($data['linkedMinutes']!=null){
                            echo "<div class='autolink-div'<p class='autolink'>Following minutes will be automatically linked to this minute</p>";
                            foreach($data['linkedMinutes'] as $minute){
                                
                                echo "<p class='autolink-minute'>"."<a href='".ROOT."/secretary/viewminute?minuteID=".$minute->Minute_ID."' target='_blank'> Minute with ID - ".$minute->Minute_ID." "."</a></p>";
                            }
                            echo "</div>";
                        }?>
                        <select name="LinkedMinutes[]" id="LinkedMinutes">
                            <option value="none">Select Linked Minutes</option>
                            <?php foreach($minuteList as $minute){
                                echo "<option value='$minute->Minute_id'>$minute->title - $minute->type Meeting - $minute->date </option>";
                            }?>
                        </select>
                    </div>
                     <button type="button" id="addLinkedMinuteBtn" onclick="addAnotherMinute()">Link Another Minute</button>  
                </div>

                <div class="sub-container media-Linking">
                    <h1 class="form-sub-title">Link Media Files (If any)</h1>
                    <p>Choose Files or Drag and Drop to the Below Space</p>
                    <div id="Link-Media">
                        <input type="file" name="media[]" id="media" multiple> 
                    </div>
                </div>

                <div class="sub-container keyword-add">
                    <h1 class="form-sub-title">Add some keywords for search purposes (Optional)</h1>
                    <div class="keyword-container">
                    <div class="keyword-section">
                        <div class="keyword-input">
                            
                        <input type="text" name="keywordlist[]" id="keyword">  
                        </div>
                    </div>
                        <button type="button" id="addkeyword" onclick="addAnotherKeyword()">+</button>
                    </div>
                    </div>
               

                
            </div>

            <!-- Navigation buttons -->
            <div class="form-navigation">
                <button type="button" id="backBtntoP1" style="display: none;">Back</button>
                <button type="button" id="backBtntoP2" style="display: none;">Back</button>
                <button type="button" id="nextBtntoP2">Next</button>
                <button type="button" id="nextBtntoP3" style="display: none;">Next</button>
                <button type="submit" id="submitBtn" style="display: none;">Submit</button>
            </div>
        </form>
    <? $isPrev=0;?>

    </div>
    <script >
        const draftStatus = <?= $draftStatus ?>;
        const fetchURL = "<?=ROOT?>/secretary/inputDraftData";
        const fetchGetURL = "<?=ROOT?>/secretary/getDraftData";
        const meetingId = <?= $meetingId ?>;
        const username = "<?=$_SESSION['userDetails']->username?>";
        const options = <?php echo json_encode($departments); ?>;
        const meetingType = <?php echo json_encode($data['meetingType']); ?>;
        const form=document.getElementById("minuteForm");
        const users=<?php echo json_encode($meetingMembers); ?>;
        let isPrev=<?=$isPrev?>;
        const previousMeetingId=<?=$previousMeetingId?>;
        if(isPrev===1){
        const prevMinuteID=<?=$prevMin->Minute_ID?>;
        const rejectRadioBtn = document.getElementById('rejectRadioBtn');
        rejectRadioBtn.addEventListener('click', function() {
             
          
            const selected = document.querySelector('input[name="previousMinuteStatus"]:checked');
            if (selected && selected.value === 'reject') {

                document.querySelector('.recorrect-button').style.display = 'inline-block';
                Swal.fire({
                    text: "You have to recorrect the previous minute before proceeding, Your current content will be saved as a draft, and you'll be redirected to the recreation page.",
                    icon: "warning",
                    confirmButtonText: "OK",
                    confirmButtonColor: "#3b82f6",
                    customClass: {
                        popup: "warning-font"
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.open("<?=ROOT.'/'?>secretary/recorrectminute?meeting="+previousMeetingId+"&prevMin="+prevMinuteID, "_blank");
                }
                });
            }

            
   
        });
        const acceptradioBtn = document.getElementById('acceptRadioBtn');
        acceptradioBtn.addEventListener('click', function() {
            document.querySelector('.recorrect-button').style.display = 'none';
        });
    }


        function addAnotherKeyword(){
            const keywordInput = document.getElementsByClassName("keyword-section")[0];
            const inputbox = document.createElement("div");
            inputbox.classList.add("keyword-input");
            const input = document.createElement("input");
            input.type="text";
            input.name="keywordlist[]";
            input.id="keyword";
            const btn=document.createElement("button");
            btn.innerHTML="X";
            btn.type="button";
            btn.id="keywordClose";
            btn.onclick=function(){
                this.parentElement.remove();
            }
            btn.classList.add("closeBtn");
            inputbox.appendChild(input);
            inputbox.appendChild(btn);
            keywordInput.appendChild(inputbox);

        }

        //dynamically add input selects
        function addAnotherMinute(){
            const select = document.getElementById("LinkedMinutes");
            const newSelect = select.cloneNode(true);
            newSelect.id = "";
            const closeBtn=document.createElement("button");
            closeBtn.innerHTML="X";
            closeBtn.classList.add("closeBtn");
            closeBtn.type="button";
            closeBtn.onclick=function(){
                if(confirm("Are you sure you want to remove this linked minute?")){
                    newSelect.remove();
                    closeBtn.remove();
                }
            }
            document.getElementById("LinkedminuteSection").appendChild(newSelect);
            document.getElementById("LinkedminuteSection").appendChild(closeBtn);

        }

        document.getElementById("minuteForm").addEventListener("submit", function(e){
            e.preventDefault();
            //meeting details
            const meetingID= <?= $meetingId ?>;
            const memoCount= <?=$memoCount ?>;
    


            const DiscussedMemos=[]; //discussed memos
            const underDiscussionMemos=[]; //under discussion memos
            const parkedMemos=[]; //parked memos
            const LinkedMinuteList=[]; //linked minutes
            const mediaArr=[]; //media files
            let sectionsData = []; // content sections


            
            //agenda details
            if(isPrev==1){
                const selectedRadio = document.querySelector('input[name="previousMinuteStatus"]:checked');
                if(!selectedRadio){
                    Swal.fire({
                        text: "Select the status of the previous minute",
                        icon: "warning",
                        confirmButtonText: "OK",
                        confirmButtonColor: "#3b82f6",
                        customClass: {
                            popup: "warning-font"
                        }
                    });
                    return;
                }
            }
            
            

            //minute contents
            let Contenterror=false;
            const contentSections = document.querySelectorAll('.content-section');
            contentSections.forEach((section, index) =>{
                let forwardDepartments=[];
                const title = section.querySelector('.title-input').value;
                const selectedRadio = section.querySelector(`input[name="options-${index+1}"]:checked`);
                const selectedRadioValue = selectedRadio ? selectedRadio.value : '';
                const editorInstance = editors.find(e => e.titleInput === section.querySelector('.title-input'));
                const insertedcontent = editorInstance ? editorInstance.editor.getData() : '';
                const forwardDeps = section.querySelectorAll(`input[name="forwardDep[]"]:checked`);
                if(title==""|| title=="You can type content title here" || insertedcontent==""||insertedcontent=="<p>This is <strong>sample</strong> content with <i>italic</i> text with formatting.</p><p><br><br><br><br>&nbsp;</p><p>Click add more to add another content.</p>"){
                    Swal.fire({
                    text: "Fill all the content sections",
                    icon: "warning",
                    confirmButtonText: "OK",
                    confirmButtonColor: "#3b82f6",
                    customClass: {
                        popup: "warning-font"
                    }
                    });
                    document.getElementsByClassName("minute-content")[0].style.border="1px solid red";
                    Contenterror=true;
                    return;
                }
                else{
                    if(forwardDeps){
                    forwardDeps.forEach(dep=>{
                        forwardDepartments.push(dep.value);
                    });
                }

                    const sectionId = index + 1;
                    const selectedRestrictions = sectionRestrictions[sectionId] || [];
                    document.getElementsByClassName("minute-content")[0].style.border="0.5px solid #bcbcbc";
                    sectionsData.push({
                    insertedcontent,
                    selectedRadioValue,
                    title,
                    selectedRestrictions,
                    forwardDepartments
                });
                    Contenterror=false;
                }

            });




            // memo
            const discussed = document.querySelectorAll('input[name="discussed[]"]:checked');
            const underdiscussion = document.querySelectorAll('input[name="underdiscussion[]"]:checked');
            const parked = document.querySelectorAll('input[name="parked[]"]:checked');
            discussed.forEach(memo=>{
                DiscussedMemos.push(memo.value);
            });
            underdiscussion.forEach(memo=>{
                underDiscussionMemos.push(memo.value);
            });
            parked.forEach(memo=>{
                parkedMemos.push(memo.value);
            });
            const markedMemos = DiscussedMemos.length+underDiscussionMemos.length+parkedMemos.length;
            if(markedMemos!=memoCount){
                Swal.fire({
                    text: "Mark the state of all the memos",
                    icon: "warning",
                    confirmButtonText: "OK",
                    confirmButtonColor: "#3b82f6",
                    customClass: {
                        popup: "warning-font"
                    }
                    });             
                    document.getElementsByClassName("memo-linking")[0].style.border="1px solid red";
                    return;
            }
            else{
                document.getElementsByClassName("memo-linking")[0].style.border="0.5px solid #bcbcbc";
            }

            
            //linked minutes
            const linkedMinutes=document.querySelectorAll('select[name="LinkedMinutes[]"]');
            linkedMinutes.forEach(minute=>{
                if(minute.value!="none" && !LinkedMinuteList.includes(minute.value)){   
                    LinkedMinuteList.push(minute.value);
                }
            });

            //linked media files
            const fileInput = document.getElementById("media");
            for(const file of fileInput.files){
                mediaArr.push(file);

            }

            const sectionHiddenInput= document.createElement('input');
            sectionHiddenInput.type = 'hidden';
            sectionHiddenInput.name = 'sections'; 
            sectionHiddenInput.value = JSON.stringify(sectionsData); 
            form.appendChild(sectionHiddenInput);
            const minutesHiddenInput= document.createElement('input');
            minutesHiddenInput.type = 'hidden';
            minutesHiddenInput.name = 'Linkedminutes';
            minutesHiddenInput.value = JSON.stringify(LinkedMinuteList);
            form.appendChild(minutesHiddenInput);

            if(!Contenterror){
                
                if(confirm("Are you sure you want to submit the minute?")){
                    document.getElementById('loadingOverlay').style.visibility = 'visible';
                    form.submit();
                }
            }



        });
        </script>
    <script src="https://cdn.ckeditor.com/ckeditor5/37.0.1/classic/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script src="<?=ROOT?>/assets/js/secretary/createminute.script.js"></script>
</body>