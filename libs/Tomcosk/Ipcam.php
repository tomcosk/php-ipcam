<?php
namespace Tomcosk;
include("simple_html_dom.php");
use Tomcosk\Config as c;
use Tomcosk\dataSources\DataSource;

//date_default_timezone_set('CET');
/**
* 
*/
class Ipcam 
{
	
	protected $dataSource = [];
	protected $publisher = null;
	protected $folder = null;
	protected $workDir = null;
	protected $filename = null;
	protected $debug = 1;
	protected $lastReturnValue = true;

	protected $timeStamp;

	/**
	 * @param string $folder
	 * @param string $workDir
	 * @param string $filename
	 */
	function __construct($folder = "./", $workDir = "./workdir", $filename = "cam.jpg")
	{
		$this->setFolder($folder);
		$this->setWorkDir($workDir);
		$this->setFilename($filename);
	}

	/**
	 * @param String $path
	 */
	public function setFolder($path) {
		$this->folder = $path;
	}
	
	/**
	 * @param String $path
	 */
	public function setWorkDir($path) {
		$this->workDir = $path;
	}
	
	/**
	 * @param String $filename
	 */
	public function setFilename($filename) {
		$this->filename = $filename;
	}

	/**
	 * @param Int $level
	 * @return Ipcam
	 */
	public function setDebug($level) {
		$this->debug = $level;
		return $this;
	}
	
	/**
	 * @param Publisher $publisher
	 * @return Ipcam
	 */
	public function setPublisher($publisher) {
		$this->publisher = $publisher;
		return $this;
	}
	
	/**
	 * @return Publisher
	 */
	public function getPublisher() {
		return $this->publisher;
	}
	
	/**
	 * @param DataSource $source
	 * @return multitype:
	 */
	public function addDataSource($source) {
		$source->setDebug($this->debug);
		$this->dataSource[] = $source;

		return $this->dataSource[count($this->dataSource)-1];
	}

	/**
	 * @param Int $index
	 * @return Array:
	 */
	public function getDataSource($index) {
		return $this->dataSource[$index];
	}

	public function removeDataSource($index) {

	}

	/**
	 *	echo list of datasources 
	 */
	public function displayDataSources() {
		foreach ($this->dataSource as $key => $value) {
			echo $key." => ".$value->getName()."\n";
		}
	}

	/**
	 * IPCam log method
	 * @param String $msg
	 * @param Int $level
	 */
	public function log($msg, $level=1) {
		if ($level <= $this->debug) {
			$date = date("d.m.Y G:i:s");
			echo "[$date] [Ipcam] $msg\n";
		}
	}

	/**
	 *	echo the values of datasources 
	 */
	public function getValues() {
		foreach ($this->dataSource as $key => $value) {
			$val = $value->getValue();
			if (is_string($val)) {
				echo $val."<br />\n";
			}
		}
	}

	/**
	 * Execute every datasource
	 * @return Ipcam
	 */
	public function composeImage() {
		$this->timeStamp = new \DateTime();

		foreach ($this->dataSource as $key => $value) {
			$ret = true;
			$this->log("executing: ".$value->getName(),2);
			$ret = $value->apply(["folder"=>c::get("workDir"), "filename"=>c::get("filename")]);
			if (!$ret) {
				$this->lastReturnValue = $ret;
				break;
			}
			$this->lastReturnValue = $ret;
		}
		return $this;
	}

	/**
	 * Publish via publisher
	 * @return number
	 */
	public function publish() {
		if(!empty($this->publisher)) {
			if ($this->lastReturnValue) {
				$this->publisher->publish();
			} else {
				$this->log("Not publishing. Somethign goes wrong in some datasource");
			}
		} else {
			$this->log("Not publishing. Publisher not found");
		}
		$currentTime = new \DateTime();
		$diff = $currentTime->diff($this->timeStamp);
		$diffSeconds = $diff->s + ($diff->i * 60) + ($diff->h * 60) + ($diff->d * 24 * 60);
		$this->log("Took: $diffSeconds secs");
		return $diffSeconds;
	}
}
?>