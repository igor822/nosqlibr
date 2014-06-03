<?php 
namespace NoSqlibr\Driver;

interface DriverInterface {

	public function connect();
	
	public function insert($data);

	public function update($data, $condition = array());

	public function remove($condition);

	public function find($condition = array());

	public function selectDB($name);

	public function getConnections();

}