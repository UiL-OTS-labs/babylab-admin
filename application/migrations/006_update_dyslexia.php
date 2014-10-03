<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_Dyslexia extends CI_Migration 
{
	public function up()
	{
		$comment = array(
			'comment' => array('type' => 'VARCHAR(200) DEFAULT NULL', 'null' => TRUE),
		);
		$date = array(
			'date' => array('name' => 'created', 'type' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'),
		);

		$this->dbforge->add_column('dyslexia', $comment);
		$this->dbforge->modify_column('dyslexia', $date);
	}

	public function down()
	{
		$date = array(
			'created' => array('name' => 'date', 'type' => 'DATE'),
		);

		$this->dbforge->modify_column('dyslexia', $date);
		$this->dbforge->drop_column('dyslexia', 'comment');
	}
}
