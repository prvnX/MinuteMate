<?php
class minute_Recorrect_Transaction{
    use Model;
    //minute table
    protected $minuteTable = 'minute';
    protected $minuteColumns = ['Minute_ID','MeetingID','title','created_date','created_by','is_approved','is_recorrect'];

    //protected $MeetingTable = 'meeting';

 

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





    protected $linkedminuteTable='minutes_linked';
    protected $linkedminutColumns=['minute_id','minutes_linked'];

    //media files table
    protected $mediaFilesTable='Linked_Media';
    protected $mediaFilesColumns=['Name','minute_id','media_location','ext'];

    //meeting table
    protected $meetingTable='meeting';
    protected $meetingColumns=['meeting_id','data','start_time','end_time','location','created_by','is_minute','type_id','meeting_type','additional_note'];

    protected $keywordTable='minute_Keywords';
    protected $keywordColumns=['Minute_ID','Keyword'];

    public $MinuteID;



    public function insertMinute($data){
        try{
        $this->beginTransaction(); //begin transaction
        $minuteData=[
            'MeetingID'=>$data['MeetingID'],
            'title'=>$data['title'],
            'created_date'=>date('Y-m-d'),
            'created_by'=>$data['secretary'],
            'is_approved'=>1,
            'is_recorrect'=>1
        ];

        /*Insert Minute Details*/

        $isdataInserted = $this->insertToTable($this->minuteTable,$this->minuteColumns,$minuteData); 
        if(!$isdataInserted){
            throw new Exception("Failed to insert Minute Details.");
        }
        $LastInsertedMinuteID = $this->getLastInsertID(); //get the inserted minute id
        $this->MinuteID = $LastInsertedMinuteID;


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
                    'meeting_type'=>strtolower($forwardMeeting),
                    'forward_by'=>$data['secretary']
                ];
                $isdataInserted = $this->insertToTable($this->forwardMeetingTable,$this->forwardMeetingColumns,$forwardMeetingData);
                if(!$isdataInserted){
                    throw new Exception("Failed to insert Content Forward Meeting.");
                }
            }
        }

        /*Link Minutes*/
        $linkedMinutes=$data['LinkedMinutes'];
        foreach($linkedMinutes as $minute){
            $linkedMinuteData=[
                'minute_id'=> $this->MinuteID,
                'minutes_linked' => $minute
            ];
            $isdataInserted=$this->insertToTable($this->linkedminuteTable,$this->linkedminutColumns,$linkedMinuteData);
            if(!$isdataInserted){
                throw new Exception("Failed to insert the linked minutes");
            }
        }

        /*Linked Media Files*/
        $mediaFiles=$data['mediaFiles'];
        foreach($mediaFiles as $mediaFile){
            $mediaFileData=[
                'Name'=>$mediaFile['name'],
                'minute_id'=>$this->MinuteID,
                'media_location'=>$mediaFile['url'],
                'ext'=>$mediaFile['ext']
            ];
            $isdataInserted=$this->insertToTable($this->mediaFilesTable,$this->mediaFilesColumns,$mediaFileData);
            if(!$isdataInserted){
                throw new Exception("Failed to insert the linked media files");
            }
        }

        //update the meeting is_correct on previous minute
        /*Accept prev minute*/
        $prevMinuteID=$data['prevMinuteID'];
        $prevMinuteData=[
            'is_recorrect'=>1
        ];
        if($prevMinuteID!=null){
            $isdataUpdated = $this->updateTheTable($this->minuteTable,$prevMinuteID,$this->minuteColumns,$prevMinuteData,'Minute_id');
            if(!$isdataUpdated){
                throw new Exception("Failed to update Minute Status.");
            }
        }



        $keywords=$data['keywords'];
        foreach($keywords as $keyword){
            $keywordData=[
                'Minute_ID'=>$this->MinuteID,
                'Keyword'=>$keyword
            ];
            $isdataInserted=$this->insertToTable($this->keywordTable,$this->keywordColumns,$keywordData);
            if(!$isdataInserted){
                throw new Exception("Failed to insert the keywords");
            }
            
        }
            

    
        //commit the changes
        $this->commit();
        return $this->MinuteID;        
    }
    catch(Exception $e){
        $this->rollBack();
        return "Transaction Failed : ".$e->getMessage();
    }

    }

    // public function testData($data){


    // }
        

    }