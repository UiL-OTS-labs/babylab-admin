<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_User extends CI_Migration
{
    public function up()
    {
        $modify = array(
            'role' => array('type' => 'ENUM("admin", "leader", "researcher", "caller", "system")', 'null' => FALSE),
        );

        $this->dbforge->modify_column('user', $modify);
    }

    public function down()
    {
        $modify = array(
            'role' => array('type' => 'ENUM("admin", "leader", "caller", "system")', 'null' => FALSE),
        );

        $this->dbforge->modify_column('user', $modify);
    }
}
