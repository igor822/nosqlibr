<?php 
namespace NoSqlibr\Driver;

use NoSqlibr\Driver\DriverInterface;
use NoSqlibr\Exception as NoSqlException;

/**
 * Driver to connect, read and write data to mongodb
 * 
 * @author Igor Carvalho <igor822@gmail.com>
 * @since 2014-06-01
 */
class Mongo implements DriverInterface {

	private $data = null;

	protected $driver = null;

	private $db = null;

	private $collection = null;

	public function __construct($data) {
		$this->data = $data;
	}
	
	public function connect() {
		$this->driver = new \Mongo($this->dsn());
		return $this;
	}

	private function dsn() {
		return 'mongodb://'.$this->data['user'].':'.$this->data['pass'].'@'.$this->data['host'].':'.$this->data['port'];
	}
	
	/**
	 * Method to insert values to collection
	 * 
	 * @param $data Array of data with keys and values to add to collection
	 * @param $options Array of options to insert values
	 * @access public
	 * @throws NoSqlException\CollectionException
	 * @throws \Exception
	 */
	public function insert($data, $options = array()){
		if (empty($this->collection)) throw new NoSqlException\CollectionException('Please select an collection to insert your values');

		if (is_array($data)) {
			$rs = $this->getCollection()->insert($data, $options);
		} else {
			throw new \Exception('Values must be an array');
		}
		return $rs;
	}

	public function update($data, $condition = array()){

	}

	public function remove($condition){

	}

	public function find($condition = array()){
		return $this->collection->find($condition);
	}

	public function selectDB($name) {
		if (empty($this->driver)) $this->connect();
		$this->db = $this->driver->selectDB($name);
		return $this;
	}

	public function getConnections() {
		if (!empty($this->driver)) return $this->driver->getConnections();
	}

	public function getDB() {
		if (!empty($this->db)) return $this->db;
		else throw new \Exception('DB is not selected');
	}

	public function listCollections($incl = false) {
		return $this->getDB()->listCollections($incl);
	}

	public function selectCollection($name) {
		if (!empty($this->collection)) return $this->collection;
		return $this->collection = $this->getDB()->selectCollection($name);
	}

	public function createCollection($name, $options = array()) {
		if (!in_array($name, $this->getDB()->getCollectionNames())) {
			$defaultOptions = array('capped' => false, 'size' => '10*1024', 'max' => 10);
			$this->collection = $this->getDB()->createCollection($name, array_merge($defaultOptions, $options));
		} else {
			$this->selectCollection($name);
		}
		return $this;
	}

	public function getCollection() {
		return $this->collection;
	}

}