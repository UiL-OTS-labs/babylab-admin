<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_Participation extends CI_Migration 
{
    public function up()
    {
        $fields = array(
            'excluded'          => array('type' => 'TINYINT', 'constraint' => 1, 'default' => 0),
            'excluded_reason'   => array('type' => 'ENUM("crying", "fussy", "parent", "tech_problems", "interrupted", "other")', 'null' => TRUE),
        );

        $this->dbforge->add_column('participation', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('participation', 'excluded');
        $this->dbforge->drop_column('participation', 'excluded_reason');
    }
}
