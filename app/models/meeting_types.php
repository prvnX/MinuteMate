<?php

class Meeting_Types
{
    use Database;

    protected $table = 'meeting_types';

   
    public function getAllMeetingTypes()
    {
        $query = "SELECT * FROM $this->table";
        return $this->query($query);
    }

   
    public function getByMeetingType(string $meetingType)
    {
        $query = "SELECT * FROM $this->table WHERE meeting_type = :meeting_type";
        $data = [':meeting_type' => $meetingType];
        return $this->get_row($query, $data);
    }

   
    public function getTypeIdByMeetingType(string $meetingType)
    {
        $query = "SELECT type_id FROM $this->table WHERE meeting_type = :meeting_type";
        $data = [':meeting_type' => $meetingType];
        $result = $this->get_row($query, $data);
        return $result ? $result->type_id : false;
    }
}
