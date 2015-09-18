<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_call extends CI_Migration 
{
    public function up()
    {
        $modify = array(
            'status' => array('type' => 'ENUM("call_started", "no_reply", "call_back", "voicemail", "email", "confirmed", "cancelled")'),
        );

        $this->dbforge->modify_column('call', $modify);

        $add = array(
            'call_back_date' => array('type' => 'TIMESTAMP', 'null' => TRUE),
        );
        $this->dbforge->add_column('participation', $add);
    }

    public function down()
    {
        $modify = array(
            'status'    => array('type' => 'ENUM("call_started", "no_reply", "voicemail", "email", "confirmed", "cancelled")'),
        );

        $this->dbforge->modify_column('call', $modify);
        $this->dbforge->drop_column('participation', 'call_back_date');
    }
}
