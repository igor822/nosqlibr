<?php 
namespace NoSqlibr;

interface ConnectorInterface {

	public function getDB();

	public function getCollection($name = '');

}