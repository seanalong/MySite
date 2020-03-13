<?php
/**
 * This class handles grabbing the runs from the database.
 */
class RunsModel extends CI_Model
{
	
	/**
	 * This handles loading the database....
	 */
	public function __construct()
	{
		$this->load->database();
	}
	
	/**
	 * This function selects all rows from `runs`
	 *
	 * @return array The result object
	 */
	public function selectAll()
	{
		$SQL = "SELECT * FROM `railtracks`.`runs`";
		$query = $this->db->query($SQL);
		$data = $query->result_array();
		return $data;
	}
	
}