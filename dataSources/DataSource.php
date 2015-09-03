<?
namespace dataSources;

abstract class DataSource 
{
	abstract public function getName();
	abstract public function getDescription();
	abstract public function apply($options);

	public $cacheTimeMin = 10;
	protected $debug = 1;

	function __construct()
	{

	}

	public function log($msg, $level = 1) {
		if ($level <= $this->debug) {
			$date = date("d.m.Y G:i:s");
			$callers=debug_backtrace();
			$class = $callers[1]["class"];
			echo "[$date] [$class] $msg\n";
		}
	}

	public function setDebug($level) {
		$this->debug = $level;
	}

	public function setPosX($x) {
		$this->posX = $x;
		return $this;
	}

	public function setPosY($y) {
		$this->posY = $y;
		return $this;
	}

	public function setFontSize($size) {
		$this->fontSize = $size;
		return $this;
	}


}
?>