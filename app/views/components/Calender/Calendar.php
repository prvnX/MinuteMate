<?php
class Calendar {
    private $events = [];    
    private $filePath = '../app/views/components/Calender/events.json'; 


    public function __construct() {
        $this->loadEventsFromDatabase(); 
    }


    public function loadEventsFromDatabase() {
        $meetingModel = new Meeting();
        $meetings = $meetingModel->showMeeting(); 

        $meetings = json_decode(json_encode($meetings), true); 
        $this->events = array_map(function($meeting) {
            return [
                'id' => $meeting['meeting_id'], 
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
        $this->saveEventsToFile(); 
    }

    public function draw($year, $month) {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $output = '<table>';

        $output .= '<tr>';
        $output .= '<th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th>';
        $output .= '</tr><tr>';

        $firstDayOfMonth = date('w', strtotime("$year-$month-01"));
        $output .= str_repeat('<td></td>', $firstDayOfMonth);
        $dt = new DateTime("now", new DateTimeZone('Asia/Colombo')); 
        $today = $dt->format('Y-m-d');

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = "$year-$month-" . str_pad($day, 2, '0', STR_PAD_LEFT);
            $classes = '';
            $hasEvent = false;
            
            foreach ($this->events as $event) {
                $date = sprintf("%04d-%02d-%02d", $year, $month, $day);
                if ($event['date'] == $date) {
                    $classes = implode(' ', $event['classes']);
                    $hasEvent = true;
                    break;
                }
            }

            if ($date == $today) {
                $classes .= ' today';
            }
            $onclick = $hasEvent ? ' onclick="handleDayClick(\'' . $date . '\')" ' : '';

            $output .= '<td class="' . trim($classes) . '" ' . $onclick . '>' . $day . '</td>';

            if (($firstDayOfMonth + $day) % 7 == 0) {
                $output .= '</tr><tr>';
            }
        }

        if (($firstDayOfMonth + $daysInMonth) % 7 != 0) {
            $remainingCells = 7 - (($firstDayOfMonth + $daysInMonth) % 7);
            $output .= str_repeat('<td></td>', $remainingCells);
        }

        $output .= '</tr></table>';
        return $output;
}

}
