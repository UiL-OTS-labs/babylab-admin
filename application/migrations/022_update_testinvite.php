<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_testinvite extends CI_Migration 
{
    public function up()
    {
        $fields = array(
            'datemanualreminder'    => array('type' => 'TIMESTAMP', 'null' => TRUE),
        );

        $this->dbforge->add_column('testinvite', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('testinvite', 'datemanualreminder');
    }
}
