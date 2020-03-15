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
		$runs = [];
		$SQL = "SELECT * FROM `railtracks`.`trainruns`";
		$query = $this->db->query($SQL);
		$data = $query->result_array();
		if (!empty($data)) {
			foreach($data as $runData) {
				$r = $this->RunFactory->doCreateObject($runData);
				if (!empty($r->getId())) {
					$runs[$r->getId()] = $r;
				}
			}
		}
		return $runs;
	}
	
	/**
	 * This function selects a run based on id
	 *
	 * @param string $runId
	 *
	 * @return Run | false
	 */
	public function selectRun($runId)
	{
		$run = false;
		$SQL =  "SELECT * FROM
					`railtracks`.`trainruns`
				WHERE
					`trainruns`.`id` = '$runId'
				LIMIT 1";
					
		$query = $this->db->query($SQL);
		$data = $query->result_array();
		if (!empty($data)) {
			$run = $this->RunFactory->doCreateObject($data[0]);
		}
		return $run;
	}
	
	/**
	 * This function saves/updates Runs given to it.
	 *
	 * @param Run $run
	 *
	 * @return boolean
	 *
	 * @throws \Exception 
	 */
	public function saveRun($run)
	{
		if ($this->RunFactory->checkInstance($run)) {
			$runCheck = $this->selectRun($run->getId());
			if ($runCheck) {
				$run->setId($runCheck->getId());
			}
			$id = filter_var($run->getId(), FILTER_SANITIZE_STRING);
			$trainLine = filter_var($run->getTrainLine(), FILTER_SANITIZE_STRING);
			$route = filter_var($run->getRoute(), FILTER_SANITIZE_STRING);
			$operator = filter_var($run->getOperator(), FILTER_SANITIZE_STRING);
			$SQL = "INSERT INTO `railtracks`.`trainruns` (
						`id`,
						`trainLine`,
						`route`,
						`operator`
					) VALUES (
						'$id',
						'$trainLine',
						'$route',
						'$operator'
					) ON DUPLICATE KEY UPDATE
						`trainLine` =  VALUES(`trainLine`),
						`route` = VALUES(`route`),
						`operator` = VALUES(`operator`)";
						
			$response = $this->db->simple_query($SQL);
			if  (!$response){
				throw new \Exception("An error has occured when saving the run passed.");
			}
			return true;
		} else {
			throw new \Exception("Invalid instance passed into function: Must be an instance of Run");
		}
	}
	
	/**
	 * This function handles removing a Run from the database.
	 *
	 * @param string $id
	 *
	 * @return true
	 *
	 * @throws \Exception
	 */
	public function deleteRun($id)
	{
		if (empty($id)) {
			throw new \Exception("An invalid id was passed");
		}
		if ($run = $this->selectRun($id)) {
			$SQL = "DELETE FROM `railtracks`.`trainruns` WHERE `id` = '$id'";
			$response =  $this->db->simple_query($SQL);
			if (!$response) {
				throw new \Exception("An error occured when deleting Run: " . $id);
			}
			return true;
		} else {
			throw new \Exception("Run passed was not found in the DB.");
		}
	}
	
	/**
	 * This function returns the base object from the factory used in this model
	 *
	 * @return Run
	 */
	public function getBaseRun()
	{
		return $this->RunFactory->getBaseObject();
	}
	
}