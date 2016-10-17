<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_Participation extends CI_Migration 
{
    public function up()
    {
        $add = array(
            'call_back_comment' => array('type' => 'VARCHAR', 'constraint' => 200, 'null' => TRUE),
        );
        $this->dbforge->add_column('participation', $add);

        $modify = array(
            'call_back_date' => array('type' => 'DATE', 'null' => TRUE),
            // Setting explicit default values
            'nrcalls' => array('type' => 'INT', 'null' => FALSE, 'default' => 0),
            'confirmed' => array('type' => 'TINYINT', 'constraint' => 1, 'null' => FALSE, 'default' => 0),
            'cancelled' => array('type' => 'TINYINT', 'constraint' => 1, 'null' => FALSE, 'default' => 0),
            'noshow' => array('type' => 'TINYINT', 'constraint' => 1, 'null' => FALSE, 'default' => 0),
            'completed' => array('type' => 'TINYINT', 'constraint' => 1, 'null' => FALSE, 'default' => 0),
            'interrupted' => array('type' => 'TINYINT', 'constraint' => 1, 'null' => FALSE, 'default' => 0),
            // Setting columns to allow null values
            'comment' => array('type' => 'VARCHAR', 'constraint' => 200, 'null' => TRUE),
            'part_number' => array('type' => 'VARCHAR', 'constraint' => 20, 'null' => TRUE),
            'tech_comment' => array('type' => 'VARCHAR', 'constraint' => 200, 'null' => TRUE),
            'calendar_comment' => array('type' => 'VARCHAR', 'constraint' => 200, 'null' => TRUE),
        );

        $this->dbforge->modify_column('participation', $modify);
    }

    public function down()
    {
        $modify = array(
            'call_back_date' => array('type' => 'TIMESTAMP', 'null' => TRUE),
            'nrcalls' => array('type' => 'INT', 'null' => FALSE),
            'confirmed' => array('type' => 'TINYINT', 'constraint' => 1, 'null' => FALSE),
            'cancelled' => array('type' => 'TINYINT', 'constraint' => 1, 'null' => FALSE),
            'noshow' => array('type' => 'TINYINT', 'constraint' => 1, 'null' => FALSE),
            'completed' => array('type' => 'TINYINT', 'constraint' => 1, 'null' => FALSE),
            'interrupted' => array('type' => 'TINYINT', 'constraint' => 1, 'null' => FALSE),
            'comment' => array('type' => 'VARCHAR', 'constraint' => 200, 'null' => FALSE),
            'part_number' => array('type' => 'VARCHAR', 'constraint' => 20, 'null' => FALSE),
            'tech_comment' => array('type' => 'VARCHAR', 'constraint' => 200, 'null' => FALSE),
            'calendar_comment' => array('type' => 'VARCHAR', 'constraint' => 200, 'null' => FALSE),
        );

        $this->dbforge->modify_column('participation', $modify);
        $this->dbforge->drop_column('participation', 'call_back_comment');
    }
}
