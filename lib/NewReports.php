<?php
class NewReports
{
	private $_db;
    private $_siteID;


    public function __construct($siteID)
    {
        $this->_siteID = $siteID;
        $this->_db = DatabaseConnection::getInstance();
    }

    public function getCandidateDetails($status){
    	$sql = sprintf(
    		"SELECT concat (c.first_name,' ',c.last_name) candidate_name,c.phone_home,c.key_skills,c.totalExp,c.current_employer,c.currentCity,c.preferredCity FROM candidate c,candidate_joborder cj where c.candidate_id = cj.candidate_id and cj.status = %s",
            $this->_db->makeQueryInteger($status)
    	);

    	return $this->_db->getAllAssoc($sql);
    }
}
?>