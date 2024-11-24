function handleMeetingDelete(meetingId) {
    document.getElementById('confirmMessage').innerText = `Are you sure you want to delete this meeting?`;
    const modal = document.getElementById('confirmModal');
    modal.style.display = 'block';
    document.getElementById('confirmYes').onclick = function () {
        const url = '<?=ROOT?>/Events/deleteMeeting';
        fetch(url, {
                method: 'POST', // Use POST as the PHP function expects POST data
                headers: {
                        'Content-Type': 'application/json', // Specify content type
                        },
                body: JSON.stringify({ meeting_id: meetingId }), // Send meeting ID as JSON
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json(); // Parse JSON response
            })
        .then(data => {
            // Handle success or failure from PHP
        if (data.success) { //success is the key in the json response from php same as the error key
            console.log(data.success); // Log success message
            showAlert(data.success); // Optionally alert the user
        } else {
            console.error('Deletion failed:', data.error || 'Unknown error');
        }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
    //close the modal on click
    document.getElementById('confirmNo').onclick = function () {
        modal.style.display = 'none';
    };
}



function handleMeetingReschedule(meetingId) {
    const modal = document.getElementById('rescheduleModal');
    modal.style.display = 'block';

    document.getElementById('rescheduleYes').onclick = function () {
        const newDate = document.getElementById('newDate').value;
        const startTime = document.getElementById('startTime').value;
        const endTime = document.getElementById('endTime').value;

    if (newDate && startTime && endTime) {
        alert(`Meeting rescheduled  to ${newDate} from ${startTime} to ${endTime}.`);
        modal.style.display = 'none';
        } 
        else {
            alert("Please fill in all fields.");
        }
    };
        document.querySelector('.close-btn').onclick = function () {
            modal.style.display = 'none';
        };
    }
    //function for handling the alert
    function showAlert(message) {
            const modal = document.getElementById('alertModal');
            const messageElement = document.getElementById('Message');
            messageElement.textContent = message;
            modal.style.display = 'block';
            document.getElementById('successOk').onclick = function () {
            modal.style.display = 'none';
            location.reload();  // Refresh the current page
        };
}




    
    window.onclick = function (event) {
    const modal = document.getElementById('confirmModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
};


