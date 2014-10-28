<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_user extends CI_Migration 
{
    public function up()
    {
        $fields = array(
            'firstname' => array('type' => 'VARCHAR', 'constraint' => 200),
            'lastname'  => array('type' => 'VARCHAR', 'constraint' => 200),
        );

        $this->dbforge->add_column('user', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('user', 'firstname');
        $this->dbforge->drop_column('user', 'lastname');
    }
}
