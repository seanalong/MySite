<?php
/**
 * This class is an instance of a run.
 * 
 * - Holds methods to access this particular instance of run
 * - This holds the structure of what I want a run to be
 */
class Run
{
	/** @var string The id of the run */
	protected $id;
	 
	/** @var string The name of the train line */
	protected $trainLine;
	
	/** @var string The name of the route */
	protected $route;
	
	/** @var string The name of the operator */
	protected $operator;
	
	/**
	 * This sets the id of the run
	 *
	 * @param string $id
	 */
	public function setId($id)
	{
		$this->id = (string) $id;
	}
	
	/**
	 * This returns the id of the run
	 *
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}
	
	/**
	 * This function sets the train line property
	 *
	 * @param string $trainLine
	 */
	public function setTrainLine($trainLine)
	{
		$this->trainLine = (string) $trainLine;
	}
	
	/**
	 *  This function returns the train line
	 */
	public function getTrainLine($trainLine)
	{
		return $this->trainLine;
	}
	
	/**
	 * This function sets the route for this run
	 *
	 * @param string $route
	 */
	public function setRoute($route)
	{
		$this->route = (string) $route;
	}
	
	/**
	 * This function returns the  route for this run
	 *
	 * @return string
	 */
	public function getRoute()
	{
		return $this->route;
	}
	
	/**
	 * This function returns the route for this run
	 *
	 * @param $string $operator
	 */
	public function setOperator($operator)
	{
		$this->operator = (string) $operator;
	}
	
	/**
	 * Returns the operator of this run
	 *
	 * @return string
	 */
	public function getOperator()
	{
		return $this->operator;
	}
	
}
