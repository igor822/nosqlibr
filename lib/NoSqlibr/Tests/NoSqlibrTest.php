<?php 
namespace NoSqlibr\Tests;

require_once '../../../vendor/autoload.php';

class NoSqlibr extends \PHPUnit_Framework_TestCase {

	protected $conn = null;

	protected function setUp() {
		$this->conn = array(
			'driver' => 'mongo',
			'user' => 'admin',
			'pass' => 'admin',
			'host' => 'localhost',
			'port' => '27017',
			'db' => 'test'
		);
	}

	/**
	 * @expectedException Exception
	 */
	public function testInstantiateClassThrowsException() {
		$noSql = new \NoSqlibr\NoSqlibr();
	}

	public function testInstantiateObjectWithConnectionData() {
		$noSql = new \NoSqlibr\NoSqlibr($this->conn);

		$this->assertTrue($noSql->getDB()->isConnected());

		return $noSql;
	}

	/**
	 * @depends testInstantiateObjectWithConnectionData
	 * @expectedException Exception
	 */
	public function testGetInvalidCollectionWithNotSelectedDB(\NoSqlibr\NoSqlibr $noSql) {
		unset($this->conn['db']);
		$noSql = new \NoSqlibr\NoSqlibr($this->conn);

		$noSql = new \NoSqlibr\NoSqlibr($this->conn);		
		$col = $noSql->getCollection('aaaaa');
	}

	public function testGetValidCollection() {
		$noSql = new \NoSqlibr\NoSqlibr($this->conn);
		$col = $noSql->getCollection('any_data');

		$this->assertInstanceOf('MongoCollection', $col);

		return $noSql;
	}

	/**
	 * @depends testGetValidCollection
	 */ 
	public function testGetMultipleCollections(\NoSqlibr\NoSqlibr $noSql) {
		$this->assertInternalType('array', $noSql->getCollection());

		$noSql->getCollection('users');

		$this->assertEquals(2, count($noSql->getCollection()));
	}

	public function testSelectCollectionDirectly() {
		$noSql = new \NoSqlibr\NoSqlibr($this->conn);

		$this->assertInstanceOf('MongoCursor', $noSql->connectTo('test.users')->find());
		$this->assertCount(2, $noSql->connectTo('test.users')->find()->limit(2));
	}

	public function testInsertCreateCollection() {
		$noSql = new \NoSqlibr\NoSqlibr($this->conn);

		$this->assertInstanceOf('MongoCollection', $noSql->connectTo('test.test_col'));
		$this->assertEquals(true, $noSql->connectTo('test.test_col')->insert(array('test' => 1)));
	}

}