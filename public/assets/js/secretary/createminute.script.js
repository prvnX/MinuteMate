let currentPage = 1;
let sectionCount = 0;
let editors = [];
//page handle
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
        currentPage--;
    }
});

document.getElementById("nextBtntoP3").addEventListener("click", function() {
    if(currentPage === 2) {
        document.querySelector(".minute-page-2").style.display = "none";
        document.querySelector(".minute-page-3").style.display = "block";
        document.querySelector(".minute-page-1").style.display = "none";
        document.getElementById("backBtntoP1").style.display = "none";
        document.getElementById("nextBtntoP2").style.display = "none";
        document.getElementById("nextBtntoP3").style.display = "none";
        document.getElementById("backBtntoP2").style.display = "inline";
        document.getElementById("submitBtn").style.display = "inline";
        currentPage++;
    }
});

document.getElementById("backBtntoP2").addEventListener("click", function() {
    if(currentPage === 3) {
        document.querySelector(".minute-page-3").style.display = "none";
        document.querySelector(".minute-page-2").style.display = "block";
        document.querySelector(".minute-page-1").style.display = "none";
        document.getElementById("backBtntoP1").style.display = "inline";
        document.getElementById("nextBtntoP2").style.display = "none";
        document.getElementById("nextBtntoP3").style.display = "inline";
        document.getElementById("backBtntoP2").style.display = "none";
        document.getElementById("submitBtn").style.display = "none";
        currentPage--;
    }
});


// Add more agenda items (dynamic agenda )
let i = 2;
document.getElementById("addMoreBtn").addEventListener("click", function() {
    const newInputContainer = document.createElement("div");
    newInputContainer.classList.add("input-container");

    const newInput = document.createElement("input");
    newInput.type = "text";
    newInput.name = "Agenda[]";
    newInput.placeholder = "Enter the Agenda Item " + i + " here";

    newInputContainer.appendChild(newInput);
    document.getElementById("agendaContainer").appendChild(newInputContainer);
    i++;
});

function addContentSection(title = '', content = '') {
    sectionCount++;

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

    // Radio buttons label and inputs
   

    const radioContainer = document.createElement('div');
    radioContainer.style.marginBottom = '10px';
    radioContainer.classList.add('radio-container');

    const radioText = document.createElement('label');
    radioText.innerText = "Send to the next meeting (If applicable): ";
    radioText.classList.add('radio-text');
    radioContainer.appendChild(radioText); // Append the label before radio buttons

    const radioLabels = ["IUD", "RHD", "BOM", "SYN"];
    radioLabels.forEach(label => {
        const radioInput = document.createElement('input');
        radioInput.type = 'radio';
        radioInput.name = `options-${sectionCount}`;
        radioInput.value = label;

        const radioLabel = document.createElement('label');
        radioLabel.innerText = label;
        radioLabel.style.marginRight = '20px';

        radioContainer.appendChild(radioInput);
        radioContainer.appendChild(radioLabel);
    });
    sectionDiv.appendChild(radioContainer);

    // Select label and dropdown
    const selectText = document.createElement('label');
    selectText.innerText = "Forward to the following department (If applicable): ";
    selectText.classList.add('select-text');
    sectionDiv.appendChild(selectText); // Append the label before select

    const selectDropdown = document.createElement('select');
    selectDropdown.classList.add('select-dropdown');
    selectDropdown.style.marginBottom = '10px';

    options.forEach(optionText => {
        const option = document.createElement('option');
        option.value = optionText;
        option.text = optionText;
        selectDropdown.appendChild(option);
    });
    sectionDiv.appendChild(selectDropdown);

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
};


