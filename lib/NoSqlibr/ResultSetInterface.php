<?php 
namespace NoSqlibr\Driver;

interface ResultSetInterface {

	public function count();

	public function next();

	public function hasNext();

	public function skip(int $num);

	public function sort();

	public function serialize();

	public function unserialize(string $serialized);

}