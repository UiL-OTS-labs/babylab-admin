<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_participant extends CI_Migration 
{
    public function up()
    {
        $modify = array(
            'deactivated_reason' => array('type' => 'ENUM("new", "call", "exp", "manual", "selfservice")', 'null' => TRUE),
        );

        $add = array(
            'selfservicecode'    => array('type' => 'VARCHAR', 'constraint' => 200, 'null' => TRUE),
            'selfservicetime'    => array('type' => 'TIMESTAMP', 'null' => TRUE),
            'otherbabylabs'      => array('type' => 'BOOLEAN'),
        );

        $this->dbforge->modify_column('participant', $modify);
        $this->dbforge->add_column('participant', $add);
    }

    public function down()
    {
        $modify = array(
            'deactivated_reason'    => array('type' => 'ENUM("new", "call", "exp", "manual")', 'null' => TRUE),
        );

        $this->dbforge->modify_column('participant', $modify);
        $this->dbforge->drop_column('participant', 'selfservicecode');
        $this->dbforge->drop_column('participant', 'selfservicetime');
        $this->dbforge->drop_column('participant', 'otherbabylabs');
    }
}
