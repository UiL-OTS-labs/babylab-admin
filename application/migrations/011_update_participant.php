<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_participant extends CI_Migration 
{
    public function up()
    {
        $fields = array(
            'deactivated'           => array('type' => 'TIMESTAMP', 'null' => TRUE),
            'deactivated_reason'    => array('type' => 'ENUM("new", "call", "exp", "manual")', 'null' => TRUE),
        );

        $this->dbforge->add_column('participant', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('participant', 'deactivated');
        $this->dbforge->drop_column('participant', 'deactivated_reason');
    }
}
