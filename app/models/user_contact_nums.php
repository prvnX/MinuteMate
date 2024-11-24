<?php
// user_contact_nums.php

class UserContactNums {
    use Model;
    protected $table = 'user_contact_nums';

    // Method to fetch contact number by username
    public function getContactByUsername($username) {
        $query = "SELECT contact_no FROM user_contact_nums WHERE username = :username";
        $result = $this->query($query, ['username' => $username]);
        return $result ? $result[0]->contact_no : null; // Return the contact number or null if not found
    }

    // You can add other methods to handle insert, update, delete operations on user_contact_nums as needed
}
