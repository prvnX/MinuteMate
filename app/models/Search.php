<?php 
class Search{
    use Model;


    function minute_search($searchTerm){
        $data=[
            'searchTerm'=>$searchTerm
        ];
        $query='SELECT 
        m.Minute_ID,
        m.title,
        mt.meeting_id,
        mt.date,
        mt.start_time,
        mt.meeting_type,

        (
            COALESCE(SUM((MATCH(mk.Keyword) AGAINST(:searchTerm IN NATURAL LANGUAGE MODE)) * 4), 0) +
            COALESCE(SUM(MATCH(c.title, c.content) AGAINST(:searchTerm IN NATURAL LANGUAGE MODE)), 0)
        ) AS total_relevance
        FROM Minute m
        LEFT JOIN minute_Keywords mk ON m.Minute_ID = mk.Minute_ID
        LEFT JOIN content c ON m.Minute_ID = c.minute_id
        LEFT JOIN meeting mt ON m.MeetingID = mt.meeting_id
        WHERE 
            (MATCH(mk.Keyword) AGAINST(:searchTerm IN NATURAL LANGUAGE MODE) > 0)
            OR
            (MATCH(c.title, c.content) AGAINST(:searchTerm IN NATURAL LANGUAGE MODE) > 0)
        GROUP BY m.Minute_ID
        ORDER BY total_relevance DESC';

        return $this->query($query,$data);
    }

    public function memo_search($searchTerm){
        $data=[
            'searchTerm'=>$searchTerm
        ];
        $query="SELECT 
        m.memo_id,
        m.memo_title,
        m.memo_content,
        u.full_name as submitted_by,
        m.meeting_id,
        m.submitted_date,
        mt.meeting_type,
        MATCH(m.memo_title, m.memo_content) AGAINST(:searchTerm IN NATURAL LANGUAGE MODE) AS relevance
        FROM Memo m
        INNER JOIN meeting mt ON m.meeting_id = mt.meeting_id
        INNER JOIN user u ON m.submitted_by = u.username
        WHERE MATCH(m.memo_title, m.memo_content) AGAINST(:searchTerm IN NATURAL LANGUAGE MODE) > 0 AND m.status NOT LIKE 'rejected'
        ORDER BY relevance DESC;
        ";
        return $this->query($query,$data);
    }
}