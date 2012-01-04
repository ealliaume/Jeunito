<?php
class Jeunito_Database_Doctrine_TestCase extends PHPUnit_Extensions_Database_TestCase
{

	private $_dataSetPath;

	private $_useCustomDataSet = false;


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

		if (!$this->_useCustomDataSet) {
			if (!isset($GLOBALS['DB_SEED'])) {
				if (!is_null($_dataSetPath)) {
		  			throw new Exception('No Data Set.');	
				} 
				$dataSetPath = $this->_dataSetPath;
			} else {
				$dataSetPath = $GLOBALS['DB_SEED'];
			}
		} else {
			$dataSetPath = $this->_dataSetPath;
			if (is_null($dataSetPath)) {
				throw new Exception('No Data Set.');
			}
		}
		
		if ($this->_useCustomDataSet) {
			return $this->createMySQLXMLDataSet($dataSetPath);
		}
		return $this->createXMLDataSet($dataSetPath);
	}

	private function getDoctrineCnxnString() 
	{
		$user = $GLOBALS['DB_USER'];
		$pass = $GLOBALS['DB_PASSWORD'];
		$dbnm = $GLOBALS['DB_NAME'];

		return "mysql://$user:$pass@localhost/$dbnm";
	}

	protected function setDataSetPath($dataSetPath) 
	{	
		$this->_useCustomDataSet = true;
		$this->_dataSetPath = $dataSetPath;
	}

}
