<head>
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/secretary/selectmeeting.style.css">
    <title>Select a meeting</title>
    <link rel="icon" href="<?=ROOT?>/img.png" type="image">
    
</head>
<body>
<div class="container">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">Select a Meeting</h1>
            </div>
            <div class="card-body">
                <div class="meeting-selection">
                    <!-- Meeting List Section -->
                    <div class="meeting-list">
                        <div class="meeting-list-header">
                            <h2 class="meeting-list-title"> Available Meetings for Minute Creation  </h2>
                            <div class="meeting-filter">
                                <button class="filter-btn active" data-filter="all">All</button>
                                <button class="filter-btn" data-filter="iud">IUD</button>
                                <button class="filter-btn" data-filter="rhd">RHD</button>
                                <button class="filter-btn" data-filter="bom">BOM</button>
                                <button class="filter-btn" data-filter="syn">SYN</button>
                            </div>
                        </div>
                        <div class="meeting-search">
                            <input type="text" placeholder="Find the meeting by date...(YYYY-MM-DD)" id="meeting-search-input">
                        </div>
                        <div class="meeting-list-container" id="meeting-list">
                            <!-- Meeting items will be added by js -->
                        </div>
                    </div>

                    <div class="calender">
                     <?php  
                        $showAddEvents=false;
                        require_once("../app/views/components/Calender/calandar-view.php"); //call the calander component
                        ?>
                    </div>
                </div>

                <div class="action-buttons">
                    <button class="btn btn-secondary" id="cancel-btn">Cancel</button>
                    <button class="btn btn-primary" id="create-minute-btn">Create Minute</button>
                </div>
            </div>
        </div>
    </div>

    
    
 


    <script>
        const meetings = <?php echo json_encode($meetings); ?>;
        
        const meetingListContainer = document.getElementById('meeting-list');
        const filterButtons = document.querySelectorAll('.filter-btn');
        const searchInput = document.getElementById('meeting-search-input');
        const createMinuteBtn = document.getElementById('create-minute-btn');
        const cancelBtn = document.getElementById('cancel-btn');
        createMinuteBtn.disabled = true; // Disable button initially
        createMinuteBtn.style.cursor = 'not-allowed'; // Change cursor to not-allowed
        createMinuteBtn.style.opacity = '0.4'; // Change opacity to indicate disabled state

        
        let selectedMeetingId = null;
        let currentFilter = 'all';
        let searchTerm = '';

        // Format date for display
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric' 
            });
        }

        // Render meeting list
        function renderMeetings() {
            // Filter meetings based on current filter and search term
            const filteredMeetings = meetings.filter(meeting => {
                const matchesFilter = currentFilter === 'all' || meeting.name === currentFilter;
                const matchesSearch = meeting.date.includes(searchTerm);
                return matchesFilter && matchesSearch;
            });

            // Sort meetings by date descending
            filteredMeetings.sort((a, b) => {
                return new Date(b.date) - new Date(a.date);
            });

            // Clear the container
            meetingListContainer.innerHTML = '';

            // If no meetings match the criteria
            if (filteredMeetings.length === 0) {
                meetingListContainer.innerHTML = `
                    <div class="no-meetings">
                        <p>No meetings found</p>
                        <p class='no-meetings-detail'>Please adjust your filters or search criteria.</p>
                    </div>
                `;
                return;
            }

            // Render each meeting
            filteredMeetings.forEach(meeting => {
                const meetingItem = document.createElement('div');
                meetingItem.className = `meeting-item ${meeting.id === selectedMeetingId ? 'selected' : ''}`;
                meetingItem.dataset.id = meeting.id;
                
                meetingItem.innerHTML = `
                    <div class="meeting-icon ${meeting.name}">${meeting.name.toUpperCase().substring(0, 1)}</div>
                    <div class="meeting-details">
                        <div class="meeting-name">${meeting.name.toUpperCase()} Meeting </div>
                        <div class="meeting-date">${formatDate(meeting.date)}</div>
                    </div>
                `;

                meetingItem.addEventListener('click', () => {
                    // Deselect previously selected meeting
                    const previouslySelected = document.querySelector('.meeting-item.selected');
                    if (previouslySelected) {
                        previouslySelected.classList.remove('selected');
                    }

                    // Select this meeting
                    meetingItem.classList.add('selected');
                    selectedMeetingId = meeting.id;
                    
                    // Enable create button if a meeting is selected
                    createMinuteBtn.disabled = false;
                    createMinuteBtn.style.cursor = 'pointer'; // Change cursor to pointer
                    createMinuteBtn.style.opacity = '1'; // Change opacity to indicate enabled state
                });

                meetingListContainer.appendChild(meetingItem);
            });
        }

        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                filterButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                currentFilter = button.dataset.filter;
                renderMeetings();
            });
        });

        // Initialize search
        searchInput.addEventListener('input', (e) => {
            searchTerm = e.target.value;
            renderMeetings();
        });

        // Initialize buttons
        createMinuteBtn.addEventListener('click', () => {
            if (selectedMeetingId) {
                window.location.href = `<?=ROOT?>/secretary/createminute?meeting=${selectedMeetingId}`;
            }
        });

        cancelBtn.addEventListener('click', () => {
                window.history.back();
        });

        // Initial render
        renderMeetings();

    </script>
</body>
</html>
    </script>
    </body>