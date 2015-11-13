<?php
/**
 * @author tomas.igrini
 * Abstract Publisher class
 *
 */
namespace publisher;

abstract class Publisher
{
	abstract public function publish();
	
	protected $debug = 1;
	
	/**
	 * Publisher log method
	 * @param String $msg
	 * @param Int $level
	 */
	public function log($msg, $level = 1) {
		if ($level <= $this->debug) {
			$date = date("d.m.Y G:i:s");
			$callers=debug_backtrace();
			$class = $callers[1]["class"];
			echo "[$date] [$class] $msg\n";
		}
	}

	/**
	 * @param Int $level
	 */
	public function setDebug($level) {
		$this->debug = $level;
	}

}
?>