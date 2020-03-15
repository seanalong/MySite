<?php
include __DIR__ . '/runs/RunFactory.php';
/**
 * This class handles grabbing the runs from the database.
 */
class RunsModel extends CI_Model
{
	
	/** @var class The instance of RunFactory (used for generating Runs) */
	protected $RunFactory;
	
	/**
	 * This handles loading the database....
	 */
	public function __construct()
	{
		$filepath = __DIR__ . '/runs/RunFactory.php';
		$this->load->database();
		$this->RunFactory = new RunFactory();
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