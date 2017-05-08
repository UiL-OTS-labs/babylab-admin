<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_Participation extends CI_Migration
{

	public function up()
	{
		$fields = array(
			'comment' => array('type' => 'VARCHAR', 'constraint' => 500, 'null' => TRUE),
			'tech_comment' => array('type' => 'VARCHAR', 'constraint' => 500, 'null' => TRUE),
			'calendar_comment' => array('type' => 'VARCHAR', 'constraint' => 500, 'null' => TRUE),
			'call_back_comment' => array('type' => 'VARCHAR', 'constraint' => 500, 'null' => TRUE),
		);

		$this->dbforge->modify_column('participation', $fields);
	}

	public function down()
	{
		$fields = array(
			'comment' => array('type' => 'VARCHAR', 'constraint' => 200, 'null' => TRUE),
			'tech_comment' => array('type' => 'VARCHAR', 'constraint' => 200, 'null' => TRUE),
			'calendar_comment' => array('type' => 'VARCHAR', 'constraint' => 200, 'null' => TRUE),
			'call_back_comment' => array('type' => 'VARCHAR', 'constraint' => 200, 'null' => TRUE),
		);

		$this->dbforge->modify_column('participation', $fields);
	}

}
