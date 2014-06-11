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
		$dsn_user = '';
		if (!empty($this->data['user']) && !empty($this->data['pass'])) $dsn_user = $this->data['user'].':'.$this->data['pass'].'@';
		return 'mongodb://'.$dsn_user.$this->data['host'].':'.$this->data['port'];
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

	/**
	 * Method to update document by setting a new documento to object
	 * 
	 * @param $criteria Descriptin of the objects to update
	 * @param $newObject The object with which to update the matching records
	 * @access public
	 * @throws NoSqlException\CollectionException
	 * @throws \Exception
	 */
	public function update($criteria = array(), $newObject = array(), $options = array()) {
		if (empty($this->collection)) throw new NoSqlException\CollectionException('Please select an collection to update your values');

		if (is_array($criteria) && !empty($newObject)) {
			$rs = $this->getCollection()->update($criteria, $newObject, $options);
		} else {
			throw new \Exception('You must set and new object to update');
		}

		return $rs;
	}

	/**
	 * Method to remove data of collections
	 *
	 * @see http://www.php.net/manual/pt_BR/mongocollection.remove.php
	 */
	public function remove($criteria = array(), $options = array()) {
		$rs = $this->getCollection()->remove($criteria, $options);
		return $rs;
	}

	public function find($condition = array()){
		return $this->getCollection()->find($condition);
	}

	/**
	 * Method to select what DB will be used
	 *
	 * @see http://www.php.net/manual/pt_BR/mongoclient.selectdb.php
	 */
	public function selectDB($name) {
		if (empty($this->driver)) $this->connect();
		$this->db = $this->driver->selectDB($name);
		return $this;
	}

	/**
	 * Method to get current active connections
	 *
	 * @see http://www.php.net/manual/pt_BR/mongoclient.getconnections.php
	 */ 
	public function getConnections() {
		if (!empty($this->driver)) return $this->driver->getConnections();
	}

	public function getDB() {
		if (!empty($this->db)) return $this->db;
		else throw new \Exception('DB is not selected');
	}

	/**
	 * Method to list all collections into current db 
	 *
	 * @see http://www.php.net/manual/pt_BR/mongodb.listcollections.php
	 */
	public function listCollections($incl = false) {
		return $this->getDB()->listCollections($incl);
	}

	/**
	 * Method to select what collection will be used 
	 *
	 * @see http://www.php.net/manual/pt_BR/mongoclient.selectcollection.php
	 */
	public function selectCollection($name) {
		if (!empty($this->collection)) return $this->collection;
		return $this->collection = $this->getDB()->selectCollection($name);
	}

	/**
	 * Method to create a new collection into database
	 *
	 * @see http://www.php.net/manual/pt_BR/mongodb.createcollection.php
	 */
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
		if (empty($this->collection)) throw new \Exception('You must connecto to database todo something');
		return $this->collection;
	}

}