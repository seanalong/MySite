<?php
include __DIR__ . '/Run.php';

/**
 * This class's job is to construct the Run objects using
 * data from the database.
 *
 */
class RunFactory
{
	/**
	 * This returns the base object.
	 *
	 * @returns Run
	 */
	public function getBaseObject()
	{
		return new Run();
	}
	
	/**
	 * This function handles creating a route object from
	 * data from the database and turing it into a Run object
	 *
	 * @param array $data The data from the databse
	 *
	 * @return Run
	 */
	public function doCreateObject($data)
	{
		$object = $this->getBaseObject();
		if (isset($data['id'])) {
			$object->setId($data['id']);
		}
		if (isset($data['trainLine'])) {
			$object->setTrainLine($data['trainLine']);
		}
		if (isset($data['route'])) {
			$object->setRoute($data['route']);
		}
		if (isset($data['operator'])) {
			$object->setOperator($data['operator']);
		}
		return $object;
	}
	
	/**
	 * This function checks an instance to see if it is an
	 * instance of Run
	 *
	 * @param mixed $run
	 *
	 * @return bool
	 */
	public function checkInstance($run)
	{
		if ($run instanceof Run) {
			return true;
		} else {
			return false;
		}
	}
	
}
