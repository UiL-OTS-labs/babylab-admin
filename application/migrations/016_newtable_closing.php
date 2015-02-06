<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Newtable_Closing extends CI_Migration {

    public function up()
    {
        // Create fields
        $fields = array(
            'id'            => array('type' => 'INT', 'constraint' => '11', 'auto_increment' => TRUE),
            'location_id'   => array('type' => 'INT', 'constraint' => '11'),
            'from'          => array('type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'),
            'to'            => array('type' => 'TIMESTAMP', 'null' => TRUE),  
            'comment'       => array('type' => 'VARCHAR', 'constraint' => '2000'));

        // Add fields
        $this->dbforge->add_field($fields);

        // Add primary key
        $this->dbforge->add_key('id', TRUE);

        // Create table
        $this->dbforge->create_table('closing', TRUE);

        // Add index
        $this->db->query('ALTER TABLE closing ADD INDEX location_id (location_id)');

        // Add foreign key
        $this->db->query('ALTER TABLE closing ADD CONSTRAINT closing_location_fk FOREIGN KEY (location_id) REFERENCES location (id) 
            ON DELETE RESTRICT ON UPDATE RESTRICT');
    }

    public function down()
    {
        $this->dbforge->drop_table('closing');
    }
}