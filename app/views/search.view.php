<!DOCTYPE html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/search.style.css">
</head>
<body>

<?php
    require_once("../app/views/components/navbar.php");
    $memoResultsjson = json_encode($memoResults);
    // $minuteResultsjson = json_encode($minuteResults);
    $memberList= ["John Doe", "Jane Doe", "John Smith", "Jane Smith", "John Doe", "Jane Doe", "John Smith", "Jane Smith", "John Doe", "Jane Doe", "John Smith", "Jane Smith", "John Doe", "Jane Doe", "John Smith", "Jane Smith"];
?>
<div class="search-heading-container">
    <h1 class="search-heading">Search Results for "<?=$searchtxt?>"</h1>
</div>
<div class="memo-results">
    <p><span class="search-count"><?= count($memoResults) ?> </span>memos found ,  <button type="button" class="switch-btn" onclick="document.querySelector('.memo-results').style.display='none'">View the found minutes</button> </p>
    <div class="memo-results-container">
        <div class="memo-results-list">
           <!-- Results will be displayed here -->
            </div>
            <div class="memo-results-filter">
                <p class="filter-heading">Apply Filters Here</p>
                <hr>
                <div class="date-filter">
                <p>Filter By Submitted  Dates</p>
                    <label for="date">From</label>
                    <input type="date" name="from-date" id="date">
                    <label for="date">To</label>
                    <input type="date" name="end- date" id="date">
                </div>
                <div class="type-filter">
                    <p>Filter By Meeting Type</p>
                 
                    <label class="checkbox-container iud" >
                    <input type="checkbox" name="iud" id="iud"/>
                    <span class="checkmark"></span>
                    IUD
                    </label>
                    <label class="checkbox-container rhd">
                    <input type="checkbox" name="rhd" id="iud"/>
                    <span class="checkmark"></span>
                    RHD
                    </label>
                    <label class="checkbox-container bom">
                    <input type="checkbox" name="bom" id="bom"/>
                    <span class="checkmark"></span>
                     BOM
                    </label>
                    <label class="checkbox-container syndicate">
                    <input type="checkbox" name="syndicate" id="syndicate" />
                    <span class="checkmark"></span>
                        Syndicate
                    </label>
                    </div>

                <div class="name-filter">
                    <p>Filter By Submitted By</p>
                    <select name="submitted-by" id="submitted-by">
                        <option value="all">All</option>
                        <?php foreach ($memberList as $member): ?>
                            <option value="<?= $member ?>"><?= $member ?></option>
                        <?php endforeach; ?>
                        </select>
                </div>
                <div class="filter-btns-list">
                    <button class="filter-btns">Apply Filters</button>
                    <button class="filter-btns">Clear Filters</button>
                </div>


    </div>
<script>
    const data = <?php echo $memoResultsjson; ?>;
    const memoResultsList = document.querySelector(".memo-results-list");
    function displayMemoResults(dataList){
        memoResultsList.innerHTML = "";
        if (dataList.length === 0){
            memoResultsList.innerHTML = "<p class='no-memos'>0 memos found</p>";
            return;
        }
        dataList.forEach(memo => {
            const memoItem = document.createElement("div");
            memoItem.classList.add("memo-item");
            memoItem.innerHTML = `
                <div class="memo-content">
                    <p class="memo-title">${memo.title}</p>
                    <p>By, ${memo.submitted_by}</p>
                    <p>- ${memo.date}</p>
                    <div class="view-btns">
                    <button class="view-memo-btn" onclick="viewMemo('${memo.id}')">View Memo</button>
                    <button class="view-memo-btn" onclick="viewMemoReport('${memo.id}')">View Report</button>
                    </div>
                </div>
            `;
            memoResultsList.appendChild(memoItem);
        });
    }
    displayMemoResults(data);
</script>


</body>