<head>
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/secretary/createminute.style.css">
    <link href="https://cdn.ckeditor.com/ckeditor5/37.0.1/classic/theme.css" rel="stylesheet">
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
    ?>
    
    <div class="tab-container">
                <div class="tab active" data-tab="meeting-details">Meeting Details</div>
                <div class="tab" data-tab="minute-content">Minute Content</div>
                <div class="tab" data-tab="attachments">Attachments</div>
            <!-- <button class="save-draft-btn">
            <i class="fas fa-times"></i> Save To Drafts
            </button> -->

            </div>
    <div class="minute-form-container">
        <form action="<?=ROOT?>/secretary/submitminute" method="post" id="minuteForm">
            <?php
            $memoCount=0;
            $meetingMembers=$data['participants'];
            $memos=$data['memos'];
            $minuteList=$data['minutes'];
            $departments=$data['departments'];
            $meetingDetails=$data['meetingDetails'];
            if($memos==null){
                $memos=[];
            }
            else{
                $memoCount=count($memos);
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

                <div class="sub-container attendence-section">
                    <h1 class="form-sub-title">Meeting Attendence</h1>
                    <div class="attendence-mark-section">
                    <?php foreach($meetingMembers as $member){
                       echo "<div class='attendence-item'>
                            <input type='checkbox' name='attendence[]' value='$member->username' id='$member->username'>
                            <label for='$member->username' class='member'>$member->name</label>
                        </div>";
                    }?>
                </div>
                </div>
                
                <div class="sub-container agenda-details">
                    <h1 class="form-sub-title">Agenda Details</h1>

                    <div id="agendaContainer" class="agendaContainer">
                        <div class="input-container">
                            <input type="text" name="Agenda[]" placeholder="Enter the Agenda Item here" />
                        </div>
                    </div>
                    <button type="button" id="addMoreBtn">Add Another Item</button>
                    
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
                     </table>
                     </div>
                </div>

                <div class="sub-container minute-Linking">
                    <h1 class="form-sub-title">Link Previous Minutes (If applicable)</h1>
                    <div id="LinkedminuteSection">
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
    </div>
    <script >

        const options = <?php echo json_encode($departments); ?>;
        const meetingType = <?php echo json_encode($data['meetingType']); ?>;
        const users = <?php echo json_encode($data['participants']); ?>;
        const form=document.getElementById("minuteForm");

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


            const attendenceList=[] ; //meeting attendence list
            const agendaList=[]; //agenda list
            const DiscussedMemos=[]; //discussed memos
            const underDiscussionMemos=[]; //under discussion memos
            const parkedMemos=[]; //parked memos
            const LinkedMinuteList=[]; //linked minutes
            const mediaArr=[]; //media files
            let sectionsData = []; // content sections


            const attendence = document.querySelectorAll('input[name="attendence[]"]:checked');
            attendence.forEach(attendee=>{
                attendenceList.push(attendee.value);
            });
            if(attendenceList.length==0){
                Swal.fire({
                    text: "Select the attendence",
                    icon: "warning",
                    confirmButtonText: "OK",
                    confirmButtonColor: "#3b82f6",
                    customClass: {
                        popup: "warning-font"
                    }
                    });
                
                document.getElementsByClassName("attendence-section")[0].style.border="1px solid red";
                return;
            }
            else{
                document.getElementsByClassName("attendence-section")[0].style.border="0.5px solid #bcbcbc";
            }
            //agenda details
            const agendaItems = document.querySelectorAll('input[name="Agenda[]"]');
            const agendaItemcount=agendaItems.length;
            agendaItems.forEach(agenda=>{
                if(agenda.value==""){
                    Swal.fire({
                    text: "Fill all the agenda items",
                    icon: "warning",
                    confirmButtonText: "OK",
                    confirmButtonColor: "#3b82f6",
                    customClass: {
                        popup: "warning-font"
                    }
                    });   
                    document.getElementsByClassName("agenda-details")[0].style.border="1px solid red";
                    return;
                }
                agendaList.push(agenda.value);
            });

            if(agendaList.length==0 || agendaList.length!=agendaItemcount){
                document.getElementsByClassName("agenda-details")[0].style.border="1px solid red";
                return;
            }
            else{
                document.getElementsByClassName("agenda-details")[0].style.border="0.5px solid #bcbcbc";
            }

            //minute contents
            let Contenterror=false;
            const contentSections = document.querySelectorAll('.content-section');
            contentSections.forEach((section, index) =>{
                const title = section.querySelector('.title-input').value;
                const selectedRadio = section.querySelector(`input[name="options-${index+1}"]:checked`);
                const selectedRadioValue = selectedRadio ? selectedRadio.value : '';
                const selectedDepartment = section.querySelector('.select-dropdown').value;
                const editorInstance = editors.find(e => e.titleInput === section.querySelector('.title-input'));
                const insertedcontent = editorInstance ? editorInstance.editor.getData() : '';
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
                    const sectionId = index + 1;
                    const selectedRestrictions = sectionRestrictions[sectionId] || [];
                    document.getElementsByClassName("minute-content")[0].style.border="0.5px solid #bcbcbc";
                    sectionsData.push({
                    insertedcontent,
                    selectedRadioValue,
                    selectedDepartment,
                    title,
                    selectedRestrictions
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
                    form.submit();
                }
            }



        });





        </script>
    <script src="https://cdn.ckeditor.com/ckeditor5/37.0.1/classic/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script src="<?=ROOT?>/assets/js/secretary/createminute.script.js"></script>
</body>