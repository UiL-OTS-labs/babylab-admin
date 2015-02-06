<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_testsurvey extends CI_Migration 
{
    public function up()
    {
        $modify = array(
            'whensent' => array('type' => 'ENUM("participation", "months", "manual")'),
            'whennr' => array('type' => 'INT', 'null' => TRUE),
        );

        $this->dbforge->modify_column('testsurvey', $modify);
    }

    public function down()
    {
        $modify = array(
            'whensent' => array('type' => 'ENUM("participation", "months")'),
            'whennr' => array('type' => 'INT', 'null' => FALSE),
        );

        $this->dbforge->modify_column('testsurvey', $modify);
    }
}
