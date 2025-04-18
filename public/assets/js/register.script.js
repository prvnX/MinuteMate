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

studentCheck.addEventListener('change',updateDynamicFields)
secretaryCheck.addEventListener('change',updateDynamicFields)
lecturerCheck.addEventListener('change',updateDynamicFields)

updateDynamicFields();
});
