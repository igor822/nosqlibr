<?php 
namespace NoSqlibr\Exception;

class CollectionException extends \RuntimeException {

	public function __construct($message, $code = 10) {
		parent::__construct($message, $code);
	}

}