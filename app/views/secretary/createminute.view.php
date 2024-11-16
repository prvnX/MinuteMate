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
    require_once("../app/views/components/navbar.php");
    ?>
    
    <h1 class="minute-heading">Create the minute</h1>
    <div class="minute-form-container">
        <form action="<?=ROOT?>/secretary/submitminute" method="post" id="minuteForm">
            <?php

            //the following arrays are just placeholders for the actual data that will be fetched from the database
            $departments=["department1","department2","department3","department4","department5","department6","department7","department8","department9","department10"]; //get the departments from the database here
            $meetingMembers=[
                "Amelia Rivera",
                "Liam Harper",
                "Sophia Jensen",
                "Elijah Foster",
                "Olivia Cruz",
                "Ethan Walker",
                "Ava Brooks",
                "Mason Reed",
                "Mia Bennett",
                "Noah Hughes",
                "Emma Knight",
                "Lucas Morgan",
                "Isabella Cooper",
                "Henry Parker",
                "Lily Torres",
                "Aiden Murphy",
                "Grace Edwards",
                "Benjamin Hayes",
                "Zoey Mitchell",
                "Jacob Adams",
                "Nora Hill"
            ]; //get the meeting members from the database here
            $memos = [
                ['id' => 1, 'name' => '"Integrating AI Tools in Academic Assessments for Enhanced Learning'],
                ['id' => 2, 'name' => '"Integrating AI Tools in Academic Assessments for Enhanced Learning'],
                ['id' => 3, 'name' => '"Integrating AI Tools in Academic Assessments for Enhanced Learning'],
                ['id' => 4, 'name' => '"Integrating AI Tools in Academic Assessments for Enhanced Learning'],
                ['id' => 5, 'name' => '"Integrating AI Tools in Academic Assessments for Enhanced Learning'],
                ['id' => 6, 'name' => '"Integrating AI Tools in Academic Assessments for Enhanced Learning'],
                ['id' => 7, 'name' => 'Memo 7'],
                ['id' => 8, 'name' => 'Memo 8'],
                ['id' => 9, 'name' => 'Memo 9'],
                ['id' => 10, 'name' => 'Memo 10']
            ]; //get the memos from the database here
            $minuteList = [
                ['id' => 1, 'name' => 'Minute 1' , "meeting" => "meeting01" , "date" => "2021-09-01"],
                ['id' => 2, 'name' => 'Minute 2' , "meeting" => "meeting02" , "date" => "2021-09-02"],
                ['id' => 3, 'name' => 'Minute 3' , "meeting" => "meeting03" , "date" => "2021-09-03"],
                ['id' => 4, 'name' => 'Minute 4' , "meeting" => "meeting04" , "date" => "2021-09-04"],
                ['id' => 5, 'name' => 'Minute 5' , "meeting" => "meeting05" , "date" => "2021-09-05"],
                ['id' => 6, 'name' => 'Minute 6' , "meeting" => "meeting06" , "date" => "2021-09-06"],
                ['id' => 7, 'name' => 'Minute 7' , "meeting" => "meeting07" , "date" => "2021-09-07"],
                ['id' => 8, 'name' => 'Minute 8' , "meeting" => "meeting08" , "date" => "2021-09-08"],
                ['id' => 9, 'name' => 'Minute 9' , "meeting" => "meeting09" , "date" => "2021-09-09"],
                ['id' => 10, 'name' => 'Minute 10' , "meeting" => "meeting10" , "date" => "2021-09-10"]
            ]
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
                            <input type='checkbox' name='attendence[]' value='$member' id='$member'>
                            <label for='$member' class='member'>$member</label>
                        </div>";
                    }?>
                </div>
                </div>
                
                <div class="sub-container agenda-details">
                    <h1 class="form-sub-title">Agenda Details</h1>

                    <div id="agendaContainer" class="agendaContainer">
                        <div class="input-container">
                            <input type="text" name="Agenda[]" placeholder="Enter the Agenda Item 1 here" />
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
                            echo "<tr>
                            <td>$memo[id] - $memo[name]</td>
                            <td><input type='checkbox' name='discussed[]' value='$memo[id]'> </td>
                            <td><input type='checkbox' name='underdiscussion[]' value='$memo[id]'></td>
                            <td><input type='checkbox' name='parked[]' value='$memo[id]'></td>";}?>
                     </table>
                     </div>
                </div>

                <div class="sub-container minute-Linking">
                    <h1 class="form-sub-title">Link Previous Minutes (If applicable)</h1>
                    <div id="LinkedminuteSection">
                        <select name="LinkedMinutes[]" id="LinkedMinutes">
                            <option value="none">Select Linked Minutes</option>
                            <?php foreach($minuteList as $minute){
                                echo "<option value='$minute[id]'>$minute[name] - $minute[meeting] - $minute[date]</option>";
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
        const minuteList = <?php echo json_encode($minuteList); ?>;

        //dynamically add input selects
        function addAnotherMinute(){
            const select = document.getElementById("LinkedMinutes");
            const newSelect = select.cloneNode(true);
            newSelect.id = "";
            document.getElementById("LinkedminuteSection").appendChild(newSelect);

        }
        </script>
    <script src="https://cdn.ckeditor.com/ckeditor5/37.0.1/classic/ckeditor.js"></script>
    <script src="<?=ROOT?>/assets/js/secretary/createminute.script.js"></script>
</body>