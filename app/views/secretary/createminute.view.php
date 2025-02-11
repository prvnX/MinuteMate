<head>
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/secretary/createminute.style.css">
    <link href="https://cdn.ckeditor.com/ckeditor5/37.0.1/classic/theme.css" rel="stylesheet">
    <title>Create a Minute</title>
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
            $meetingMembers=$data['participants'];
            $memos=$data['memos'];
            $minuteList=$data['minutes'];
            $departments=$data['departments'];
            if($memos==null){
                $memos=[];
            }
            ?>
            
            <!-- Page 1 -->
            <div class="minute-page minute-page-1">
                <div class="sub-container meeting-details">
                    <h1 class="form-sub-title">Meeting Details</h1>
                    <div class="row-A">
                        <div class="col">
                            Meeting : <?= $meetingId ?>
                        </div>

                        <div class="col">
                            <label for="location">Location of the meeting</label>
                            <input type="text" name="location" id="location">
                        </div>  
                    </div>
                    <div class="row-B">
                        <div class="col">
                            <label for="meeting-date">Date</label>
                            <input type="date" name="meeting-date" id="meeting-date">
                        </div>
                        <div class="col">
                            <label for="meeting-start-time">Meeting Started Time</label>
                            <input type="time" name="meeting-start-time" id="meeting-start-time">
                            <label for="meeting-end-time">Meeting Ended Time</label>
                            <input type="time" name="meeting-end-time" id="meeting-end-time">
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
        // PHP array embedded in JavaScript
        const options = <?php echo json_encode($departments); ?>;
        const meetingType = <?php echo json_encode($data['meetingType']); ?>;

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
            console.log("Form Submitted");
        });
        </script>
    <script src="https://cdn.ckeditor.com/ckeditor5/37.0.1/classic/ckeditor.js"></script>
    <script src="<?=ROOT?>/assets/js/secretary/createminute.script.js"></script>
</body>