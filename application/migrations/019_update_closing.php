<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_Closing extends CI_Migration 
{
    public function up()
    {
        $fields = array(
            'lockdown'  => array('type' => 'tinyint', 'constraint' => '1', 'null' => TRUE)
        );

        $this->dbforge->add_column('closing', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('closing', 'lockdown');
    }
}
