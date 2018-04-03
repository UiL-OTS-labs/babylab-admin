<?php
class TestSurveyMappingModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/////////////////////////
	// TestSurveys
	/////////////////////////

	/** Returns the mapping for a testsurvey and a specific table */
	public function get_mapping_by_testsurvey($testsurvey_id, $table)
	{
		$this->db->where('testsurvey_id', $testsurvey_id);
		$this->db->where('table', $table);
		$mappings = $this->db->get('testsurveymapping')->result();

		$result = array();
		foreach ($mappings as $mapping)
		{
			$result[$mapping->field] = $mapping->limesurvey_question_id;
		}
		return (object) $result;
	}
}
