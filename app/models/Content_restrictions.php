<?php
class Content_restrictions{
    use Model;
    protected $table='content_restrictions';
    protected $allowedColumns=[
        'content_id',
        'restricted_user'
    ];
    public function checkRestrictions($username,$contentID){
        $data['username']=$username;
        $data['contentID']=$contentID;
        $query="SELECT 
                EXISTS (
                SELECT 1 
                FROM $this->table 
                WHERE content_id = :contentID AND restricted_user = :username
                ) AS is_restricted";
        return $this->query($query, $data);
    }
}