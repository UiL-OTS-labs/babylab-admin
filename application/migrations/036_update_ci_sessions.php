<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_CI_Sessions extends CI_Migration
{

    /*
    CREATE TABLE IF NOT EXISTS `ci_sessions` (
    `id` varchar(128) NOT NULL,
    `ip_address` varchar(45) NOT NULL,
    `timestamp` int(10) unsigned DEFAULT 0 NOT NULL,
    `data` blob NOT NULL,
    KEY `ci_sessions_timestamp` (`timestamp`)
    );
     */

    public function up()
    {
        $this->dbforge->rename_table('ci_sessions', 'ci_sessions_old');

        $fields = [
            'id' => [
                'type' => 'VARCHAR',
                'constraint' => 128,
                'null' => false,
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => false,
            ],
            'timestamp' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'default' => 0,
                'null' => false
            ],
            'data' => [
                'type' => 'blob',
                'null' => false
            ],
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('timestamp');
        $this->dbforge->create_table('ci_sessions');
    }

    public function down()
    {
        $this->dbforge->drop_table('ci_sessions');
        $this->dbforge->rename_table('ci_sessions_old', 'ci_sessions');
    }

}
