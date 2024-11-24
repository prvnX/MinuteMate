<?php

class User_Meeting_Types
{
    use Database;

    protected $table = 'user_meeting_types';

    /**
     * Inserts meeting types for a given user into the database
     *
     * @param string $username - The username of the accepted user
     * @param array $meetingTypeIds - An array of selected meeting type IDs
     */
    public function insertMeetingTypes($username, $meetingTypeIds)
    {
        foreach ($meetingTypeIds as $typeId) {
            $query = "INSERT INTO $this->table (meeting_type_id, accessible_user) VALUES (:meeting_type_id, :accessible_user)";
            $data = [
                ':meeting_type_id' => $typeId,
                ':accessible_user' => $username,
            ];
            $this->query($query, $data);
        }
    }
}
