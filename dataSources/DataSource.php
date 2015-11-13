<?
/**
 * @author tomas.igrini
 * Abstract datasource class
 */
namespace dataSources;

abstract class DataSource 
{
	abstract public function getName();
	abstract public function getDescription();
	abstract public function apply($options);

	public $cacheTimeMin = 10;
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
