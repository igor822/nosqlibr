<?php 
namespace NoSqlibr;

use NoSqlibr\ConnectorInterface;
use NoSqlibr\Driver\Driver;

/**
 * Class to create and manipulate data from driver
 *
 * @author Igor Carvalho <igor822@gmail.com>
 * @since 2014-06-04
 * @license MIT
 */
class NoSqlibr implements ConnectorInterface {

	private $collections = array();

	private $connection;

	private $driver;

	/**
	 * Constructor class
	 * Instanciate and validates data connection
	 *
	 * @param $conn Array with data to connect to database
	 * @access public
	 * @return void
	 */
	public function __construct($conn) {
		$this->connection = $conn;
		$this->validateDataConnection();
		$this->connect();
	}

	/**
	 * Method to connect to database, is permissible to connect to others databses
	 *
	 * @param $conn Optional param to set data connection
	 * @access public
	 * @return $this
	 */
	public function connect($conn = null) {
		$conn = !empty($conn) ? $conn : $this->connection;
		$this->driver = new Driver($conn);

		$this->driver->getDriver()->connect();
		$this->useDb($conn['db']);
		return $this;
	}

	/**
	 * Method to validate data connection and throws exception if some data is missing
	 *
	 * @access private
	 * @return void
	 * @throws Exception when some data is missing
	 */
	private function validateDataConnection() {
		if (empty($this->connection['db']) || 
			empty($this->connection['user']) || 
			empty($this->connection['pass']) ||
			empty($this->connection['host']) ||
			empty($this->connection['port'])) 
			throw new \Exception('You need to set a db to be selected');
	}

	/**
	 * Method to retrieve db object
	 *
	 * @access public
	 * @return $driver Driver to manipulate 
	 * @throws Exception when driver is not connected
	 */
	public function getDB() {
		if (empty($this->driver)) throw new \Exception('Driver is not connected, please check your connection data');
		return $this->driver;
	}

	/**
	 * Method to get collection(s)
	 *
	 * @param $name Optional name to get object or array of all collections
	 * @access public
	 * @return mixed $collections
	 * @throws Exception When try to get an invalid collection
	 */
	public function getCollection($name = '') {
		if ($name === '') return $this->collections;
		$this->collections[$name] = $this->driver->getDriver()->selectCollection($name);
		
		if (!empty($this->collections[$name]->validate()['errmsg'])) {
			$this->driver->getDriver()->createCollection($name);
			return $this->getCollection($name);
			//throw new \Exception('You selected an invalid collection');
		}

		return $this->collections[$name];
	}

	/**
	 * Method to select db to work with object
	 *
	 * @param $name String of db name
	 * @access public
	 * @return $this
	 */
	public function useDb($name) {
		$this->driver->getDriver()->selectDB($name);
		return $this;
	}

	/**
	 * Method connect to databse and get collection by passing a db_name.collection
	 *
	 * @param $db string name with db_name.collection 
	 * @access public
	 * @return MongoCollection object with collection 
	 * @throws Exception when string param is not in pattern db_name.collecton
	 */
	public function connectTo($db) {
		$collection = '';
		if (preg_match('/(\S+)[\.](\S+)/i', $db) !== false) {
			list($db, $collection) = explode('.', $db);
		} else throw new \Exception('You must indicate the db_name.collection to fetch object');

		$this->useDb($db);
		return $this->getCollection($collection);
	}
}