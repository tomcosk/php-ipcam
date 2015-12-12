<?php
namespace Tomcosk\dataSources;

/**
* Signature datasource to add watermark
*/
class CaptureJpeg extends DataSource
{

	protected $url;	
	protected $html;
	protected $type = null;
	protected $host = null;
	protected $name = "CaptureJpeg";
	protected $description = "Capture frame from JPEG URL";

	function __construct($url, $type="image", $host=null)
	{
		$this->setUrl($url);
		$this->type = $type;
		$this->host = $host;
		return $this;
	}

	public function setUrl($url) {
		$this->url = $url;
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

		if($this->type == "url") {
			$url = file($this->url);
			$pattern = '/"([^"]+)"/';
			$url = preg_match($pattern, $url[0], $matches);
			$url = $matches[1];
			$this->setUrl($this->host.$url);
		}

		$opts=array(
		    "ssl"=>array(
		    "verify_peer"=>false,
		    "verify_peer_name"=>false,
		    ),
		);
		file_put_contents($folder."/".$filename, fopen($this->url, 'r', false, stream_context_create($opts)));
		$this->log($this->url, 2);
		$this->log("Frame captured from ".$this->url);
		return true;
	}
}

?>
