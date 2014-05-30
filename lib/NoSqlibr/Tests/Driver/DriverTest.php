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

	/**
	 */
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

		return $driver;
	}

	/**
	 * @depends testInstantiateDriver
	 */
	public function testDriverConnection(\NoSqlibr\Driver\Driver $driver) {
		$this->assertNotEmpty($driver);

		$this->assertTrue($driver->isConnected());
	}

}