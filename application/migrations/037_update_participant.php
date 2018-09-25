<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_participant extends CI_Migration
{
    public function up()
    {
        $add = array(
            'newsletter' => array('type' => 'BOOLEAN', 'default' => FALSE),
        );

        $this->dbforge->add_column('participant', $add);
    }

    public function down()
    {
        $this->dbforge->drop_column('participant', 'newsletter');
    }
}
