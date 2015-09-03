<?

include("simple_html_dom.php");
//include("dataSources/DataSource.php");
use Config as c;

date_default_timezone_set('CET');
/**
* 
*/
class Ipcam 
{
	
	protected $dataSource = [];
	protected $publisher;
	protected $folder = null;
	protected $workDir = null;
	protected $filename = null;
	protected $debug = 1;

	function __construct($folder = "./", $workDir = "./workdir", $filename = "cam.jpg")
	{
		$this->setFolder($folder);
		$this->setWorkDir($workDir);
		$this->setFilename($filename);
	}

	public function setFolder($path) {
		$this->folder = $path;
	}
	
	public function setWorkDir($path) {
		$this->workDir = $path;
	}
	
	public function setFilename($filename) {
		$this->filename = $filename;
	}

	public function setDebug($level) {
		$this->debug = $level;
		return $this;
	}
	
	public function setPublisher($publisher) {
		$this->publisher = $publisher;
		return $this;
	}
	
	public function getPublisher() {
		return $this->publisher;
	}
	
	public function addDataSource($source) {
		$source->setDebug($this->debug);
		$this->dataSource[] = $source;

		return $this->dataSource[count($this->dataSource)-1];
	}

	public function getDataSource($index) {
		return $this->dataSource[$index];
	}

	public function removeDataSource($index) {

	}

	public function displayDataSources() {
		foreach ($this->dataSource as $key => $value) {
			echo $key." => ".$value->getName()."\n";
		}
	}

	public function log($msg, $level=1) {
		if ($level <= $this->debug) {
			$date = date("d.m.Y G:i:s");
			echo "[$date] [Ipcam] $msg\n";
		}
	}

	public function getValues() {
		foreach ($this->dataSource as $key => $value) {
			$val = $value->getValue();
			if (is_string($val)) {
				echo $val."<br />\n";
			}
		}
	}

	public function composeImage() {
		foreach ($this->dataSource as $key => $value) {
			$this->log("executing: ".$value->getName(),2);
			$value->apply(["folder"=>c::get("workDir"), "filename"=>c::get("filename")]);
		}
		return $this;
	}

	public function publish() {
		$this->publisher->publish();
	}
}
?>