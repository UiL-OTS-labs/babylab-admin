<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_closing_id_null extends CI_Migration 
{
    public function up()
    {
        $fields = array(
            'location_id'  => array('type' => 'int', 'constraint' => '11', 'null' => TRUE)
        );

        $this->dbforge->modify_column('closing', $fields);
        
        $this->dbforge->drop_column('closing', 'lockdown');
    }

    public function down()
    {
        $fields = array(
            'location_id'  => array('type' => 'int', 'constraint' => '11', 'null' => FALSE)
        );
        $fields2 = array(
            'lockdown'  => array('type' => 'tinyint', 'constraint' => '1', 'null' => TRUE)
        );

        $this->dbforge->modify_column('closing', $fields);
        $this->dbforge->add_column('closing', $fields2);

    }
}
