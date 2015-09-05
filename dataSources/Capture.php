<?
namespace dataSources;

/**
* Signature datasource to add watermark
*/
class Capture extends DataSource
{

	protected $url;	
	protected $html;
	protected $name = "Capture";
	protected $description = "Capture frame";

	function __construct($url)
	{
		$this->setUrl($url);
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
		$cmd = "avconv -rtsp_transport tcp -i ".$this->url." -f image2 -async 1 -vcodec mjpeg -vframes 1 -y $folder/$filename"; 
		exec($cmd);
		$this->log($cmd, 2);
		$this->log("Frame captured");
	}
}

?>
