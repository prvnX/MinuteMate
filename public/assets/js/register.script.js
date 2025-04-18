document.addEventListener('DOMContentLoaded', ()=>{
    const dynamicFields=document.getElementById("dynamic-fields")
    const studentCheck=document.getElementById("studentCheck")
    const lecturerCheck=document.getElementById("lecturerCheck")
    const secretaryCheck=document.getElementById("secretaryCheck")

    function updateDynamicFields(){
        dynamicFields.innerHTML= '';

        if(studentCheck.checked){
            dynamicFields.innerHTML += `
             <label for="stu-id">Student ID</label>
            <input type="text" name="lec-id[]" class="roundedge-input-text" placeholder="Enter your Student ID" required>
            `;     
    }

    if (secretaryCheck.checked){
        dynamicFields.innerHTML += `
            <label for="sec-id">Lecturer ID</label>
            <input type="text" name="lec-id[]" class="roundedge-input-text" placeholder="Enter your Lecturer ID" required>
            `;     
    }

    if (lecturerCheck.checked){
        dynamicFields.innerHTML += `
            <label for="lec-id">Lecturer ID</label>
            <input type="text" name="lec-id" class="roundedge-input-text" placeholder="Enter your Lecturer ID" required>
            <label for="deps">Department Associated</label>
            <input type="text" name="deps" class="roundedge-input-text" placeholder="Enter the departments you associated with " >
            `;    
    }
}

studentCheck.addEventListener('change',updateDynamicFields)
secretaryCheck.addEventListener('change',updateDynamicFields)
lecturerCheck.addEventListener('change',updateDynamicFields)
});
