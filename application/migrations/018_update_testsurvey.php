<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_testsurvey extends CI_Migration 
{
    public function up()
    {
        $add = array(
            'description' => array('type' => 'VARCHAR', 'constraint' => 200, 'null' => TRUE),
        );

        $this->dbforge->add_column('testsurvey', $add);
    }

    public function down()
    {
        $this->dbforge->drop_column('testsurvey', 'description');
    }
}
