<?
include("simple_html_dom.php");
include("dataSources/DataSource.php");
include("config.php");
date_default_timezone_set('CET');
/**
* 
*/
class Ipcam 
{
	
	protected $dataSource = [];
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
		copy($this->filename, $this->workDir."/".$this->filename);
		foreach ($this->dataSource as $key => $value) {
			$this->log("executing: ".$value->getName(),2);
			$value->apply(["folder"=>$this->workDir, "filename"=>$this->filename]);
		}
		return $this;
	}

	public function publish() {
		$ftp_server=$ftpHost; 
		$ftp_user_name=$ftpLogin; 
		$ftp_user_pass=$ftpPass; 
		$file = $this->workDir."/".$this->filename;
		$remote_file = "/web/ipcam/cam.jpg"; 

		// set up basic connection 
		$conn_id = ftp_connect($ftp_server); 

		// login with username and password 
		$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass); 

		// upload a file 
		if (ftp_put($conn_id, $remote_file, $file, FTP_ASCII)) { 
		    $this->log("successfully uploaded $file"); 
		} else { 
		    $this->log("There was a problem while uploading $file"); 
	    } 
		// close the connection 
		ftp_close($conn_id); 
	}
}
?>