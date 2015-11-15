<?php
namespace Tomcosk\dataSources;
use Tomcosk\Config as c;

/**
* Signature datasource to archive picture
*/
class Archiver extends DataSource
{

	protected $url;	
	protected $html;
	protected $name = "Archive file";
	protected $description = "Archive file to disk";
	protected $destination = null;
	protected $cacheTimeSec = 60;
	protected $lastUpdated;

	/**
	 * @param int $cacheTimeSec
	 */
	public function setCacheTimeSec($cacheTimeSec)
	{
		$this->cacheTimeSec = $cacheTimeSec;
		return $this;
	}

	/**
	 * @param null $destination
	 * @return Archiver
	 */
	public function setDestination($destination)
	{
		$this->destination = $destination;
		return $this;
	}

	protected function getPath() {
		$fileparts = pathinfo(c::get("filename"));
		return $this->destination.$fileparts["filename"]."_".date("U").".".$fileparts["extension"];
	}

	function __construct()
	{
		return $this;
	}

	public function getName() {
		return $this->name;
	}

	public function getDescription() {
		return $this->description;
	}

	public function getValue() {
		return "Plavba.sk";
	}

	public function apply($options) {
		$folder = $options["folder"];
		$filename = $options["filename"];
		if(empty($this->lastUpdated)) {
			copy($folder."/".$filename, $this->getPath());
			$this->log("First frame saved to ".$this->destination);
			$this->lastUpdated = new \DateTime();
		}
		$currentTime =new \DateTime();
		$diff=$currentTime->diff($this->lastUpdated);
		$diffSeconds = $diff->s + ($diff->i*60) + ($diff->h*60) + ($diff->d *24*60);
		if ($diffSeconds > $this->cacheTimeSec) {
			copy($folder."/".$filename, $this->getPath());
			$this->log("Frame saved to ".$this->destination);
			$this->lastUpdated = new \DateTime();
		}

		return true;
	}
}

?>
