<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_participant extends CI_Migration 
{
    public function up()
    {
        $modify = array(
            'deactivated_reason' => array('type' => 'ENUM("new", "call", "exp", "manual", "selfservice", "survey")', 'null' => TRUE),
        );

        $this->dbforge->modify_column('participant', $modify);
    }

    public function down()
    {
        $modify = array(
            'deactivated_reason' => array('type' => 'ENUM("new", "call", "exp", "manual", "selfservice")', 'null' => TRUE),
        );

        $this->dbforge->modify_column('participant', $modify);
    }
}
