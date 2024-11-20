const data = <?php echo $memoResultsjson; ?>;
    const memoResultsList = document.querySelector(".memo-results-list");
    const minutesData = <?php echo $minuteResultsjson; ?>;
    const minuteResultsList = document.querySelector(".minute-results-list");
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

    function displayMinuteResults(dataList){
        minuteResultsList.innerHTML = "";
        if (dataList.length === 0){
            minuteResultsList.innerHTML = "<p class='no-memos'>0 contents found</p>";
            return;
        }
        dataList.forEach(minute => {
            const minuteItem = document.createElement("div");
            minuteItem.classList.add("minute-item");
            minuteItem.innerHTML = `
                <div class="minute-content">
                    <p class="minute-title">${minute.content_title}</p>
                    <p>Meeting type : ${minute.meeting_type}</p>
                    <p>- ${minute.meeting_date}</p>
                    <div class="view-btns">
                    <button class="view-minute-btn" onclick="viewContent('${minute.content_id}')">View Content</button>
                    <button class="view-minute-btn" onclick="viewMinute('${minute.minute_id}')">View Minute</button>
                    <button class="view-minute-btn" onclick="viewMinuteReport('${minute.minute_id}')">View Report</button>
                </div>
                </div>
            `;
            minuteResultsList.appendChild(minuteItem);
        });
    }
    displayMemoResults(data);
    displayMinuteResults(minutesData);

    function viewMemo(id){
        window.location.href = `<?=ROOT?>/<?=$user?>/viewmemo?memo=${id}`;
    }
    function viewMemoReport(id){
        window.location.href = `<?=ROOT?>/<?=$user?>/viewmemoreports?memo=${id}`;
    }
    function viewMinute(id){
        window.location.href = `<?=ROOT?>/<?=$user?>/viewminute?minute=${id}`;
    }
    function viewMinuteReport(id){
        window.location.href = `<?=ROOT?>/<?=$user?>/viewminutereports?minute=${id}`;
    }
    function viewContent(id){
        window.location.href = `<?=ROOT?>/<?=$user?>/viewcontent?content=${id}`;
    }
    function applyMemoFilters(){
        const submittedBy = document.getElementById("submitted-by").value;
        const iud = document.getElementById("memo-iud").checked;
        const rhd = document.getElementById("memo-rhd").checked;
        const bom = document.getElementById("memo-bom").checked;
        const syndicate = document.getElementById("memo-syndicate").checked;
        const fromDate = document.getElementById("from-memo-date").value;
        const toDate = document.getElementById("to-memo-date").value;
        console.log(submittedBy, iud, rhd, bom, syndicate, fromDate, toDate , data);
        const filteredData = data.filter(memo => {
            let result = true;
            if (submittedBy !== "all"){
                result = (memo.submitted_by.toLowerCase() === submittedBy.toLowerCase());
            }
            if (iud && rhd && bom && syndicate){
                result = result && (memo.meeting_type.toLowerCase() === "iud" || memo.meeting_type.toLowerCase() === "rhd" || memo.meeting_type.toLowerCase() === "bom" || memo.meeting_type.toLowerCase() === "syndicate");
            }
            else if(iud && rhd && bom){
                result = result && (memo.meeting_type.toLowerCase() === "iud" || memo.meeting_type.toLowerCase() === "rhd" || memo.meeting_type.toLowerCase() === "bom");
            }
            else if(iud && rhd && syndicate){
                result = result && (memo.meeting_type.toLowerCase() === "iud" || memo.meeting_type.toLowerCase() === "rhd" || memo.meeting_type.toLowerCase() === "syndicate");
            }
            else if(iud && bom && syndicate){
                result = result && (memo.meeting_type.toLowerCase() === "iud" || memo.meeting_type.toLowerCase() === "bom" || memo.meeting_type.toLowerCase() === "syndicate");
            }
            else if(rhd && bom && syndicate){
                result = result && (memo.meeting_type.toLowerCase() === "rhd" || memo.meeting_type.toLowerCase() === "bom" || memo.meeting_type.toLowerCase() === "syndicate");
            }
            else if(iud && rhd){
                result = result && (memo.meeting_type.toLowerCase() === "iud" || memo.meeting_type.toLowerCase() === "rhd");
            }
            else if(iud && bom){
                result = result && (memo.meeting_type.toLowerCase() === "iud" || memo.meeting_type.toLowerCase() === "bom");
            }
            else if(iud && syndicate){
                result = result && (memo.meeting_type.toLowerCase() === "iud" || memo.meeting_type.toLowerCase() === "syndicate");
            }
            else if(rhd && bom){
                result = result && (memo.meeting_type.toLowerCase() === "rhd" || memo.meeting_type.toLowerCase() === "bom");
            }
            else if(rhd && syndicate){
                result = result && (memo.meeting_type.toLowerCase() === "rhd" || memo.meeting_type.toLowerCase() === "syndicate");
            }
            else if(bom && syndicate){
                result = result && (memo.meeting_type.toLowerCase() === "bom" || memo.meeting_type.toLowerCase() === "syndicate");
            }
            else if (iud){
                result = result && memo.meeting_type.toLowerCase() === "iud";
            }
            else if (rhd){
                result = result && memo.meeting_type.toLowerCase() === "rhd";
            }
            else if (bom){
                result = result && memo.meeting_type.toLowerCase() === "bom";
            }
            else if (syndicate){
                result = result && memo.meeting_type.toLowerCase() === "syndicate";
            }
            if (fromDate){
                result = result && memo.date >= fromDate;
            }
            if (toDate){
                result = result && memo.date <= toDate;
            }
            return result;
        });
        displayMemoResults(filteredData);
        updateSearchCount("memo",filteredData.length);
        }
    function clearMemoFilters(){
        document.getElementById("submitted-by").value = "all";
        document.getElementById("memo-iud").checked = false;
        document.getElementById("memo-rhd").checked = false;
        document.getElementById("memo-bom").checked = false;
        document.getElementById("memo-syndicate").checked = false;
        document.getElementById("from-memo-date").value = "";
        document.getElementById("to-memo-date").value = "";
        displayMemoResults(data);
        updateSearchCount("memo",data.length);
        }
    
    function applyMinuteFilters(){
        const iud = document.getElementById("minute-iud").checked;
        const rhd = document.getElementById("minute-rhd").checked;
        const bom = document.getElementById("minute-bom").checked;
        const syndicate = document.getElementById("minute-syndicate").checked;
        const fromDate = document.getElementById("minute-from-date").value;
        const toDate = document.getElementById("minute-to-date").value;
        console.log(iud, rhd, bom, syndicate, fromDate, toDate , minutesData);
        const filteredData = minutesData.filter(minute => {
            let result = true;
            if (iud && rhd && bom && syndicate){
                result = result && (minute.meeting_type.toLowerCase() === "iud" || minute.meeting_type.toLowerCase() === "rhd" || minute.meeting_type.toLowerCase() === "bom" || minute.meeting_type.toLowerCase() === "syndicate");
            }
            else if(iud && rhd && bom){
                result = result && (minute.meeting_type.toLowerCase() === "iud" || minute.meeting_type.toLowerCase() === "rhd" || minute.meeting_type.toLowerCase() === "bom");
            }
            else if(iud && rhd && syndicate){
                result = result && (minute.meeting_type.toLowerCase() === "iud" || minute.meeting_type.toLowerCase() === "rhd" || minute.meeting_type.toLowerCase() === "syndicate");
            }
            else if(iud && bom && syndicate){
                result = result && (minute.meeting_type.toLowerCase() === "iud" || minute.meeting_type.toLowerCase() === "bom" || minute.meeting_type.toLowerCase() === "syndicate");
            }
            else if(iud && rhd){
                result = result && (minute.meeting_type.toLowerCase() === "iud" || minute.meeting_type.toLowerCase() === "rhd");
            }
            else if(iud && bom){
                result = result && (minute.meeting_type.toLowerCase() === "iud" || minute.meeting_type.toLowerCase() === "bom");
            }
            else if(iud && syndicate){
                result = result && (minute.meeting_type.toLowerCase() === "iud" || minute.meeting_type.toLowerCase() === "syndicate");
            }
            else if(rhd && bom){
                result = result && (minute.meeting_type.toLowerCase() === "rhd" || minute.meeting_type.toLowerCase() === "bom");
            }
            else if(rhd && syndicate){
                result = result && (minute.meeting_type.toLowerCase() === "rhd" || minute.meeting_type.toLowerCase() === "syndicate");
            }
            else if(bom && syndicate){
                result = result && (minute.meeting_type.toLowerCase() === "bom" || minute.meeting_type.toLowerCase() === "syndicate");
            }
            else if (iud){
                result = result && minute.meeting_type.toLowerCase() === "iud";
            }
            else if (rhd){
                result = result && minute.meeting_type.toLowerCase() === "rhd";
            }
            else if (bom){
                result = result && minute.meeting_type.toLowerCase() === "bom";
            }
            else if (syndicate){
                result = result && minute.meeting_type.toLowerCase() === "syndicate";
            }
            if (fromDate){
                result = result && minute.meeting_date >= fromDate;
            }
            if (toDate){
                result = result && minute.meeting_date <= toDate;
            }
            return result;
            });
        displayMinuteResults(filteredData);
        updateSearchCount("minute",filteredData.length);
    }
    function clearMinuteFilters(){
        document.getElementById("minute-iud").checked = false;
        document.getElementById("minute-rhd").checked = false;
        document.getElementById("minute-bom").checked = false;
        document.getElementById("minute-syndicate").checked = false;
        document.getElementById("minute-from-date").value = "";
        document.getElementById("minute-to-date").value = "";
        displayMinuteResults(minutesData);
        updateSearchCount("minute",minutesData.length);
    }

    function updateSearchCount(type,count){
        document.querySelector(".search-count-"+type).textContent = count+" ";
    }
