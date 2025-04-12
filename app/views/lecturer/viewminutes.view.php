<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/secretary/viewminute.style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="<?=ROOT?>/img.png" type="image">
    <title>Minutes List</title>
</head>

<body>
<?php
    $user = "lecturer";
    $notification = "notification";
    $menuItems = [
        "home" => ROOT . "/lecturer",
        $notification => ROOT . "/lecturer/notifications",
        "profile" => ROOT . "/lecturer/viewprofile"
    ];
    echo "<div class='minute-list-navbar'>";
    require_once("../app/views/components/new_navbar.php");
    echo "</div>";
    require_once("../app/views/components/lec_sidebar.php");
    $minuteList=$data['minutes'];
?>
    <header class="page-header">
        <h1>Minutes</h1>
        <p class="subtitle">View and manage all meeting minutes</p>
    </header>

    <div class="main-container">
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
                        <a href="<?=ROOT?>/lecturer/viewminute?minuteID=<?=$minuteCard->Minute_ID?>" class="action-button view-button">View</a>
                        <a href="#" class="action-button download-button">Download</a>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
                    const submitter = card.dataset.submittedBy;

                    let isVisible = true;

                    if (selectedTypes.length && !selectedTypes.includes(type)) isVisible = false;
                    if (fromDate && date < fromDate) isVisible = false;
                    if (toDate && date > toDate) isVisible = false;
                    if(!isVisible){
                        card.classList.add('hidden');
                    }
                    if (isVisible) visibleCount++;
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
        });
    </script>
</body>
</html>