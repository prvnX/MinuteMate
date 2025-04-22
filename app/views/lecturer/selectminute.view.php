<head>
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/secretary/selectminute.style.css">
    <title>Please Select a minute</title>
    <link rel="icon" href="<?=ROOT?>/img.png" type="image">

    
</head>
<body>
<?php
    $user="secretary";
    $memocart="memocart";   //use memocart-dot if there is a memo in the cart change with db
    $notification="notification"; //use notification-dot if there's a notification
    $menuItems = [ "home" => ROOT."/secretary",$memocart => ROOT."/secretary/memocart", $notification => ROOT."/secretary/notifications", "profile" => ROOT."/secretary/viewprofile"]; //pass the menu items here (key is the name of the page, value is the url)
    require_once("../app/views/components/new_navbar.php"); //call the navbar component
  
    ?>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">Select a Minute</h1>
            </div>
            <div class="card-body">
                <div class="minute-selection">
                    <!-- Minute List Section -->
                    <div class="minute-list">
                        <div class="minute-list-header">
                            <h2 class="minute-list-title">Available Minutes</h2>
                            <div class="minute-filter">
                                <button class="filter-btn active" data-filter="all">All</button>
                                <button class="filter-btn" data-filter="iud">IUD</button>
                                <button class="filter-btn" data-filter="rhd">RHD</button>
                                <button class="filter-btn" data-filter="bom">BOM</button>
                                <button class="filter-btn" data-filter="syn">SYN</button>
                            </div>
                        </div>
                        <div class="minute-search">
                            <input type="text" placeholder="Find the minute by date...(YYYY-MM-DD)" id="minute-search-input">
                        </div>
                        <div class="minute-list-container" id="minute-list">
                            <!-- Minute items will be added by JS -->
                        </div>
                    </div>

                    <div class="calendar">
                        <?php  
                            $showAddEvents = false;
                            require_once("../app/views/components/Calender/calandar-view.php"); // Call the calendar component
                        ?>
                    </div>
                </div>

                <div class="action-buttons">
                    <button class="btn btn-secondary" id="cancel-btn">Cancel</button>
                    <button class="btn btn-primary" id="view-minute-report-btn">View Minute Report</button>
                </div>
            </div>
        </div>
    </div>
<script>
    const minutes = <?php echo json_encode($minutes); ?>;

    const minuteListContainer = document.getElementById('minute-list');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const searchInput = document.getElementById('minute-search-input');
    const viewMinuteReportBtn = document.getElementById('view-minute-report-btn');
    const cancelBtn = document.getElementById('cancel-btn');
    viewMinuteReportBtn.disabled = true; // Disable button initially
    viewMinuteReportBtn.style.cursor = 'not-allowed'; // Change cursor to not-allowed
    viewMinuteReportBtn.style.opacity = '0.4'; // Change opacity to indicate disabled state

    let selectedMinuteId = null;
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

    // Render minute list
    function renderMinutes() {
        // Filter minutes based on current filter and search term
        const filteredMinutes = minutes.filter(minute => {
            const matchesFilter = currentFilter === 'all' || minute.meeting_type.toLowerCase() === currentFilter;
            const matchesSearch = minute.created_date.includes(searchTerm);
            return matchesFilter && matchesSearch;
        });

        // Sort minutes by date descending
        filteredMinutes.sort((a, b) => {
            return new Date(b.created_date) - new Date(a.created_date);
        });

        // Clear the container
        minuteListContainer.innerHTML = '';

        // If no minutes match the criteria
        if (filteredMinutes.length === 0) {
            minuteListContainer.innerHTML = `
                <div class="no-minutes">
                    <p>No minutes found</p>
                    <p class='no-minutes-detail'>Please adjust your filters or search criteria.</p>
                </div>
            `;
            return;
        }

        // Render each minute
        filteredMinutes.forEach(minute => {
            const minuteItem = document.createElement('div');
            minuteItem.className = `minute-item ${minute.minute_id === selectedMinuteId ? 'selected' : ''}`;
            minuteItem.dataset.id = minute.minute_id;

            minuteItem.innerHTML = `
                <div class="minute-icon ${minute.meeting_type}">${minute.meeting_type.toUpperCase().substring(0, 1)}</div>
                <div class="minute-details">
                    <div class="minute-name">${minute.title}</div>
                    <div class="minute-date">${formatDate(minute.created_date)}</div>
                </div>
            `;

            minuteItem.addEventListener('click', () => {
                // Deselect previously selected minute
                const previouslySelected = document.querySelector('.minute-item.selected');
                if (previouslySelected) {
                    previouslySelected.classList.remove('selected');
                }

                // Select this minute
                minuteItem.classList.add('selected');
                selectedMinuteId = minute.minute_id;

                // Enable view button if a minute is selected
                viewMinuteReportBtn.disabled = false;
                viewMinuteReportBtn.style.cursor = 'pointer'; // Change cursor to pointer
                viewMinuteReportBtn.style.opacity = '1'; // Change opacity to indicate enabled state
            });

            minuteListContainer.appendChild(minuteItem);
        });
    }

    // Initialize filter buttons
    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            currentFilter = button.dataset.filter;
            renderMinutes();
        });
    });

    // Initialize search
    searchInput.addEventListener('input', (e) => {
        searchTerm = e.target.value;
        renderMinutes();
    });

    // Initialize buttons
    viewMinuteReportBtn.addEventListener('click', () => {
        if (selectedMinuteId) {
            window.location.href = `<?=ROOT?>/secretary/viewminutereports?minute=${selectedMinuteId}`;
        }
    });

    cancelBtn.addEventListener('click', () => {
        window.history.back();
    });

    // Initial render
    renderMinutes();
</script>
</body>
</html>
    </script>
    </body>