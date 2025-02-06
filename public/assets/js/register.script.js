document.addEventListener('DOMContentLoaded', ()=>{
    const dynamicFields=document.getElementById("dynamic-fields")
    const studentRadio=document.getElementById("studentRadio")
    const lecturerRadio=document.getElementById("lecturerRadio")
    const secretaryRadio=document.getElementById("secretaryRadio")

    function addStudentField(){
        dynamicFields.innerHTML=`
             <label for="stu-id">Student ID</label>
            <input type="text" name="lec-id" id='lec-id' class="roundedge-input-text" placeholder="Enter your Student ID" required>
            `     
    }
    function addSecretaryField(){
        dynamicFields.innerHTML=`
            <label for="lec-id">Lecturer ID</label>
            <input type="text" name="lec-id" id='lec-id' class="roundedge-input-text" placeholder="Enter your Lecturer ID" required>
            `     
    }
    function addLecturerField(){
        dynamicFields.innerHTML=`
            <label for="lec-id">Lecturer ID</label>
            <input type="text" name="lec-id" id='lec-id' class="roundedge-input-text" placeholder="Enter your Lecturer ID" required>
            <label for="deps">Department Associated</label>
            <input type="text" name="deps" id='deps' class="roundedge-input-text" placeholder="Enter the departments you associated with " >
            `     
    }
    studentRadio.addEventListener('change',addStudentField)
    secretaryRadio.addEventListener('change',addSecretaryField)
    lecturerRadio.addEventListener('change',addLecturerField)
})
