<?php
class Lecturer extends Controller {
    public function index() {
        $this->view("lecturer/dashboard");
    }

    public function search() {
        $searchtxt=$_POST['search'];
        if($searchtxt=="" || !$searchtxt){
            $this->view("lecturer/dashboard");
        }
        else{
             //these are just placeholders
        $user = "lecturer";
       
        $notification = "notification-dot"; //use notification-dot if there's a notification
        $menuItems = [
            "home" => ROOT."/lecturer",
            $notification => ROOT."/lecturer/notifications",
            "profile" => ROOT."/lecturer/viewprofile"
        ];
        $memoResults = [
            [
                "id" => 1,
                "title" => "Project Proposal",
                "date" => "2024-11-01",
                "submitted_by" => "Alice",
                "meeting_type" => "IUD"
            ],
            [
                "id" => 2,
                "title" => "Meeting Minutes",
                "date" => "2024-11-05",
                "submitted_by" => "Bob",
                "meeting_type" => "RHD"
            ],
            [
                "id" => 3,
                "title" => "Research Report",
                "date" => "2024-11-10",
                "submitted_by" => "Charlie",
                "meeting_type" => "IUD"
            ],
            [
                "id" => 4,
                "title" => "Budget Plan",
                "date" => "2024-11-12",
                "submitted_by" => "Diana",
                "meeting_type" => "Syndicate"
            ],
            [
                "id" => 5,
                "title" => "Client Feedback",
                "date" => "2024-11-15",
                "submitted_by" => "Eve",
                "meeting_type" => "RHD"
            ],
            [
                "id" => 6,
                "title" => "Project Proposal",
                "date" => "2024-11-01",
                "submitted_by" => "Alice",
                "meeting_type" => "IUD"
            ],
            [
                "id" => 7,
                "title" => "Meeting Minutes",
                "date" => "2024-11-05",
                "submitted_by" => "Bob",
                "meeting_type" => "RHD"
            ],
            [
                "id" => 8,
                "title" => "Research Report",
                "date" => "2024-11-10",
                "submitted_by" => "Charlie",
                "meeting_type" => "IUD"
            ],
            [
                "id" => 11,
                "title" => "Budget Plan",
                "date" => "2024-11-12",
                "submitted_by" => "Diana",
                "meeting_type" => "Syndicate"
            ],
            [
                "id" => 12,
                "title" => "Client Feedback",
                "date" => "2024-11-15",
                "submitted_by" => "Eve",
                "meeting_type" => "RHD"
            ],
            [
                "id" => 13,
                "title" => "Project Proposal",
                "date" => "2024-11-01",
                "submitted_by" => "x",
                "meeting_type" => "IUD"
            ]
        ];

        $minuteResults = [
            [
            "content_id" => 1,
            "minute_id"=> "M001",
            "meeting_date" => "2024-11-01",
            "meeting_type" => "IUD",
            "content_title" => "Project proposal of 'පැදුර "
            ],
            [
            "content_id" => 2,
            "minute_id"=> "M002",
            "meeting_date" => "2024-11-05",
            "meeting_type" => "RHD",
            "content_title" => "Increasing credits of SE III"
            ],
            [
            "content_id" => 3,
            "minute_id"=> "M003",
            "meeting_date" => "2024-11-10",
            "meeting_type" => "IUD",
            "content_title" => "Reducing marks for 'A'"
            ],
            [
            "content_id" => 4,
            "minute_id"=> "M004",
            "meeting_date" => "2024-01-15",
            "meeting_type" => "Syndicate",
            "content_title" => "Annual Budget Review"
            ],
            [
            "content_id" => 5,
            "minute_id"=> "M005",
            "meeting_date" => "2024-02-20",
            "meeting_type" => "BOM",
            "content_title" => "New Research Proposals"
            ],
            [
            "content_id" => 6,
            "minute_id"=> "M006",
            "meeting_date" => "2024-03-18",
            "meeting_type" => "RHD",
            "content_title" => "Quarterly Performance Review"
            ],
            [
            "content_id" => 7,
            "minute_id"=> "M007",
            "meeting_date" => "2024-04-22",
            "meeting_type" => "Syndicate",
            "content_title" => "Policy Updates"
            ],
            [
            "content_id" => 8,
            "minute_id"=> "M008",
            "meeting_date" => "2024-05-10",
            "meeting_type" => "IUD",
            "content_title" => "Project Milestone Discussion"
            ],
            [
            "content_id" => 9,
            "minute_id"=> "M009",
            "meeting_date" => "2024-06-14",
            "meeting_type" => "RHD",
            "content_title" => "Client Feedback Analysis"
            ],
            [
            "content_id" => 10,
            "minute_id"=> "M010",
            "meeting_date" => "2024-07-19",
            "meeting_type" => "Syndicate",
            "content_title" => "Mid-Year Review"
            ],
            [
            "content_id" => 11,
            "minute_id"=> "M011",
            "meeting_date" => "2024-08-23",
            "meeting_type" => "IUD",
            "content_title" => "New Initiatives Planning"
            ],
            [
            "content_id" => 12,
            "minute_id"=> "M012",
            "meeting_date" => "2024-09-17",
            "meeting_type" => "RHD",
            "content_title" => "Team Building Activities"
            ],
            [
            "content_id" => 13,
            "minute_id"=> "M013",
            "meeting_date" => "2024-10-21",
            "meeting_type" => "Syndicate",
            "content_title" => "Year-End Strategy"
            ],
            [
            "content_id" => 14,
            "minute_id"=> 15,
            "meeting_date" => "2024-12-05",
            "meeting_type" => "IUD",
            "content_title" => "Final Project Review"
            ]
        ];
        $this->view("search",[ "user" => $user, "menuItems" => $menuItems, "notification" => $notification,"searchtxt"=>$searchtxt,"memoResults"=>$memoResults,"minuteResults"=>$minuteResults]);
        }
    }
    public function entermemo() {
        $this->view("lecturer/entermemo");
    }
    public function reviewstudentmemos() {
        $this->view("lecturer/reviewstudentmemos");
    }
    public function viewminutes() {
        $this->view("lecturer/viewminutes");
    }
    public function viewsubmittedmemos() {
        $this->view("lecturer/viewsubmittedmemos");
    }
    public function viewmemoreports() {
        $this->view("lecturer/viewmemoreports");
    }
    public function viewminutereports() {
        $this->view("lecturer/viewminutereports");
    }

    public function notifications() {
        //these are just placeholders
        $user = "lecturer";
        $memocart = "memocart";   //use memocart-dot if there is a memo in the cart if not drop the -dot part change with db
        $notification = "notification"; //use notification-dot if there's a notification
        $menuItems = [
            "home" => ROOT."/lecturer",
            $notification => ROOT."/lecturer/notifications",
            "profile" => ROOT."/lecturer/viewprofile"
        ];
        $this->view("notifications",[ "user" => $user, "menuItems" => $menuItems,"notification" => $notification]);
    }
    public function viewprofile() {
        $this->view("lecturer/viewprofile");
    }

    public function submitmemo() {
        $memosuccess = false;
        $memoid = 1;
        if($memosuccess) {
            $this->view("showsuccessmemo",["user"=>"lecturer","memoid"=>$memoid]);
        }
        else {
            $this->view("showunsuccessmemo",["user"=>"lecturer"]);
        }
    }
    public function confirmlogout() {
        $this->view("confirmlogout",[ "user" =>"lecturer"]);
    }

    public function logout() {
        $this->view("logout",[ "user" =>"lecturer"]);
    }

    public function requestchange(){
        $responseStatus = "";
    
        // Handle POST request
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $field = $_POST['field'] ?? null;
            $newValue = $_POST['newValue'] ?? null;
    
            if (!empty($field) && !empty($newValue)) {
                $responseStatus = "success";
            } else {
                $responseStatus = "failure";
            }
        }
    
        // Pass responseStatus to the view
        $this->view("lecturer/requestchange", [
            "user" => "lecturer",
            "responseStatus" => $responseStatus
        ]);
    }


}
