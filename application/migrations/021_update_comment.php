<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_Comment extends CI_Migration 
{
    public function up()
    {
        $add = array(
            'handled' => array('type' => 'TIMESTAMP', 'null' => TRUE),
        );

        $this->dbforge->add_column('comment', $add);
    }

    public function down()
    {
        $this->dbforge->drop_column('comment', 'handled');
    }
}
