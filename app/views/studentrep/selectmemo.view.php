<head>
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/studentrep/selectmemo.style.css">
    <title>Please Select a memo</title>
    <link rel="icon" href="<?=ROOT?>/img.png" type="image">

    
</head>
<body>
<?php
    $user="studentrep";
    $memocart="memocart";   //use memocart-dot if there is a memo in the cart change with db
    $notification="notification"; //use notification-dot if there's a notification
    $menuItems = [ "home" => ROOT."/studentrep",$memocart => ROOT."/studentrep/memocart", $notification => ROOT."/studentrep/notifications", "profile" => ROOT."/studentrep/viewprofile"]; //pass the menu items here (key is the name of the page, value is the url)
    require_once("../app/views/components/new_navbar.php"); //call the navbar component
  
    ?>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">Select a Memo</h1>
            </div>
            <div class="card-body">
                <div class="memo-selection">
                    <!-- Memo List Section -->
                    <div class="memo-list">
                        <div class="memo-list-header">
                            <h2 class="memo-list-title">Available Memos </h2>
                            <div class="memo-filter">
                                <button class="filter-btn active" data-filter="all">All</button>
                                <button class="filter-btn" data-filter="iud">IUD</button>
                                <button class="filter-btn" data-filter="rhd">RHD</button>
                                <button class="filter-btn" data-filter="bom">BOM</button>
                                <button class="filter-btn" data-filter="syn">SYN</button>
                            </div>
                        </div>
                        <div class="memo-search">
                            <input type="text" placeholder="Find the memo by date...(YYYY-MM-DD)" id="memo-search-input">
                        </div>
                        <div class="memo-list-container" id="memo-list">
                            <!-- Memo items will be added by JS -->
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
                    <button class="btn btn-primary" id="view-memo-report-btn">View Memo Report</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        const memos= <?php echo json_encode($memos); ?>;
        
        const memoListContainer = document.getElementById('memo-list');
        const filterButtons = document.querySelectorAll('.filter-btn');
        const searchInput = document.getElementById('memo-search-input');
        const viewMemoReportBtn = document.getElementById('view-memo-report-btn');
        const cancelBtn = document.getElementById('cancel-btn');
        viewMemoReportBtn.disabled = true; // Disable button initially
        viewMemoReportBtn.style.cursor = 'not-allowed'; // Change cursor to not-allowed
        viewMemoReportBtn.style.opacity = '0.4'; // Change opacity to indicate disabled state

        
        let selectedMemoId = null;
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
        // Render memo list
        function renderMemos() {
            // Filter memos based on current filter and search term
            const filteredMemos = memos.filter(memo => {
                const matchesFilter = currentFilter === 'all' || memo.meeting_type.toLowerCase() === currentFilter;
                const matchesSearch = memo.submitted_date.includes(searchTerm);
                return matchesFilter && matchesSearch;
            });

            // Sort memos by date descending
            filteredMemos.sort((a, b) => {
                return new Date(b.submitted_date) - new Date(a.submitted_date);
            });

            // Clear the container
            memoListContainer.innerHTML = '';

            // If no memos match the criteria
            if (filteredMemos.length === 0) {
                memoListContainer.innerHTML = `
                    <div class="no-memos">
                        <p>No memos found</p>
                        <p class='no-memos-detail'>Please adjust your filters or search criteria.</p>
                    </div>
                `;
                return;
            }

            // Render each memo
            filteredMemos.forEach(memo => {
                const memoItem = document.createElement('div');
                memoItem.className = `memo-item ${memo.memo_id === selectedMemoId ? 'selected' : ''}`;
                memoItem.dataset.id = memo.memo_id;

                memoItem.innerHTML = `
                    <div class="memo-icon ${memo.meeting_type}">${memo.meeting_type.toUpperCase().substring(0, 1)}</div>
                    <div class="memo-details">
                        <div class="memo-name">${memo.memo_title}</div>
                        <div class="memo-date">${formatDate(memo.submitted_date)}</div>
                    </div>
                `;

                memoItem.addEventListener('click', () => {
                    // Deselect previously selected memo
                    const previouslySelected = document.querySelector('.memo-item.selected');
                    if (previouslySelected) {
                        previouslySelected.classList.remove('selected');
                    }

                    // Select this memo
                    memoItem.classList.add('selected');
                    selectedMemoId = memo.memo_id;

                    // Enable view button if a memo is selected
                    viewMemoReportBtn.disabled = false;
                    viewMemoReportBtn.style.cursor = 'pointer'; // Change cursor to pointer
                    viewMemoReportBtn.style.opacity = '1'; // Change opacity to indicate enabled state
                });

                memoListContainer.appendChild(memoItem);
            });
        }
        
 
       
              
    // Initialize filter buttons
    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            currentFilter = button.dataset.filter;
            renderMemos();
        });
    });

    // Initialize search
    searchInput.addEventListener('input', (e) => {
        searchTerm = e.target.value;
        renderMemos();
    });

    // Initialize buttons
    viewMemoReportBtn.addEventListener('click', () => {
        if (selectedMemoId) {
            window.location.href = `<?=ROOT?>/studentrep/viewmemoreport?memo=${selectedMemoId}`;
        }
    });

    cancelBtn.addEventListener('click', () => {
        window.history.back();
    });

    // Initial render
    renderMemos();

    </script>
</body>
</html>
    </script>
    </body>
        
        