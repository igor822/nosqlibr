<?php 
namespace NoSqlibr\Driver;

/**
 * Class to load driver of NoSql database and verifies if is connected or not
 * 
 * @author Igor Carvalho <igor822@gmail.com>
 * @version 0.0.1
 * @license MIT
 * @since 2014-05-30
 */
class Driver {

	private $driver = null;

	/**
	 * Class constructor
	 * Get the data connection to retrieve driver
	 *
	 * @param $data Array of data connection, containing the username password, host 
	 *        and port to connects to driver
	 * @access public
	 * @return void
	 */
	public function __construct($data) {
		if (empty($data)) {
			throw new \Exception('The data connection is missing');
		}
		
		$this->loadDriver($data);
	}

	/**
	 * Load and initialize driver
	 *
	 * @param $data Array of data connection, containing the username password, host 
	 *        and port to connects to driver
	 * @return $driver to manipulate data
	 */
	public function loadDriver($data) {
		try {
			// check if the driver is instantiated yet.
			if (!empty($this->driver)) return $this->driver;
			$ns = __NAMESPACE__.'\\'.ucfirst($data['driver']);
			$this->driver = new $ns($data);
		} catch (\Exception $e) {
			throw new \Exception('There is no Driver allowed to you');
		}
		return $this->driver;
	}

	/**
	 * Get driver to manipulate data
	 *
	 * @return $driver to manipulate data
	 */
	public function getDriver() {
		return $this->driver;
	}

	/**
	 * Check if driver is connected or not
	 * 
	 * @return boolean to verify if is connected
	 */ 
	public function isConnected() {
		return !empty($this->driver);
	}

}