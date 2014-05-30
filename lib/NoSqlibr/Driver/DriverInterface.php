<?php 
namespace NoSqlibr\Driver;

interface DriverInterface {

	public function connect();

	public function dsn($data);
	
	public function insert($data);

	public function update($data, $condition = array());

	public function 


}