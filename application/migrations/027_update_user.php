<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_user extends CI_Migration 
{
    public function up()
    {
        $fields = array(
            'needssignature'    => array('type' => 'TINYINT', 'default' => 0),
            'signed'            => array('type' => 'TIMESTAMP', 'null' => TRUE),
        );

        $this->dbforge->add_column('user', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('user', 'needssignature');
        $this->dbforge->drop_column('user', 'signed');
    }
}
