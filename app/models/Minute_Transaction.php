<?php
class Minute_Transaction{
    use Model;
    //minute table
    protected $minuteTable = 'minute';
    protected $minuteColumns = ['MeetingID','title','created_date','created_by'];

    //protected $MeetingTable = 'meeting';

    //attendence table
    protected $attendenceTable = 'meeting_attendence';
    protected $attendenceColumns = ['meeting_id','attendee'];

    //agenda table
    protected $agendaTable = 'meeting_agenda';
    protected $agendaColumns = ['meeting_id','agenda_item'];

    // content table
    protected $contentTable = 'content';
    protected $contentColumns = ['title','content','minute_id'];

    //content restriction table
    protected $restrictionsTable = 'content_restrictions';
    protected $restrictionsColumns = ['content_id','restricted_user'];

    //content forward dep table
    protected $forwardDepTable = 'content_forward_dep';
    protected $forwardDepColumns = ['content_id','forward_dep_id','forward_by'];

    //content forward meeting table
    protected $forwardMeetingTable = 'content_forward_meeting';
    protected $forwardMeetingColumns = ['content_id','meeting_type','forward_by'];

    //memo table

    public $MinuteID;




    protected function insertToTable($table,$columns=[],$data=[]){
        if(!empty($columns)){
            foreach($data as $key=>$value){
                if(!in_array($key, $columns)){
                        unset($data[$key]);
                    }
                }
            }    
        $keys=array_keys($data);
        $query="insert into $table (".implode(",",$keys).") values (:".implode(",:",$keys).")";
        return $this->queryExec($query, $data);
    }

    public function insertMinute($data){
        try{
        $this->beginTransaction(); //begin transaction
        $minuteData=[
            'MeetingID'=>$data['MeetingID'],
            'title'=>$data['title'],
            'created_date'=>date('Y-m-d'),
            'created_by'=>$data['secretary']
        ];

        /*Insert Minute Details*/

        $isdataInserted = $this->insertToTable($this->minuteTable,$this->minuteColumns,$minuteData); 
        if(!$isdataInserted){
            throw new Exception("Failed to insert Minute Details.");
        }
        $LastInsertedMinuteID = $this->getLastInsertID(); //get the inserted minute id
        $this->MinuteID = $LastInsertedMinuteID;

        /*Insert Attendence Details*/
        $attendees = $data['attendence'];
        foreach($attendees as $attendee){
            $attendenceData=[
                'meeting_id'=>$data['MeetingID'],
                'attendee'=>$attendee
            ];
            $isdataInserted = $this->insertToTable($this->attendenceTable,$this->attendenceColumns,$attendenceData); //insert row by row
            if(!$isdataInserted){ 
                throw new Exception("Failed to insert Attendence Details.");
            }
        }

        /*Insert Agenda Details*/

        $agendas = $data['agenda'];
        foreach($agendas as $agenda){
            $agendaData=[
                'meeting_id'=>$data['MeetingID'],
                'agenda_item'=>$agenda
            ];
            $isdataInserted = $this->insertToTable($this->agendaTable,$this->agendaColumns,$agendaData);
            if(!$isdataInserted){
                throw new Exception("Failed to insert Agenda Details.");
            }
        }

        /*Insert Minute Content*/
        $sections=$data['sections'];
        foreach($sections as $section){
            $insertedContent=$section['insertedcontent'];
            $title=$section['title'];
            $restrictions=$section['selectedRestrictions'];
            $forwardDeps=$section['forwardDepartments'];
            $forwardMeeting=$section['selectedRadioValue'];

            $contentData=[
                'title'=>$title,
                'content'=>$insertedContent,
                'minute_id'=>$LastInsertedMinuteID
            ];
            $isdataInserted = $this->insertToTable($this->contentTable,$this->contentColumns,$contentData);
            if(!$isdataInserted){
                throw new Exception("Failed to insert Content Details.");
            }
            $LastInsertedContentID = $this->getLastInsertID();

            if(isset($restrictions) && count($restrictions)>0){
                //restriction handle
                foreach($restrictions as $restricted_user){
                    $restrictionData=[
                        'content_id'=>$LastInsertedContentID,
                        'restricted_user'=>$restricted_user
                    ];
                    $isdataInserted = $this->insertToTable($this->restrictionsTable,$this->restrictionsColumns,$restrictionData);
                    if(!$isdataInserted){
                        throw new Exception("Failed to insert Content Restrictions.");
                    }
                }
            }

            if(isset($forwardDeps) && count($forwardDeps)>0){
                //forward dep handle
                foreach($forwardDeps as $forwardDep){
                    $forwardDepData=[
                        'content_id'=>$LastInsertedContentID,
                        'forward_dep_id'=>$forwardDep,
                        'forward_by'=>$data['secretary']
                    ];
                    $isdataInserted = $this->insertToTable($this->forwardDepTable,$this->forwardDepColumns,$forwardDepData);
                    if(!$isdataInserted){
                        throw new Exception("Failed to insert Content Forward Department.");
                    }

                }
            }
            if(isset($forwardMeeting) && $forwardMeeting!=""){
                //forward meeting handle
                $forwardMeetingData=[
                    'content_id'=>$LastInsertedContentID,
                    'meeting_type'=>$forwardMeeting,
                    'forward_by'=>$data['secretary']
                ];
                $isdataInserted = $this->insertToTable($this->forwardMeetingTable,$this->forwardMeetingColumns,$forwardMeetingData);
                if(!$isdataInserted){
                    throw new Exception("Failed to insert Content Forward Meeting.");
                }
            }
        }
        $this->commit();
        return true;        
    }
    catch(Exception $e){
        $this->rollBack();
        return "Transaction Failed : ".$e->getMessage();
    }

    }

    // public function testData($data){
        
    // }
        

    }