<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Newtable_TestSurveyMapping extends CI_Migration 
{
    public function up()
    {
        // Create table for TestSurveyMapping
        $this->dbforge->add_field(array(
            'id'                        => array('type' => 'INT', 'auto_increment' => TRUE),
            'testsurvey_id'             => array('type' => 'INT'),
            'table'                     => array('type' => 'VARCHAR', 'constraint' => '200'),
            'field'                     => array('type' => 'VARCHAR', 'constraint' => '200'),
            'limesurvey_question_id'    => array('type' => 'VARCHAR', 'constraint' => '200'),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('testsurveymapping', FALSE, array('ENGINE' => 'InnoDB'));

        // Add foreign key
        $this->db->query("ALTER TABLE `testsurveymapping`
            ADD FOREIGN KEY (`testsurvey_id`)
            REFERENCES `testsurvey` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;");
    }

    public function down()
    {
        $this->dbforge->drop_table('testsurveymapping');
    }
}
