<?php
include __DIR__ . '/Run.php';

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
	
}
