<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/searching.style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="<?=ROOT?>/img.png" type="image">
    <title>Search Results</title>
</head>

<body>
<?php
    $userType=($_SESSION['userDetails']->role);
    $user = $userType;
    $notification = "notification";
    $menuItems = [
        "home" => ROOT . "/".$user,
        $notification => ROOT . "/".$user."/notifications",
        "profile" => ROOT . "/".$user."/viewprofile"
    ];


    echo "<div class='minute-list-navbar'>";
    require_once("../app/views/components/new_navbar.php");
    echo "</div>";
    switch ($userType) {
        case 'secretary':
            require_once("../app/views/components/sec_sidebar.php");
            break;
        case 'lecturer':
            require_once("../app/views/components/lec_sidebar.php");
            break;
        case 'studentrep':
            require_once("../app/views/components/std_sidebar.php");
            break;
    }
    $memoList = [
        (object)[
            "memo_id" => "MEM001",
            "meeting_id" => "MTG101",
            "meeting_type" => "iud",
            "submitted_date" => "2025-04-02",
            "submitted_by" => "John Doe"
        ],
        (object)[
            "memo_id" => "MEM002",
            "meeting_id" => "MTG102",
            "meeting_type" => "rhd",
            "submitted_date" => "2025-04-06",
            "submitted_by" => "Jane Smith"
        ],
        (object)[
            "memo_id" => "MEM003",
            "meeting_id" => "MTG103",
            "meeting_type" => "bom",
            "submitted_date" => "2025-03-29",
            "submitted_by" => "Alice Johnson"
        ],
        (object)[
            "memo_id" => "MEM004",
            "meeting_id" => "MTG104",
            "meeting_type" => "syndicate",
            "submitted_date" => "2025-04-11",
            "submitted_by" => "Bob Williams"
        ],
        (object)[
            "memo_id" => "MEM003",
            "meeting_id" => "MTG103",
            "meeting_type" => "bom",
            "submitted_date" => "2025-03-29",
            "submitted_by" => "Alice Johnson"
        ],
        (object)[
            "memo_id" => "MEM004",
            "meeting_id" => "MTG104",
            "meeting_type" => "syndicate",
            "submitted_date" => "2025-04-11",
            "submitted_by" => "Bob Williams"
        ],
        (object)[
            "memo_id" => "MEM003",
            "meeting_id" => "MTG103",
            "meeting_type" => "bom",
            "submitted_date" => "2025-03-29",
            "submitted_by" => "Alice Johnson"
        ],
        (object)[
            "memo_id" => "MEM004",
            "meeting_id" => "MTG104",
            "meeting_type" => "syndicate",
            "submitted_date" => "2025-04-11",
            "submitted_by" => "Bob Williams"
        ],
        (object)[
            "memo_id" => "MEM003",
            "meeting_id" => "MTG103",
            "meeting_type" => "bom",
            "submitted_date" => "2025-03-29",
            "submitted_by" => "Alice Johnson"
        ],
        (object)[
            "memo_id" => "MEM004",
            "meeting_id" => "MTG104",
            "meeting_type" => "syndicate",
            "submitted_date" => "2025-04-11",
            "submitted_by" => "Bob Williams"
        ]
    ];
    $minuteList = [
        (object)[
            "Minute_ID" => "MNT001",
            "meeting_id" => "MTG001",
            "meeting_type" => "iud",
            "date" => "2025-04-01",
            "start_time" => "09:00:00"
        ],
        (object)[
            "Minute_ID" => "MNT002",
            "meeting_id" => "MTG002",
            "meeting_type" => "rhd",
            "date" => "2025-04-05",
            "start_time" => "10:30:00"
        ],
        (object)[
            "Minute_ID" => "MNT003",
            "meeting_id" => "MTG003",
            "meeting_type" => "bom",
            "date" => "2025-03-28",
            "start_time" => "11:00:00"
        ],
        (object)[
            "Minute_ID" => "MNT004",
            "meeting_id" => "MTG004",
            "meeting_type" => "syndicate",
            "date" => "2025-04-10",
            "start_time" => "13:00:00"
        ],
        (object)[
            "Minute_ID" => "MNT004",
            "meeting_id" => "MTG004",
            "meeting_type" => "syndicate",
            "date" => "2025-04-10",
            "start_time" => "13:00:00"
        ],
        (object)[
            "Minute_ID" => "MNT004",
            "meeting_id" => "MTG004",
            "meeting_type" => "syndicate",
            "date" => "2025-04-10",
            "start_time" => "13:00:00"
        ],
        (object)[
            "Minute_ID" => "MNT004",
            "meeting_id" => "MTG004",
            "meeting_type" => "syndicate",
            "date" => "2025-04-10",
            "start_time" => "13:00:00"
        ],
        (object)[
            "Minute_ID" => "MNT004",
            "meeting_id" => "MTG004",
            "meeting_type" => "syndicate",
            "date" => "2025-04-10",
            "start_time" => "13:00:00"
        ]
    ];
    $minuteListSize= count($minuteList);
    $memoListSize= count($memoList);
?>
    <header class="page-header">
        <h1>Search Results for "<?=$searchtxt?>"</h1>
        <div class="search-item-select">
        <div class="btn-container">
        <button class="search-item-btn clicked-btn" id="minutes-btn" onclick="handle_minutetab_click()">Minutes</button>
        <button class="search-item-btn" id="memo-btn"onclick="handle_memotab_click()">Memos</button>

    </div> 
    <hr class="main-hr">   
    </div>
    <div class="result_count"><span id="count"><?=$minuteListSize?> </span><span id="type"> minutes</span> found.</div>

    </header>

    <div class="main-container">
        
        <div class="minute-results">
        <div class="content-area">
            <div class="minutes-list" id="minutes-list">
                <?php foreach($minuteList as $minuteCard): ?>
                <div class="minute-card" data-type=<?=htmlspecialchars(strtoupper($minuteCard->meeting_type))?> data-date=<?=htmlspecialchars($minuteCard->date)?> >
                    <div class="minute-info">
                        <h2 class="minute-title">Minute - <?=htmlspecialchars($minuteCard->Minute_ID)?></h2>
                        <div class="minute-meta">
                            <span class="minute-id"><?=htmlspecialchars($minuteCard->meeting_id)?></span>
                            <span class="department-badge <?= htmlspecialchars($minuteCard->meeting_type)?>"><?=htmlspecialchars(strtoupper($minuteCard->meeting_type))?></span>
                        </div>
                        <div class="minute-details">
                            <div class="detail-item"><i class="far fa-calendar"></i><span><?=htmlspecialchars((new DateTime($minuteCard->date))->format('d, F Y'))?></span></div>
                            <div class="detail-item"><i class="far fa-clock"></i><span><?=htmlspecialchars(substr($minuteCard->start_time,0,-3))?></span></div>
                        </div>
                    </div>
                    <div class="minute-actions">
                        <a href="<?=ROOT?>/secretary/viewminute?minuteID=<?=$minuteCard->Minute_ID?>" class="action-button view-button">View</a>
                        <a href="<?=ROOT?>/download?minuteID=<?=$minuteCard->Minute_ID?>" class="action-button download-button">Download</a>
                    </div>
                </div>
                <?php endforeach; ?>
           
            </div>

            <div id="empty-state" class="empty-state" style="display: none;">
                <div class="empty-icon"></div>
                <h3>No minutes found</h3>
                <p>Try adjusting the filters</p>
            </div>
        </div>

        <div class="sidebar">
            <div class="filter-sidebar">
                <h2 class="filter-header">Apply Filters Here</h2>

                <div class="filter-section">
                    <h3 class="filter-section-title">Filter By Meeting Dates</h3>
                    <div class="date-inputs">
                        <label for="date-from" class="date-label">From</label>
                        <input type="date" id="date-from" class="date-input" placeholder="From">
                        <label for="date-to" class="date-label">To</label>
                        <input type="date" id="date-to" class="date-input" placeholder="To">
                    </div>
                </div>

                <div class="filter-section">
                    <h3 class="filter-section-title">Filter By Meeting Type</h3>
                    <div class="checkbox-group">
                        <div class="checkbox-item"><input type="checkbox" id="iud-checkbox" value="IUD"><label for="iud-checkbox">IUD</label></div>
                        <div class="checkbox-item"><input type="checkbox" id="rhd-checkbox" value="RHD"><label for="rhd-checkbox">RHD</label></div>
                        <div class="checkbox-item"><input type="checkbox" id="bom-checkbox" value="BOM"><label for="bom-checkbox">BOM</label></div>
                        <div class="checkbox-item"><input type="checkbox" id="syndicate-checkbox" value="SYN"><label for="syndicate-checkbox">Syndicate</label></div>
                    </div>
                </div>


            <div class="filter-btns">
                <button id="apply-filters" class="filter-button apply-button">Apply Filters</button>
                <button id="clear-filters" class="filter-button clear-button">Clear Filters</button>
            </div>
            </div>
        </div>
        </div>

        <div class="memo-results hide">
            <div class="content-area">
                <div class="memo-list" id="memos-list">
                <?php foreach($memoList as $memo): ?>
                <div class="memo-card" 
                    data-type="<?=htmlspecialchars(strtoupper($memo->meeting_type))?>" 
                    data-date="<?=htmlspecialchars($memo->submitted_date)?>" 
                    data-submittedBy="<?=htmlspecialchars($memo->submitted_by)?>">
                <div class="memo-info">
                <h2 class="memo-title">Memo - <?=htmlspecialchars($memo->memo_id)?></h2>
                <div class="memo-meta">
                    <span class="memo-id"><?=htmlspecialchars($memo->meeting_id)?></span>
                    <span class="department-badge <?=htmlspecialchars($memo->meeting_type)?>">
                        <?=htmlspecialchars(strtoupper($memo->meeting_type))?>
                    </span>
                </div>
                <div class="memo-details">
                    <div class="detail-item"><i class="far fa-calendar"></i>
                        <span><?=htmlspecialchars((new DateTime($memo->submitted_date))->format('d, F Y'))?></span>
                    </div>
                    <div class="detail-item"><i class="fas fa-user"></i>
                        <span><?=htmlspecialchars($memo->submitted_by)?></span>
                    </div>
                </div>
            </div>
            <div class="memo-actions">
                <a href="<?=ROOT?>/secretary/viewmemo?memoID=<?=$memo->memo_id?>" class="action-button view-button">View</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div id="memos-empty-state" class="empty-state" style="display: none;">
        <div class="empty-icon"></div>
        <h3>No memos found</h3>
        <p>Try adjusting the filters</p>
    </div>

    </div>
        <div class="sidebar">
            <div class="filter-sidebar">
                <h2 class="filter-header">Apply Filters Here</h2>

                <div class="filter-section">
                    <h3 class="filter-section-title">Filter By Submitted Dates</h3>
                    <div class="date-inputs">
                        <label for="memos-date-from" class="date-label">From</label>
                        <input type="date" id="memos-date-from" class="date-input" placeholder="From">
                        <label for="memos-date-to" class="date-label">To</label>
                        <input type="date" id="memos-date-to" class="date-input" placeholder="To">
                    </div>
                </div>

                <div class="filter-section">
                    <h3 class="filter-section-title">Filter By Submitted Meeting Type</h3>
                    <div class="checkbox-group">
                        <div class="checkbox-item"><input type="checkbox" id="memos-iud-checkbox" value="IUD"><label for="iud-checkbox">IUD</label></div>
                        <div class="checkbox-item"><input type="checkbox" id="memos-rhd-checkbox" value="RHD"><label for="rhd-checkbox">RHD</label></div>
                        <div class="checkbox-item"><input type="checkbox" id="memos-bom-checkbox" value="BOM"><label for="bom-checkbox">BOM</label></div>
                        <div class="checkbox-item"><input type="checkbox" id="memos-syndicate-checkbox" value="SYN"><label for="syndicate-checkbox">Syndicate</label></div>
                    </div>
                </div>
                <?php 
                $memoSubmitters = [
                    "John Doe",
                    "Jane Smith",
                    "Alice Johnson",
                    "Bob Williams",
                    "Charlie Adams",
                    "Diana Lewis",
                    "Ethan Clark",
                    "Fiona Thompson",
                    "George Baker",
                    "Hannah Davis"
                ];
                ?>

                <div class="filter-section">
                    <h3 class="filter-section-title">Filter By Submitted Member</h3>
                    <div class="select-group">
                        <select id="memos-submitted-by" class="select-input">
                            <option value="all">All</option>
                            <?php foreach($memoSubmitters as $submitter): ?>
                                <option value="<?=htmlspecialchars($submitter)?>"><?=htmlspecialchars($submitter)?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>


            <div class="filter-btns">
                <button id="memos-apply-filters" class="filter-button apply-button">Apply Filters</button>
                <button id="memos-clear-filters" class="filter-button clear-button">Clear Filters</button>
            </div>
            </div>
        </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            //minutes
            const dateFrom = document.getElementById('date-from');
            const dateTo = document.getElementById('date-to');
            const iudCheckbox = document.getElementById('iud-checkbox');
            const rhdCheckbox = document.getElementById('rhd-checkbox');
            const bomCheckbox = document.getElementById('bom-checkbox');
            const syndicateCheckbox = document.getElementById('syndicate-checkbox');

            const applyButton = document.getElementById('apply-filters');
            const clearButton = document.getElementById('clear-filters');
            const minutesList = document.getElementById('minutes-list');
            const emptyState = document.getElementById('empty-state');

            //memos
            const memosDateFrom = document.getElementById('memos-date-from');
            const memosDateTo = document.getElementById('memos-date-to');
            const memosIudCheckbox = document.getElementById('memos-iud-checkbox');
            const memosRhdCheckbox = document.getElementById('memos-rhd-checkbox');
            const memosBomCheckbox = document.getElementById('memos-bom-checkbox');
            const memosSyndicateCheckbox = document.getElementById('memos-syndicate-checkbox');
            const memosSubmitterSelect = document.getElementById('memos-submitted-by');

            const memosApplyButton = document.getElementById('memos-apply-filters');
            const memosClearButton = document.getElementById('memos-clear-filters');
            const memosList = document.getElementById('memos-list');
            const memosEmptyState = document.getElementById('memos-empty-state');

            // Apply filters for minutes

            applyButton.addEventListener('click', () => {
                const selectedTypes = [];
                if (iudCheckbox.checked) selectedTypes.push('IUD');
                if (rhdCheckbox.checked) selectedTypes.push('RHD');
                if (bomCheckbox.checked) selectedTypes.push('BOM');
                if (syndicateCheckbox.checked) selectedTypes.push('SYN');

                const fromDate = dateFrom.value ? new Date(dateFrom.value) : null;
                const toDate = dateTo.value ? new Date(dateTo.value) : null;
  

                const cards = minutesList.querySelectorAll('.minute-card');
                let visibleCount = 0;

                cards.forEach(card => {
                    const type = card.dataset.type;
                    const date = new Date(card.dataset.date);
                    

                    let isVisible = true;

                    if (selectedTypes.length && !selectedTypes.includes(type)) isVisible = false;
                    if (fromDate && date < fromDate) isVisible = false;
                    if (toDate && date > toDate) isVisible = false;
                    if(!isVisible){
                        card.classList.add('hidden');
                    }
                    if (isVisible){
                        visibleCount++;
                        card.classList.remove('hidden');
                    } 
                });

                emptyState.style.display = visibleCount === 0 ? 'flex' : 'none';
            });

            clearButton.addEventListener('click', () => {
                dateFrom.value = '';
                dateTo.value = '';
                iudCheckbox.checked = false;
                rhdCheckbox.checked = false;
                bomCheckbox.checked = false;
                syndicateCheckbox.checked = false;

                document.querySelectorAll('.minute-card').forEach(card => {
                    card.classList.remove('hidden');
                });
                emptyState.style.display = 'none';
            });

            // Apply filters for memos
            memosApplyButton.addEventListener('click', () => {
                const selectedTypes = [];
                if (memosIudCheckbox.checked) selectedTypes.push('IUD');
                if (memosRhdCheckbox.checked) selectedTypes.push('RHD');
                if (memosBomCheckbox.checked) selectedTypes.push('BOM');
                if (memosSyndicateCheckbox.checked) selectedTypes.push('SYN');

                const fromDate = memosDateFrom.value ? new Date(memosDateFrom.value) : null;
                const toDate = memosDateTo.value ? new Date(memosDateTo.value) : null;

                const cards = memosList.querySelectorAll('.memo-card');
                let visibleCount = 0;
                cards.forEach(card => {
                    const type = card.dataset.type;
                    const date = new Date(card.dataset.date);
                    const submitter = card.getAttribute('data-submittedBy').toLowerCase();
                    const selectedSubmitter = memosSubmitterSelect.value.toLowerCase();
                    console.log(selectedSubmitter);
                    console.log(submitter);

                    let isVisible = true;

                    if (selectedTypes.length && !selectedTypes.includes(type)) isVisible = false;
                    if (fromDate && date < fromDate) isVisible = false;
                    if (toDate && date > toDate) isVisible = false;
                    if (selectedSubmitter !== 'all' && selectedSubmitter !== '') {
                        if (submitter !== selectedSubmitter) isVisible = false;
                    }
                    if(!isVisible){
                        card.classList.add('hidden');
                    }
                    if (isVisible){
                        visibleCount++;
                        card.classList.remove('hidden');
                    }
                });
                memosEmptyState.style.display = visibleCount === 0 ? 'flex' : 'none';
            });
            memosClearButton.addEventListener('click', () => {
                memosDateFrom.value = '';
                memosDateTo.value = '';
                memosIudCheckbox.checked = false;
                memosRhdCheckbox.checked = false;
                memosBomCheckbox.checked = false;
                memosSyndicateCheckbox.checked = false;
                memosSubmitterSelect.value = 'all';


                document.querySelectorAll('.memo-card').forEach(card => {
                    card.classList.remove('hidden');
                });
                memosEmptyState.style.display = 'none';
            });
        });

        function handle_minutetab_click(){
            document.querySelector('.minute-results').classList.remove('hide');
            document.querySelector('.memo-results').classList.add('hide');
            document.querySelector("#minutes-btn").classList.add('clicked-btn');
            document.querySelector("#memo-btn").classList.remove('clicked-btn');
            document.querySelector("#count").innerText = <?=$minuteListSize?>;
            document.querySelector("#type").innerText = " Minutes";
        }
        function handle_memotab_click(){
            document.querySelector('.minute-results').classList.add('hide');
            document.querySelector('.memo-results').classList.remove('hide');
            document.querySelector("#memo-btn").classList.add('clicked-btn');
            document.querySelector("#minutes-btn").classList.remove('clicked-btn');
            document.querySelector("#count").innerText = <?=$memoListSize?>;
            document.querySelector("#type").innerText = " Memos";
        }   
    </script>
</body>
</html>