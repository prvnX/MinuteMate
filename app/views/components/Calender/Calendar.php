<?php
class Calendar {
    private $events = [];
    private $filePath = '../app/views/components/Calender/events.json'; // Path to the file storing events (After creating database, this will be changed to database)


    public function __construct() {
        $this->loadEventsFromFile(); // Load events when the Calendar is initialized (After creating database, this will be changed to database)
    }

    public function loadEventsFromFile() {
        if (file_exists($this->filePath)) {
            $this->events = json_decode(file_get_contents($this->filePath), true) ?? [];
        }
    }

    public function saveEventsToFile() {
        file_put_contents($this->filePath, json_encode($this->events));
    }

    public function addEvent($event) {
        $this->events[] = $event;
        $this->saveEventsToFile(); // Save updated events after adding a new one (After creating database, this will be changed to database)
    }

    public function getEvents() {
        return $this->events;
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
    $dt = new DateTime("now", new DateTimeZone('Asia/Dhaka')); 
    $today = $dt->format('Y-m-d');
    // Display the days of the month
    for ($day = 1; $day <= $daysInMonth; $day++) {
        $date = "$year-$month-" . str_pad($day, 2, '0', STR_PAD_LEFT);
        $classes = '';

        // Check if the date has any events and set the color based on the event type
        foreach ($this->events as $event) {
            $date = sprintf("%04d-%02d-%02d", $year, $month, $day);
            if ($event['start'] == $date) {
                $classes = implode(' ', $event['classes']);
                break;
            }
        }

        // Highlight today's date with an additional "today" class
        if ($date == $today) {
            $classes .= ' today';
        }

        // Apply the event class if it exists, and only display the day number
        $output .= '<td class="' . trim(string: $classes) . '">' . $day . '</td>';

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
