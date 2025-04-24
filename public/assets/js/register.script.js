document.addEventListener('DOMContentLoaded', ()=>{
    const dynamicFields=document.getElementById("dynamic-fields")
    const studentCheck=document.getElementById("studentCheck")
    const lecturerCheck=document.getElementById("lecturerCheck")
    const secretaryCheck=document.getElementById("secretaryCheck")

    function updateDynamicFields(){
        dynamicFields.innerHTML= '';

        if(studentCheck.checked && !lecturerCheck.checked && !secretaryCheck.checked){
            dynamicFields.innerHTML += `
             <label for="stdrep-id">Student ID</label>
            <input type="text" name="stdrep-id" class="roundedge-input-text" placeholder="Enter your Student ID" required>
            `;     
    }else if (lecturerCheck.checked || secretaryCheck.checked){
            dynamicFields.innerHTML += `
                <label for="lec-id">Lecturer ID</label>
                <input type="text" name="lec-id" class="roundedge-input-text" placeholder="Enter your Lecturer ID" required>
                `;     
        }
    }

    function updateCheckboxStates() {
        if (studentCheck.checked) {
            lecturerCheck.disabled = true;
            secretaryCheck.disabled = true;
        } else if (lecturerCheck.checked || secretaryCheck.checked) {
            studentCheck.disabled = true;
        } else {
            // Enable all if none are selected
            studentCheck.disabled = false;
            lecturerCheck.disabled = false;
            secretaryCheck.disabled = false;
        }
    }

    function handleCheckboxChange() {
        updateDynamicFields();
        updateCheckboxStates();
    }

studentCheck.addEventListener('change',handleCheckboxChange)
secretaryCheck.addEventListener('change',handleCheckboxChange)
lecturerCheck.addEventListener('change',handleCheckboxChange)

handleCheckboxChange();
});
