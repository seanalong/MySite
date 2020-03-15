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
	const DATA_MESSAGE = 'message';
	const DATA_MESSAGE_CLASS = 'messageClass';
	const DATA_RUN_POST_ENDPOINT = 'runPostEndpoint';
	
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
			$data = $this->loadBaseData("Successfully updated/created Run!", "message-success");
			$this->load->view('Runs/RunsOutput', $data);
		} catch (\Exception $e) {
			$data['runs'] = $this->RunsModel->selectAll();
			$message = "An error has occured: " . $e->getMessage();
			$class = "message-error";
			$data = $this->loadBaseData($message,  $class);
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
			self::DATA_MESSAGE => $message,
			self::DATA_MESSAGE_CLASS => $class,
			self::DATA_RUN_POST_ENDPOINT => $this->siteUrl . 'runs/saveRun',
		];
		return $data;
	}
	
}
