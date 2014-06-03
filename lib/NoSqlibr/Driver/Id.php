<?php 
namespace NoSqlibr\Driver;

class Id {

	public function __construct() {
		
	}

	public static function convert($id) {
		return new \MongoID($id);	
	}

}