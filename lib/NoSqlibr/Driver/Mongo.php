<?php 
namespace NoSqlibr\Driver;

use NoSqlibr\Driver\DriverInterface;

class Mongo implements DriverInterface {

	private $data = null;

	private $driver = null;

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
	
	public function insert($data){

	}

	public function update($data, $condition = array()){

	}

	public function remove($condition){

	}

	public function find($condition = array()){

	}

	public function getDriver() {
		return $this->driver;
	}

}