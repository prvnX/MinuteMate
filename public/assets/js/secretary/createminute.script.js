let currentPage = 1;
let sectionCount = 0;
let editors = [];
let editorInitPromises = [];
let autoSaveState = true ;


// Function to set active tab
function setActiveTab(tabName) {
    document.querySelectorAll(".tab").forEach(tab => {
        tab.classList.remove("active");
        if (tab.getAttribute("data-tab") === tabName) {
            tab.classList.add("active");
        }
    });
}

// Navigation button event listeners
document.getElementById("nextBtntoP2").addEventListener("click", function() {
    if (currentPage === 1) {
        document.querySelector(".minute-page-1").style.display = "none";
        document.querySelector(".minute-page-3").style.display = "none";
        document.querySelector(".minute-page-2").style.display = "block";

        document.getElementById("backBtntoP1").style.display = "inline";
        document.getElementById("nextBtntoP2").style.display = "none";
        document.getElementById("nextBtntoP3").style.display = "inline";
        document.getElementById("backBtntoP2").style.display = "none";
        document.getElementById("submitBtn").style.display = "none";

        setActiveTab("minute-content");
        currentPage++;
    }
});

document.getElementById("backBtntoP1").addEventListener("click", function() {
    if (currentPage === 2) {
        document.querySelector(".minute-page-2").style.display = "none";
        document.querySelector(".minute-page-1").style.display = "block";
        document.querySelector(".minute-page-3").style.display = "none";

        document.getElementById("backBtntoP1").style.display = "none";
        document.getElementById("nextBtntoP2").style.display = "inline";
        document.getElementById("nextBtntoP3").style.display = "none";
        document.getElementById("submitBtn").style.display = "none";

        setActiveTab("meeting-details");
        currentPage--;
    }
});

document.getElementById("nextBtntoP3").addEventListener("click", function() {
    if (currentPage === 2) {
        document.querySelector(".minute-page-2").style.display = "none";
        document.querySelector(".minute-page-3").style.display = "block";
        document.querySelector(".minute-page-1").style.display = "none";

        document.getElementById("backBtntoP1").style.display = "none";
        document.getElementById("nextBtntoP2").style.display = "none";
        document.getElementById("nextBtntoP3").style.display = "none";
        document.getElementById("backBtntoP2").style.display = "inline";
        document.getElementById("submitBtn").style.display = "inline";

        setActiveTab("attachments");
        currentPage++;
    }
});

document.getElementById("backBtntoP2").addEventListener("click", function() {
    if (currentPage === 3) {
        document.querySelector(".minute-page-3").style.display = "none";
        document.querySelector(".minute-page-2").style.display = "block";
        document.querySelector(".minute-page-1").style.display = "none";

        document.getElementById("backBtntoP1").style.display = "inline";
        document.getElementById("nextBtntoP2").style.display = "none";
        document.getElementById("nextBtntoP3").style.display = "inline";
        document.getElementById("backBtntoP2").style.display = "none";
        document.getElementById("submitBtn").style.display = "none";

        setActiveTab("minute-content");
        currentPage--;
    }
});

// Add event listeners for tab clicks
document.querySelectorAll(".tab").forEach(tab => {
    tab.addEventListener("click", function() {
        const tabName = this.getAttribute("data-tab");
        setActiveTab(tabName);

        if (tabName === "meeting-details") {
            document.querySelector(".minute-page-1").style.display = "block";
            document.querySelector(".minute-page-2").style.display = "none";
            document.querySelector(".minute-page-3").style.display = "none";
            document.getElementById("backBtntoP1").style.display = "none";
            document.getElementById("nextBtntoP2").style.display = "inline";
            document.getElementById("nextBtntoP3").style.display = "none";
            document.getElementById("backBtntoP2").style.display = "none";
            document.getElementById("submitBtn").style.display = "none";
            currentPage = 1;
        } else if (tabName === "minute-content") {
            document.querySelector(".minute-page-1").style.display = "none";
            document.querySelector(".minute-page-2").style.display = "block";
            document.querySelector(".minute-page-3").style.display = "none";
            document.getElementById("backBtntoP1").style.display = "inline";
            document.getElementById("nextBtntoP2").style.display = "none";
            document.getElementById("nextBtntoP3").style.display = "inline";
            document.getElementById("backBtntoP2").style.display = "none";
            document.getElementById("submitBtn").style.display = "none";
            currentPage = 2;
        } else if (tabName === "attachments") {
            document.querySelector(".minute-page-1").style.display = "none";
            document.querySelector(".minute-page-2").style.display = "none";
            document.querySelector(".minute-page-3").style.display = "block";
            document.getElementById("backBtntoP1").style.display = "none";
            document.getElementById("nextBtntoP2").style.display = "none";
            document.getElementById("nextBtntoP3").style.display = "none";
            document.getElementById("backBtntoP2").style.display = "inline";
            document.getElementById("submitBtn").style.display = "inline";
            currentPage = 3;
        }
    });
});


let sectionRestrictions = {}; // To store restrictions for each section

function addContentSection(title = '', content = '') {
    sectionCount++;
    // close button
    const closeBtn = document.createElement('button');
    closeBtn.type = 'button';
    closeBtn.innerHTML = 'x';
    closeBtn.classList.add('closeBtn');
    closeBtn.addEventListener('click', function() {
        if (sectionCount === 1) return;
        if(confirm('Are you sure you want to delete this content?')){
            sectionCount--;
            this.parentElement.remove();
        }
        else{
            return;
        }


    });
    // Create a new section div
    const sectionDiv = document.createElement('div');
    sectionDiv.classList.add('content-section');
    sectionDiv.id = `section-${sectionCount}`;


    // Title label and input
    const titleText = document.createElement('label');
    titleText.innerText = "Enter the content title: ";
    titleText.classList.add('title-input-text');
    sectionDiv.appendChild(titleText); // Append the label before input


    const titleInput = document.createElement('input');
    titleInput.type = 'text';
    titleInput.placeholder = 'Enter title';
    titleInput.value = title;  // Pre-fill with saved title
    titleInput.classList.add('title-input');
    titleInput.style.width = '50%';
    titleInput.style.marginBottom = '10px';
    sectionDiv.appendChild(titleInput);
    if(sectionCount > 1) {
        sectionDiv.appendChild(closeBtn);
        }

    // Radio buttons label and inputs
   

    const radioContainer = document.createElement('div');
    radioContainer.style.marginBottom = '10px';
    radioContainer.classList.add('radio-container');

    const radioText = document.createElement('label');
    radioText.innerText = "Send to the next meeting (If applicable): ";
    radioText.classList.add('radio-text');
    radioContainer.appendChild(radioText); // Append the label before radio buttons
    let radioLabels = [];
    switch (meetingType) {
        case 'iud':
            radioLabels = ["IUD", "SYN"];
            break;
        case 'rhd':
            radioLabels = ["RHD", "SYN"];
            break;
        case 'bom':
            radioLabels = ["BOM"];
            break;
        case 'syn':
            radioLabels = ["SYN","BOM"];
            break;
        default:
            radioLabels = ["IUD","RHD","BOM", "SYN"];
    }
    radioLabels.forEach(label => {
        const radioInput = document.createElement('input');
        radioInput.type = 'radio';
        radioInput.name = `options-${sectionCount}`;
        radioInput.value = label;
        radioInput.onclick = function() {
            const btnID = this.name.split('-')[1];
            this.parentElement.parentElement.querySelectorAll('.radio-clearBtn').forEach(btn => {
                btn.style.display = 'inline';
            });
        };
        const radioLabel = document.createElement('label');
        radioLabel.innerText = label;
        radioLabel.style.marginRight = '20px';

        radioContainer.appendChild(radioInput);
        radioContainer.appendChild(radioLabel);
    });
    const clearBtn=document.createElement('button');
    clearBtn.type='button';
    clearBtn.innerHTML='<i class="fa-solid fa-eraser"></i> Clear Selection';
    clearBtn.classList.add('radio-clearBtn');
    clearBtn.style.display='none';
    clearBtn.id=`${sectionCount}`;
    clearBtn.addEventListener('click',function(){
        radioLabels.forEach(label => {
            const radioInput = document.querySelector(`input[name="options-${this.id}"][value="${label}"]`);
            radioInput.checked=false;
            this.style.display='none';
        });
    });
    radioContainer.appendChild(clearBtn);

    sectionDiv.appendChild(radioContainer);

    // Select label and dropdown
    const selectText = document.createElement('label');
    selectText.innerText = "Forward to the following department(s) (If applicable): ";
    selectText.classList.add('select-text');
    sectionDiv.appendChild(selectText); // Append the label before select
        const forwarddiv=document.createElement('div');
        forwarddiv.classList.add('forward-div');
        options.forEach(optionText => {
            const optiondiv=document.createElement('div');
            optiondiv.classList.add('forward-option');
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.name = 'forwardDep[]';
            checkbox.value = optionText.id;
            checkbox.style.marginRight = '10px';
            const label = document.createElement('label');
            label.innerText = optionText.dep_name;
            optiondiv.appendChild(checkbox);
            optiondiv.appendChild(label);
            forwarddiv.appendChild(optiondiv);
        });
        sectionDiv.appendChild(forwarddiv);
    

    // user restriction button
    const restrictionButton = document.createElement('button');
    restrictionButton.type = 'button';
    restrictionButton.innerHTML = '<i class="fa-solid fa-lock"></i><span class="button-txt">&nbsp;Restrict Users</span>';
    restrictionButton.classList.add('restriction-btn');
    restrictionButton.id = `Res_btn-${sectionCount}`;
    restrictionButton.onclick = function() {
            RestrictionID= this.id.split('-')[1];
            showRestrictionPopup(RestrictionID);
    };
    sectionDiv.appendChild(restrictionButton);

    // Display selected restrictions
    const restrictionDisplay = document.createElement('div');
    restrictionDisplay.classList.add('restriction-display');
    restrictionDisplay.id = `restrictions-${sectionCount}`;
    sectionDiv.appendChild(restrictionDisplay);


    // Editor container
    const editorDiv = document.createElement('div');
    editorDiv.id = `editor-${sectionCount}`;
    editorDiv.classList.add('editor-container');
    sectionDiv.appendChild(editorDiv);

    document.getElementById('content-sections').appendChild(sectionDiv);

    // Initialize CKEditor
    ClassicEditor.create(document.querySelector(`#editor-${sectionCount}`), {
        toolbar: [
            'heading', '|', 'bold', 'italic', 'link',
            'bulletedList', 'numberedList', 'blockQuote',
            'insertTable', 'mediaEmbed', 'undo', 'redo'
        ],
        image: {
            toolbar: [
                'imageTextAlternative',
                'imageStyle:full',
                'imageStyle:side'
            ]
        }
    })
    .then(editor => {
        editor.setData(content);  // Set the saved HTML content into CKEditor
        editors.push({ titleInput, editor });
        
    })
    .catch(error => {
        console.error('Error initializing CKEditor:', error);
    });

}

window.onload = function() {
    

    addContentSection(
        'You can type content title here',
        '<p>This is <strong>sample</strong> content with <em>italic</em> text with formatting.</p><br><br><br><br><p>Click add more to add another content.</p>'
    );
    if(draftStatus==1){
        document.getElementById('loadDraftPopup').style.display='flex';
    }
        // Function to show alert

          document.querySelectorAll('.sub-container').forEach(container => {
            container.addEventListener("click", startAutoSave);
          });
          // Listen for keyboard input
          document.addEventListener("keydown", startAutoSave);
    

};
function toggleCheckBox(row,selectedCheckBox){
    $checkBoxes = document.querySelectorAll(`.rowID-${row}`);
    $checkBoxes.forEach(checkBox => {
        if(checkBox !== selectedCheckBox){
            checkBox.disabled = selectedCheckBox.checked;
            checkBox.classList.add('disabledCheckBox');
            if(!selectedCheckBox.checked){
                checkBox.disabled = false;
                checkBox.classList.remove('disabledCheckBox');
            }
        }
    });
}

function showRestrictionPopup(sectionId) {
    let restrictionHtml = users.map(user => 
        `<div style="display:block;">
         <label for="user-${user.username}-${sectionId}">${user.name}</label>
         <input type="checkbox" class="restriction-checkbox" value="${user.username}" id="user-${user.username}-${sectionId}" style="float:right"> </div>`
    ).join('');

    Swal.fire({
        title: 'Restrict Access to This Section',
        html: restrictionHtml,
        showCancelButton: true,
        confirmButtonText: 'Save Restrictions',
        confirmButtonColor: "#3b82f6",
        customClass: {
            popup: "select-res-font"
        },
        preConfirm: () => {
            let selectedUsers = [];
            document.querySelectorAll(`.restriction-checkbox:checked`).forEach(checkbox => {
                selectedUsers.push(checkbox.value);
            });
            return selectedUsers;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            sectionRestrictions[sectionId] = result.value || [];
            updateRestrictionDisplay(sectionId);
        }
    });
}

function updateRestrictionDisplay(sectionId) {
    const restrictionDisplay = document.getElementById(`restrictions-${sectionId}`);
    restrictionDisplay.innerHTML = "<span class='restriction-title'>Restrictions : </span>";
    const sizeofSection=sectionRestrictions[sectionId].length;
    if(sizeofSection === 0) {
        restrictionDisplay.innerHTML += "<span class='no-res-txt'>No restrictions are added for this section</span>";
        return;
    }
    else{
        restrictionDisplay.innerHTML = "<span class='restriction-title'>Restrictions : </span> <span class='res-users'>";
        sectionRestrictions[sectionId].forEach(Restrictuser => {
            users.forEach(user => {
                if(user.username === Restrictuser){
                    restrictionDisplay.innerHTML += user.name;
                    if(sectionRestrictions[sectionId].indexOf(Restrictuser) !== sizeofSection-1){
                        restrictionDisplay.innerHTML += " , ";
                    }

                }
            });
            restrictionDisplay.innerHTML += "</span>";
    });
    return;
}
}

//  Draft features


//saving draft
function saveDraft() {
    let Sections = [];
    let discussedMemos = [];
    let parkedMemos = [];
    let underDiscussionMemos = [];
    let linkedMinutes = [];
    let keywords = [];


    console.log("Saving as draft...");

    // Get content sections
    const contentSections = document.querySelectorAll('.content-section');
    contentSections.forEach((section, index) =>{
        let forwardDepartments=[];
        const title = section.querySelector('.title-input').value;
        const selectedRadio = section.querySelector(`input[name="options-${index+1}"]:checked`);
        const selectedRadioValue = selectedRadio ? selectedRadio.value : '';
        const editorInstance = editors.find(e => e.titleInput === section.querySelector('.title-input'));
        const insertedcontent = editorInstance ? editorInstance.editor.getData() : '';
        const forwardDeps = section.querySelectorAll(`input[name="forwardDep[]"]:checked`);
        forwardDeps.forEach(dep=>{
            forwardDepartments.push(dep.value);
        })
        const sectionId = index + 1;
        const selectedRestrictions = sectionRestrictions[sectionId] || [];
        Sections.push({
            insertedcontent,
            selectedRadioValue,
            title,
            selectedRestrictions,
            forwardDepartments
        });
    })
   

    // get the memo status
    //Discussed
    document.querySelectorAll("input[name='discussed[]']:checked").forEach((checkbox) => {
        discussedMemos.push(checkbox.value);
    });
    //Parked
    document.querySelectorAll("input[name='parked[]']:checked").forEach((checkbox) => {
        parkedMemos.push(checkbox.value);
    });
    //Under Discussion
    document.querySelectorAll("input[name='underdiscussion[]']:checked").forEach((checkbox) => {
        underDiscussionMemos.push(checkbox.value);
    });

    // linked minutes
    document.querySelectorAll("select[name='LinkedMinutes[]']").forEach((select) => {
        linkedMinutes.push(select.value);
    });

    // keywords
    document.querySelectorAll("input[name='keywordlist[]']").forEach((input) => {
        keywords.push(input.value);
    }
    );

    const data={Sections,discussedMemos,parkedMemos,underDiscussionMemos,linkedMinutes,keywords}; 
    // console.log("Data to be saved as draft:", data);
    // console.log(meetingId);
    // console.log(username);
    // console.log(fetchURL);
    fetch(fetchURL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            draftMeeting: meetingId,
            draftAddedUser: username,
            draftData: data       
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Success:', data.response);
        })
        .catch(err => {
            console.error("Failed to save", err);
          });

}
//loading draft
function handleLoadDraft() {
    console.log("Loading draft...");
    loadDraft();
    document.getElementById('loadDraftPopup').style.display='none';
}
function handleCancelDraft() {
    document.getElementById('loadDraftPopup').style.display='none';
}

//auto save on off
function handleAutoSave (){
    const autoSaveBtn= document.getElementById('autoSaveBtn');
    if(autoSaveState){
        autoSaveBtn.innerHTML='<i class="fa-solid fa-toggle-off"></i><span class="button-txt">&nbsp;&nbsp; Auto Save is Off</span>';
        autoSaveState=false;
    }
    else{
        autoSaveBtn.innerHTML='<i class="fa-solid fa-toggle-on"></i><span class="button-txt">&nbsp;Auto Save is On</span>';
        autoSaveState=true;
    }
}

function loadDraft(){
    fetch(fetchGetURL, {
        method :'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body:JSON.stringify({
            loadDraft:meetingId,
            loadAddedUser:username
        })
    }).then(response=>response.json())
    .then(data=>{
         const responseData=data.response;
         loadDraftToPage(responseData);         
    })
    .catch(err =>{
        console.log(err);
    })

    
}

function loadDraftToPage(draftData){
    // load attendence

    //load  sections
    //loading content title and content
    draftData.Sections.forEach((section,index) => {
        if(index==0){
            let title = section.title;
            let content = section.insertedcontent;
            section=document.querySelectorAll('.content-section')[0];
            const titleElement=section.querySelector('.title-input');
            const editorInstance = editors.find(e => e.titleInput === section.querySelector('.title-input'));
            titleElement.value=title;
            const editor = editorInstance.editor;
            editor.setData(content);
        }
        else{
            addContentSection(section.title, section.insertedcontent);
        }
    });

    const contentSections = document.querySelectorAll('.content-section');
    contentSections.forEach((section, index) =>{
        // load forward to buttons
        section.querySelectorAll(`input[type="radio"]`).forEach((radio) => {
            if(radio.value===draftData.Sections[index].selectedRadioValue){
                radio.checked=true;
            }
        })
        
        // load forward departments
        draftData.Sections[index].forwardDepartments.forEach((forwardDep) => {
            section.querySelectorAll(`input[name="forwardDep[]"]`).forEach((checkbox,index) => {
            if(checkbox.value===forwardDep){
                checkbox.checked=true;
            }
        }
        );
        }
        );

        // load restrictions
        sectionRestrictions[index+1]=draftData.Sections[index].selectedRestrictions;
        updateRestrictionDisplay(index+1);

    })
    //load discussed memos
    const discussedDraft=draftData.discussedMemos;
    const discussedInputs=discussedDraft.length;
    if(discussedInputs>0){
        discussedDraft.forEach((draftDiscussed) => {
            document.querySelectorAll("input[name='discussed[]']").forEach((checkbox) => {
                if(checkbox.value===draftDiscussed){
                    checkbox.click();
                }
            })
        });
    }
    //load parked memos
    const parkedDraft=draftData.parkedMemos;
    const parkedInputs=parkedDraft.length;
    if(parkedInputs>0){
        parkedDraft.forEach((draftParked) => {
            document.querySelectorAll("input[name='parked[]']").forEach((checkbox) => {
                if(checkbox.value===draftParked){
                    checkbox.click();
                }
            })
        });
    }
    //load under discussion memos
    const underDiscussionDraft=draftData.underDiscussionMemos;
    const underDiscussionInputs=underDiscussionDraft.length;
    if(underDiscussionInputs>0){
        underDiscussionDraft.forEach((draftUnderDiscussion) => {
            document.querySelectorAll("input[name='underdiscussion[]']").forEach((checkbox) => {
                if(checkbox.value===draftUnderDiscussion){
                    checkbox.click();
                }
            })
        });
    }
    //load linked minutes
    const linkedMinutesDraft=draftData.linkedMinutes;
    const linkedMinutesInputs=linkedMinutesDraft.length;
    if(linkedMinutesInputs>0){
        for (let i = 0; i < linkedMinutesInputs-1; i++) {
            addAnotherMinute();
        }
    }

    document.querySelectorAll("select[name='LinkedMinutes[]']").forEach((select,index) => {
            const draftLinkedMinutes=linkedMinutesDraft[index];
            if(draftLinkedMinutes!=="none"){
                select.value=draftLinkedMinutes;
            }
        })

    //load keywords
    const keywordsDraft=draftData.keywords;
    const keywordsInputs=keywordsDraft.length;
    if(keywordsInputs>0){
        for (let i = 0; i < keywordsInputs-1; i++) {
            addAnotherKeyword();
        }
    }
    document.querySelectorAll("input[name='keywordlist[]']").forEach((Input,index)=>{
        Input.value=keywordsDraft[index];
    })


}


let autoSaveTimer;
if(autoSaveState){
function startAutoSave() {
    clearTimeout(autoSaveTimer);
    autoSaveTimer = setTimeout(() => {
        if(autoSaveState){
            saveDraft();
        }
    }, 2000);

 } // Auto-save every 2 seconds
}
else{
    clearTimeout(autoSaveTimer);
}

