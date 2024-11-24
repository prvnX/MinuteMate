<?php

class Meeting_Types
{
    use Database;

    protected $table = 'meeting_types';

    /**
     * Retrieve all meeting types from the database.
     *
     * @return array|false - Array of meeting types or false if none found.
     */
    public function getAllMeetingTypes()
    {
        $query = "SELECT * FROM $this->table";
        return $this->query($query);
    }

    /**
     * Get a meeting type by name.
     *
     * @param string $meetingType - The name of the meeting type.
     * @return object|false - The matching meeting type record or false if not found.
     */
    public function getByMeetingType($meetingType)
    {
        $query = "SELECT * FROM $this->table WHERE meeting_type = :meeting_type";
        $data = [':meeting_type' => $meetingType];
        return $this->get_row($query, $data);
    }
}
