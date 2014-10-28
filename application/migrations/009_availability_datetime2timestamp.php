<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Availability_datetime2timestamp extends CI_Migration {

	/**
	  * I used datetime as the field instead of timestamp. 
	  * To uniformify, it is now also timestamp
	  */
	public function up()
	{
		$fields = array(
			'from' => array(
					'name' => 'from',
					'type' => 'timestamp',
					'null' => true,
				),
			'to' => array(
					'name' => 'to',
					'type' => 'timestamp',
					'null' => true,
				),
			);

		$this->dbforge->modify_column('availability', $fields);
	}

	

	public function down()
	{
		$fields = array(
			'from' => array(
					'name' => 'from',
					'type' => 'datetime',
				),
			'to' => array(
					'name' => 'to',
					'type' => 'datetime',
				),
			);

		$this->dbforge->modify_column('availability', $fields);
	}
}