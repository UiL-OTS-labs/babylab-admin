<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_participation extends CI_Migration
{
    public function up()
    {
        $add = array(
            'condition_1' => array('type' => 'TEXT', 'null' => TRUE, 'default' => NULL),
            'condition_2' => array('type' => 'TEXT', 'null' => TRUE, 'default' => NULL),
        );

        $this->dbforge->add_column('participation', $add);
    }

    public function down()
    {
        $this->dbforge->drop_column('participation', 'condition_1');
        $this->dbforge->drop_column('participation', 'condition_2');
    }
}
