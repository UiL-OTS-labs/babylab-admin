<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_participant extends CI_Migration 
{
    public function up()
    {
        $add = array(
            'languagedisorderparent' => array('type' => 'ENUM("m", "f", "mf")', 'null' => TRUE),
            'autisticparent' => array('type' => 'ENUM("m", "f", "mf")', 'null' => TRUE),
            'attentiondisorderparent' => array('type' => 'ENUM("m", "f", "mf")', 'null' => TRUE),
        );
        
        $this->dbforge->add_column('participant', $add);
    }

    public function down()
    {
        $this->dbforge->drop_column('participant', 'languagedisorderparent');
        $this->dbforge->drop_column('participant', 'autisticparent');
        $this->dbforge->drop_column('participant', 'attentiondisorderparent');
    }
}
