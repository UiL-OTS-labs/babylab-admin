<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_participant extends CI_Migration
{
    public function up()
    {
        $add = array(
            'speechdisorderparent' => array('type' => 'ENUM("m", "f", "mf")', 'null' => TRUE),
            'speechdisorderparent_details' => array('type' => 'VARCHAR', 'constraint' => 200),
        );

        $this->dbforge->add_column('participant', $add);
    }

    public function down()
    {
        $this->dbforge->drop_column('participant', 'speechdisorderparent');
        $this->dbforge->drop_column('participant', 'speechdisorderparent_details');
    }
}
