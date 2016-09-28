<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_testinvite extends CI_Migration 
{
    public function up()
    {
        $this->db->query('ALTER TABLE testinvite DROP INDEX testsurvey_participant_id');
    }

    public function down()
    {
    	// This can only be done if no duplicates exists for the testsurvey and participant columns
        $this->db->query('ALTER TABLE testinvite ADD UNIQUE testsurvey_participant_id (testsurvey_id, participant_id)');
    }
}
