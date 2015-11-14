<?php
/**
 * @author tomas.igrini
 * Abstract datasource class
 */
namespace Tomcosk\dataSources;

use Tomcosk\Config as c;

abstract class DataSource 
{
	abstract public function getName();
	abstract public function getDescription();
	abstract public function apply($options);

	public $cacheTimeMin = 10;
	protected $storage = null;
	protected $storageConnection = null;
	protected $debug = 1;
	protected $colors = [
					"black" => "\033[30m",
					"green" => "\033[32m",
					"red" => "\033[31m",
					"default" => ""
					];

	function __construct()
	{

	}

	public function setStorageConnection($conn) {
		$this->storageConnection = $conn;
		$this->setStorage(new \Pixie\QueryBuilder\QueryBuilderHandler($conn));
		return $this;
	}

	public function enableStorage($config = null) {
		if (empty($config)) {
			$config = c::get("DB");
		}
		new \Pixie\Connection('mysql', $config, 'QB');
		$row = QB::table('stats')->find(3);
		var_dump($row);

		return $this;
	}

	/**
	 * @return null
	 */
	public function getStorage()
	{
		return $this->storage;
	}

	/**
	 * @param null $storage
	 */
	public function setStorage($storage)
	{
		$this->storage = $storage;
	}

	/**
	 * Datasource Log function
	 * @param String $msg
	 * @param number $level
	 * @param string $color
	 */
	public function log($msg, $level = 1, $color="default") {
		$colorCode = $this->colors[$color];
		if ($level <= $this->debug) {
			$date = date("d.m.Y G:i:s");
			$callers=debug_backtrace();
			$class = $callers[1]["class"];
			if (is_array($msg)) {
				print_r($msg);
			} else {
				if (!empty($this->color) && !empty($this->colors[$color])) {
					$colorCode = $this->colors[$color];
				}
				echo $colorCode."[$date] [$class] $msg\n";
			}
		}
	}

	/**
	 * @param Int $level
	 */
	public function setDebug($level) {
		$this->debug = $level;
	}

	/**
	 * @param Int $x
	 * @return \dataSources\DataSource
	 */
	public function setPosX($x) {
		$this->posX = $x;
		return $this;
	}

	/**
	 * @param Int $y
	 * @return \dataSources\DataSource
	 */
	public function setPosY($y) {
		$this->posY = $y;
		return $this;
	}

	/**
	 * @param Int $size
	 * @return \dataSources\DataSource
	 */
	public function setFontSize($size) {
		$this->fontSize = $size;
		return $this;
	}


}
?>
