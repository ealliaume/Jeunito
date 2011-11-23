<?php
class Jeunito_Database_Doctrine_TestCase extends PHPUnit_Extensions_Database_TestCase
{

	private $_dataSetPath;

	public function setUp() 
	{
		parent::setUp();
		Doctrine_Manager::connection($this->getDoctrineCnxnString());
		Doctrine::loadModels(APPLICATION_PATH . '/models');
	}


	protected function getConnection()
	{
		$pdo = new PDO(
			"mysql:host=localhost;dbname=" . $GLOBALS['DB_NAME'], 
			$GLOBALS['DB_USER'], 
			$GLOBALS['DB_PASSWORD']
		);
		return $this->createDefaultDBConnection($pdo, $GLOBALS['DB_NAME']);
	}
	
	protected function getDataSet()
	{
		if (!isset($GLOBALS['DB_SEED'])) {
			throw new Exception('No Data Set.');
		}
		$dataSetPath = $GLOBALS['DB_SEED'];
		return $this->createXMLDataSet($dataSetPath);
	}

	private function getDoctrineCnxnString() 
	{
		$user = $GLOBALS['DB_USER'];
		$pass = $GLOBALS['DB_PASSWORD'];
		$dbnm = $GLOBALS['DB_NAME'];

		return "mysql://$user:$pass@localhost/$dbnm";
	}

}
