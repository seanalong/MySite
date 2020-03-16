<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This class's job is to be the head of the application at this moment.
 * -  Handles anything routes that deal with runs.
 */
class Runs extends CI_Controller
{
	/** These are the varaibles avaible in the view */
	const DATA_RUNS = 'runs';
	const DATA_RUNS_ARRAY = 'runsArray';
	const DATA_MESSAGE = 'message';
	const DATA_MESSAGE_CLASS = 'messageClass';
	const DATA_RUN_POST_ENDPOINT = 'runPostEndpoint';
	const DATA_DELETE_ENDPOINT =  'runDeleteEndpoint';
	const DATA_UPLOAD_CSV = 'uploadCsvEndpoint';
	
	/** These are the message classes for use */
	const MESSAGE_SUCCESS = 'message-success';
	const MESSAGE_ERROR = 'message-error';
	
	/**
	 * @var string The base url for my application.
	 * 
	 * @TODO Move into a helper class
	 */
	protected $siteUrl = 'http://165.227.197.1/index.php/';

	public function __construct()
	{
		parent::__construct();
		$this->load->model('RunsModel');
	}

	/**
	 * This loads everthing we need for the view.
	 *
	 * @uses RunsModel
	 */
	public function index()
	{
		$data = $this->loadBaseData();
		$this->load->view('Runs/RunsOutput', $data);
	}
	
	/**
	 * This function handles the creation request for a new run
	 * - This method expects a post request
	 */
	public function saveRun()
	{
		try {
			$id = (!empty($_POST['runId'])) ? $_POST['runId'] : false;
			$route = (!empty($_POST['route'])) ? $_POST['route'] : '';
			$trainLine  = (!empty($_POST['trainLine'])) ? $_POST['trainLine'] : '';
			$operator = (!empty($_POST['operator'])) ?  $_POST['operator'] : '';
			if (!$id) {
				throw new \Exception("No run id provided");
			}
			$run = $this->RunsModel->getBaseRun();
			$run->setId($id);
			$run->setTrainLine($trainLine);
			$run->setRoute($route);
			$run->setOperator($operator);
			$this->RunsModel->saveRun($run);
			$data = $this->loadBaseData("Successfully updated/created Run!", self::MESSAGE_SUCCESS);
			$this->load->view('Runs/RunsOutput', $data);
		} catch (\Exception $e) {
			$message = "An error has occured: " . $e->getMessage();
			$data = $this->loadBaseData($message,  self::MESSAGE_ERROR);
			$this->load->view('Runs/RunsOutput', $data);
		}
	}
	
	/**
	 * This function handles deleting an run
	 * - This method expects a post request
	 */
	public function deleteRun()
	{
		try {
			$id =  (!empty($_POST['id'])) ? $_POST['id'] : false;
			if (!$id) {
				throw new \Exception("An ID was not posted for deletion");
			}
			$this->RunsModel->deleteRun($id);
			$message = "Successfully removed Run: " . $id;
			$data = $this->loadBaseData($message, self::MESSAGE_SUCCESS);
			$this->load->view('Runs/RunsOutput', $data);
		} catch (\Exception $e) {
			$message = "An error has occured: " . $e->getMessage();
			$data = $this->loadBaseData($message,  self::MESSAGE_ERROR);
			$this->load->view('Runs/RunsOutput', $data);
		}
	}
	
	/**
	 * This functon requires that csv be uploaded and it is formated as the following:
	 * - First line is a header row. We skip it automaticlly
	 * - each row of the csv must be columns long
	 * - [0] -> Train Line
	 * - [1] -> Route
	 * - [2] -> Id (Run Number)
	 * - [3] -> Operator
	 *
	 * This function will also skip the following:
	 * - The header row
	 * - When [2] is empty
	 * - If any of the above indexes is missing
	 * - If the total index count does not equal 4
	 * - If any columns have empty rows
	 */
	public function handleCsvUpload()
	{
		try {
			if (!isset($_FILES['csv']['tmp_name']) || empty($_FILES['csv']['tmp_name'])) {
				throw new \Exception("Invalid file uploaded");
			}
			$tmpName = $_FILES['csv']['tmp_name'];
			$name = $_FILES['csv']['name'];
			$csvAsArray = array_map('str_getcsv', file($tmpName));
			if (preg_match('/\.csv/', $name) != 1) {
				throw new \Exception("File passed is not a csv");
			}
			if (!empty($csvAsArray)) {
				foreach($csvAsArray as $index => $row) {
					if ($index == "0") {
						//Headers Row - Skip this row.
						continue;
					}
					if (count($row) != 4) {
						//Row is malformed, skip this row
						continue;
					}
					if (!isset($row[0], $row[1], $row[2], $row[3])) {
						continue;
					}
					$checkTrainLine = trim($row[0]);
					$checkId = trim($row[2]);
					$checkRoute = trim($row[1]);
					$checkOperator = trim($row[3]);
					if (empty($checkId) || empty($checkTrainLine) || empty($checkRoute) || empty($checkOperator)) {
						continue;
					}
					$object = $this->RunsModel->getBaseRun();
					$object->setId($row[2]);
					$object->setTrainLine($row[0]);
					$object->setRoute($row[1]);
					$object->setOperator($row[3]);
					try {
						$this->RunsModel->saveRun($object);
					} catch (\Exception $e) {
						continue;
					}
				}
				$data = $this->loadBaseData("Successfully processed the CSV", self::MESSAGE_SUCCESS);
				$this->load->view('Runs/RunsOutput', $data);
			} else {
				throw new \Exception("Empty CSV passed.");
			}
		} catch (\Exception $e) {
			$message = "An error has occured: " . $e->getMessage();
			$data = $this->loadBaseData($message,  self::MESSAGE_ERROR);
			$this->load->view('Runs/RunsOutput', $data);
		}
	}
	
	/**
	 * This function loads the base data required for this interfaces view
	 *
	 * @param string $message The message to be displayed
	 * @param string $class The class of the message
	 *
	 * @return array
	 */
	private function loadBaseData($message = '', $class = '')
	{
		$data = [
			self::DATA_RUNS => $this->RunsModel->selectAll(),
			self::DATA_RUNS_ARRAY => [],
			self::DATA_MESSAGE => $message,
			self::DATA_MESSAGE_CLASS => $class,
			self::DATA_RUN_POST_ENDPOINT => $this->siteUrl . 'runs/saveRun',
			self::DATA_DELETE_ENDPOINT => $this->siteUrl  . 'runs/deleteRun',
			self::DATA_UPLOAD_CSV => $this->siteUrl . 'runs/handleCsvUpload',
		];
		if (!empty($data['runs']))  {
			$data[self::DATA_RUNS_ARRAY]  = $this->generateRunsArray($data['runs']);
		}
		ksort($data[self::DATA_RUNS]);
		ksort($data[self::DATA_RUNS_ARRAY]);
		return $data;
	}
	
	/**
	 * This function returns the array forms of the runs.
	 * - This is used to covert them into javascript objects later
	 *
	 * @param array $runs
	 *
	 * @return array
	 */
	private function generateRunsArray($runs)
	{
		$runsArray = [];
		if (!empty($runs)) {
			foreach($runs as $id => $r) {
				$runsArray[$id] = $r->asArray();
			}
		}
		return $runsArray;
	}
	
}
