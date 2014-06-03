<?php 
namespace NoSqlibr\Tests;

require_once '../../../vendor/autoload.php';

class DriverTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @expectedException Exception
	 */
	public function testInstantiateEmptyDriverThrowsException() {
		$driver = new \NoSqlibr\Driver\Driver();
	}

	public function testInstantiateDriver() {
		$data = array(
			'driver' => 'mongo',
			'user' => 'admin',
			'pass' => 'admin',
			'host' => 'localhost',
			'port' => '27017'
		);

		$driver = new \NoSqlibr\Driver\Driver($data);

		$this->assertInstanceOf('NoSqlibr\Driver\Driver', $driver);
		$this->assertNotEmpty($driver->getDriver());
		$this->assertInstanceOf('NoSqlibr\Driver\Mongo', $driver->getDriver());
		$this->assertInstanceOf('NoSqlibr\Driver\DriverInterface', $driver->getDriver()); //check if has interface instance

		return $driver;
	}

	/**
	 * @depends testInstantiateDriver
	 */
	public function testDriverConnection(\NoSqlibr\Driver\Driver $driver) {
		$this->assertNotEmpty($driver);

		$this->assertTrue($driver->isConnected());
		
		$driver->getDriver()->connect();
		$this->assertInternalType('array', $driver->getDriver()->getConnections());

		$db = $driver->getDriver()->selectDB('test');
		$this->assertInstanceOf('NoSqlibr\Driver\DriverInterface', $db);

		return $driver;
	}

	/**
	 * @depends testDriverConnection
	 */
	public function testSelectCollections(\NoSqlibr\Driver\Driver $driver) {
		$col = $driver->getDriver()->selectCollection('users');
		$this->assertNotEmpty($col);
		$this->assertInstanceOf('MongoCollection', $col);

		$rs = $col->find(array('job' => 'CEO'));

		$this->assertInstanceOf('MongoCursor', $rs);
		$this->assertEquals(7, $rs->count());

		return $rs;
	}


	/**
	 * @depends testSelectCollections
	 */
	public function testIterationResultSet(\MongoCursor $rs) {
		$this->assertNotEmpty($rs);

		$next = $rs->getNext();
		$this->assertEquals('CEO', $next['job']);
	}

	/**
	 * @depends testSelectCollections
	 */
	public function testIteratorToArray(\MongoCursor $rs) {
		$rsArr = iterator_to_array($rs);
		$this->assertInternalType('array', $rsArr);
		$this->assertEquals(7, count($rsArr));
	}

	public function testCreateCollectionAndValidateIfHasCreated() {
		$data = array(
			'driver' => 'mongo',
			'user' => 'admin',
			'pass' => 'admin',
			'host' => 'localhost',
			'port' => '27017'
		);

		$colName = 'any_data';

		$driver = new \NoSqlibr\Driver\Driver($data);
		$db = $driver->getDriver()->selectDB('test')->createCollection($colName);
		
		$this->assertInstanceOf('NoSqlibr\Driver\DriverInterface', $db);
		$this->assertEquals($colName, $db->getCollection()->getName());

		return $db;
	}

	/**
	 * @depends testCreateCollectionAndValidateIfHasCreated
	 */
	public function testInsertValuesIntoCollection(\NoSqlibr\Driver\Mongo $db) {
		$this->assertNotEmpty($db);

		$a = $db->insert(array('x' => mt_rand(10000, 900000), 'y' => md5(mt_rand(1000, 9000))));
		$this->assertTrue($a);

		$b = $db->insert(array('x' => mt_rand(10000, 900000), 'y' => md5(mt_rand(1000, 9000)), 'test' => 'blabla'));
		$this->assertTrue($b);

		$this->assertNotEmpty($db->find(array('test' => 'blabla')));

	}
}