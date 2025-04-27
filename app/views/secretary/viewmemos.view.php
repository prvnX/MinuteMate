<!DOCTYPE html>
<html lang = "en">

<head>
    
    <meta charset= "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>View memo</title>
     <link rel="stylesheet" href="<?=ROOT?>/assets/css/secretary/viewmemo.style.css">
     <link rel="icon" href="<?=ROOT?>/img.png" type="image">

</head>

<body>

<?php
    $user="secretary";
    $memocart="memocart";   //use memocart-dot if there is a memo in the cart change with db
    $notification="notification"; //use notification-dot if there's a notification
    $menuItems = [ "home" => ROOT."/secretary",$memocart => ROOT."/secretary/memocart", $notification => ROOT."/secretary/notifications", "profile" => ROOT."/secretary/viewprofile"]; //pass the menu items here (key is the name of the page, value is the url)
    
    echo "<div class='memo-list-navbar'>";
    require_once("../app/views/components/new_navbar.php");
    echo "</div>";
    require_once("../app/views/components/sec_sidebar.php");
    $memoList=$data['memos'];
    

   ?>
    <header class="page-header">
         <h1>Memo List </h1>
         <p class="subtitle">View all memos</p>
    </header>

   

<div class="main-container">
    <div class="content-area">
        <div class="memolist" id="memolist">
            <?php foreach ($memoList as $memoitem): ?>
                <div class="memoitem" data-type=<?= htmlspecialchars(strtoupper($memoitem->meeting_type)) ?> data-date=<?= htmlspecialchars($memoitem->date) ?> data-submitted-by=<?= htmlspecialchars(strtolower($memoitem->submitted_by)) ?> data-id=<?= htmlspecialchars($memoitem->memo_id) ?>">
                    <div class="memocontent">
                    <h3><?= htmlspecialchars($memoitem->memo_title) ?></h3>
                    <div class="memo-meta">
                            <span class="memo-id"><?=htmlspecialchars($memoitem->meeting_id)?></span>
                            <span class="department-badge <?= htmlspecialchars($memoitem->meeting_type)?>"><?=htmlspecialchars(strtoupper($memoitem->meeting_type))?></span>
                        </div>
                    <p>Memo ID: <?= htmlspecialchars($memoitem->memo_id) ?></p>
                </div>
            <a href="<?=ROOT?>/secretary/viewmemodetails/?memo_id=<?= $memoitem->memo_id ?>">
                <button class="viewbtn">View</button>
            </a>
                </div>
            <?php endforeach; ?>
        </div>

        <div id="empty-state" class="empty-state" style="display: none;">
                <div class="empty-icon"></div>
                <h3>No memos found</h3>
                <p>Try adjusting the filters</p>
        </div>
     </div>

    <div class="sidebar">
                <div class="filter-sidebar">
                    <h2 class="filter-header">Apply Filters Here</h2>

                    <div class="filter-section">
                    <h3 class="filter-section-title">Filter By Minute Id</h3>
                    <div class="id-inputs">
                        
                        <input type="text" id="id-label" class="id-input" placeholder="Id">
                    </div>
                    </div>

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

                    <div class="filter-section">
                        <h3 class="filter-section-title">Filter By Submitted Member</h3>
                        <select id="member-select" class="date-input">
                            <option value="">-- Select Member --</option>
                            <?php foreach ($submittedMembers as $member): ?>
                                <option value="<?= htmlspecialchars(strtolower($member->full_name)) ?>">
                                    <?= htmlspecialchars($member->full_name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>



                    <div class="filter-btns">
                        <button id="apply-filters" class="filter-button apply-button">Apply Filters</button>
                        <button id="clear-filters" class="filter-button clear-button">Clear Filters</button>
                    </div>
                </div>
    </div>
</div>

        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dateFrom = document.getElementById('date-from');
            const dateTo = document.getElementById('date-to');
            const iudCheckbox = document.getElementById('iud-checkbox');
            const rhdCheckbox = document.getElementById('rhd-checkbox');
            const bomCheckbox = document.getElementById('bom-checkbox');
            const syndicateCheckbox = document.getElementById('syndicate-checkbox');
            const memberSelect = document.getElementById('member-select');
            const idInserted =  document.getElementById('id-label');


            const applyButton = document.getElementById('apply-filters');
            const clearButton = document.getElementById('clear-filters');
            const memoList = document.getElementById('memolist');
            const emptyState = document.getElementById('empty-state');

            applyButton.addEventListener('click', () => {
                const selectedTypes = [];
                if (iudCheckbox.checked) selectedTypes.push('IUD');
                if (rhdCheckbox.checked) selectedTypes.push('RHD');
                if (bomCheckbox.checked) selectedTypes.push('BOM');
                if (syndicateCheckbox.checked) selectedTypes.push('SYN');

                const fromDate = dateFrom.value ? new Date(dateFrom.value) : null;
                const toDate = dateTo.value ? new Date(dateTo.value) : null;
                const selectedMember = memberSelect.value;
                const insertedId = idInserted.value.trim();
  

                const cards = memoList.querySelectorAll('.memoitem');
                let visibleCount = 0;

                cards.forEach(card => {
                    const type = card.dataset.type;
                    const date = new Date(card.dataset.date);
                    const submitter = card.dataset.submittedBy;
                    const id = card.dataset.id;

                    let isVisible = true;

                    if (selectedTypes.length && !selectedTypes.includes(type)) isVisible = false;
                    if (fromDate && date < fromDate) isVisible = false;
                    if (toDate && date > toDate) isVisible = false;
                    if (selectedMember && submitter !== selectedMember) isVisible = false;
                    if(insertedId && !id.includes(insertedId)) isVisible = false;

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
                memberSelect.value = '';

                document.querySelectorAll('.memoitem').forEach(card => {
                    card.classList.remove('hidden');
                });
                emptyState.style.display = 'none';
            });
        });
    </script>
</body>
</html>