<?php 
namespace NoSqlConn;

interface ConnectorInterface {

	public function insert($data);

	public function update($data, $condition = array());

	public function remove($condition);

	public function getDb();

	public function getCollection($name = '');

	public function getDriver();

}