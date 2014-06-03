<?php 
namespace NoSqlibr\Driver;

use NoSqlibr\Driver\ResultSetInterface;

class Cursor extends \MongoCursor implements ResultSetInterface {

	public function __construct() {
		parent::__construct();
	}

	public function count() {
		return parent::count();
	}

	public function next() {
		return parent::next();
	}

	public function hasNext();

	public function skip(int $num);

	public function sort();

	public function serialize();

	public function unserialize(string $serialized);

}