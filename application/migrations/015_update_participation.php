<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_Participation extends CI_Migration 
{
    public function up()
    {
        $fields = array(
            'user_id_leader'  => array('type' => 'INT', 'constraint' => '11', 'null' => TRUE)
        );

        $this->dbforge->add_column('participation', $fields);

        // Add index
        $this->db->query("ALTER TABLE participation ADD INDEX user_id_leader ( user_id_leader ) ");

        // Add foreign key
        $this->db->query("ALTER TABLE participation ADD CONSTRAINT participation_user_fk FOREIGN KEY ( user_id_leader ) REFERENCES user (id) ON DELETE RESTRICT ON UPDATE RESTRICT");
    }

    public function down()
    {
        $this->db->query('ALTER TABLE participation DROP FOREIGN KEY participation_user_fk');
        $this->db->query('ALTER TABLE participation DROP INDEX user_id_leader');
        $this->dbforge->drop_column('participation', 'user_id_leader');
    }
}
