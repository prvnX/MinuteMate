<?php
class Calendar {
    private $events = [];    
    private $filePath = '../app/views/components/Calender/events.json'; // Path to the file storing events (After creating database, this will be changed to database)


    public function __construct() {
        $this->loadEventsFromDatabase(); // Load events when the Calendar is initialized (After creating database, this will be changed to database)
    }


    public function loadEventsFromDatabase() {
        $meetingModel = new Meeting(); // Meeting model is autoloaded
        $meetings = $meetingModel->showMeeting(); // Fetch meetings from the database

        $meetings = json_decode(json_encode($meetings), true); // Convert objects to arrays
        $this->events = array_map(function($meeting) {
            return [
                'id' => $meeting['meeting_id'],  // Ensure 'meeting_id' is an array key
                'date' => $meeting['date'],
                'classes' => [$meeting['meeting_type']]
            ];
        }, $meetings);
    }
    

    public function saveEventsToFile() {
        file_put_contents($this->filePath, json_encode($this->events));
    }

    public function addEvent($event) {
        $this->events[] = $event;
        $this->saveEventsToFile(); // Save updated events after adding a new one (After creating database, this will be changed to database)
    }

    public function draw($year, $month) {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $output = '<table>';

        // Calendar header
        $output .= '<tr>';
        $output .= '<th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th>';
        $output .= '</tr><tr>';

        // Calculate the starting day of the week
        $firstDayOfMonth = date('w', strtotime("$year-$month-01"));
        $output .= str_repeat('<td></td>', $firstDayOfMonth);
        $dt = new DateTime("now", new DateTimeZone('Asia/Colombo')); 
        $today = $dt->format('Y-m-d');
        // Display the days of the month
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = "$year-$month-" . str_pad($day, 2, '0', STR_PAD_LEFT);
            $classes = '';
            $hasEvent = false;

            // Check if the date has any events and set the color based on the event type
            foreach ($this->events as $event) {
                $date = sprintf("%04d-%02d-%02d", $year, $month, $day);
                if ($event['date'] == $date) {
                    $classes = implode(' ', $event['classes']);
                    $hasEvent = true;
                    break;
                }
            }

            // Highlight today's date with an additional "today" class
            if ($date == $today) {
                $classes .= ' today';
            }
            $onclick = $hasEvent ? ' onclick="handleDayClick(\'' . $date . '\')" ' : '';

            // Apply the event class if it exists, and only display the day number
            $output .= '<td class="' . trim($classes) . '" ' . $onclick . '>' . $day . '</td>';

            // Break the row if the week is over
            if (($firstDayOfMonth + $day) % 7 == 0) {
                $output .= '</tr><tr>';
            }
        }

        // Fill in the remaining empty cells for the last row
        if (($firstDayOfMonth + $daysInMonth) % 7 != 0) {
            $remainingCells = 7 - (($firstDayOfMonth + $daysInMonth) % 7);
            $output .= str_repeat('<td></td>', $remainingCells);
        }

        $output .= '</tr></table>';
        return $output;
}

}
