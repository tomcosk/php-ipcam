<?
namespace dataSources;
use \Imagick;

/**
* Signature datasource to add watermark
*/
class Background extends DataSource
{

	protected $url;	
	protected $html;
	protected $name = "Background for data";
	protected $description = "";
	protected $posX = 0;
	protected $posY = 0;
	protected $width = 100;
	protected $height = 100;
	protected $fillColor = "#00000000";

	function __construct()
	{
		return $this;
	}

	public function setWidth($num) {
		$this->width = $num;
		return $this;
	}

	public function setHeight($num) {
		$this->height = $num;
		return $this;
	}

	public function setFillColor($color) {
		$this->fillColor = $color;
		return $this;
	}

	public function getName() {
		return $this->name;
	}

	public function getDescription() {
		return $this->description;
	}

	public function getImage() {

	}

	public function getValue() {
		return "Plavba.sk";
	}

	public function apply($options) {
		$folder = $options["folder"];
		$filename = $options["filename"];
		try {
			$src1 = new \Imagick($folder."/".$filename);

		    $bg = new \Imagick();
		    $bg->newImage($this->width, $this->height, $this->fillColor);
		    $bg->setImageFormat("png");

			$src1->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
	//		$src1->setImageArtifact('compose:args', "1,0,-0.5,0.5");
			$src1->compositeImage($bg, Imagick::COMPOSITE_DEFAULT, $this->posX, $this->posY);
			$src1->writeImage($folder."/".$filename);
			$this->log("Added watermark");
			return true;
		} catch(\ImagickException $e) {
			$this->log($e->getMessage());
			return false;
		}
	}
}

?>