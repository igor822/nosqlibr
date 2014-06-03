<?php 
namespace NoSqlibr\Driver;

interface DriverInterface {

	public function connect();
	
	public function insert($data, $options = array());

	public function update($criteria = array(), $newObject = array(), $options = array());

	public function remove($condition = array(), $options = array());

	public function find($condition = array());

	public function selectDB($name);

	public function getConnections();

}