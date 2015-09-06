<?
namespace dataSources;
use DateTime;

/**
* Weather datasource
*/
class OpenWeatherMap extends DataSource
{

	protected $url;	
	protected $json;
	protected $name = "Pocasie";
	protected $description = "Pocasie pre aktualne mesto";
	protected $lastUpdated;
	protected $fontSize = 20;
	protected $posX = 0;
	protected $posY = 0;
	protected $iconWind = "dataSources/icon-wind.png";
	protected $iconTemp = "dataSources/icon-temp.png";
	protected $appId = null;

	function __construct($url, $x = 10, $y = 90, $fontsize = 20)
	{
		$this->setUrl($url);
		$this->setPosY($y);
		$this->setPosX($x);
		$this->setFontSize($fontsize);
		return $this;
	}

	public function setUrl($url) {
		$this->url = $url;
//		echo $this->url;
		$this->getFreshData();
		return $this;
	}

	public function setAPIKey($key) {
		$this->appId = $key;
		return $this;
	}

	public function getUrl() {
		return $this->url;
	}
	public function getName() {
		return $this->name;
	}

	public function getDescription() {
		return $this->description;
	}

	public function getValue() {
		$current = json_decode($this->json);
		$temperature = $current->main->temp;
		$windSpd = $current->wind->speed;
		$windDir = round($current->wind->deg);

		return ["temp"=>$temperature, "windSpeed"=>$windSpd, "windDir"=>$windDir];
	}

	protected function getFreshData() {
		$handle = fopen($this->url."&appid=".$this->appId, "rb");
		$this->json = stream_get_contents($handle);
		fclose($handle);

		$this->lastUpdated = new DateTime();
		$this->log("Getting fresh data");
	}

	public function apply($options) {

		$currentTime =new DateTime();
		$diff=$currentTime->diff($this->lastUpdated);
		if ($diff->i > $this->cacheTimeMin) {
			$this->getFreshData();
		}
//		$this->log($diff->i." > ".$this->cacheTimeMin);
		$folder = $options["folder"];
		$filename = $options["filename"];
		$src1 = new \Imagick($folder."/".$filename);
		$iconWind = new \Imagick($this->iconWind);
		$iconTemp = new \Imagick($this->iconTemp);
		$arrowImg = new \Imagick("dataSources/arrow.png");
		$arrowImg->rotateImage(new \ImagickPixel('#00000000'), $this->getValue()["windDir"]);
		$draw = new \ImagickDraw();
		/* Black text */
		$draw->setFillColor('black');

		/* Font properties */
		$draw->setFont('Helvetica.ttf');
		$draw->setFontSize( $this->fontSize );

		$posX1 = $iconTemp->getImageWidth()+$this->posX;
		$posY1 = $iconTemp->getImageHeight()+$this->posY;

		$src1->compositeImage($iconTemp, \Imagick::COMPOSITE_DEFAULT, $this->posX, $this->posY);
		$src1->compositeImage($iconWind, \Imagick::COMPOSITE_DEFAULT, $this->posX, $posY1);
		$src1->compositeImage($arrowImg, \Imagick::COMPOSITE_DEFAULT, 170, $posY1+20);

		$src1->annotateImage($draw, $iconTemp->getImageWidth()+5+$this->posX, $this->posY+$this->fontSize*2, 0, $this->getValue()["temp"]." C");
		$src1->annotateImage($draw, $posX1, $posY1 + $this->fontSize * 2 , 0, $this->getValue()["windSpeed"]." m/s");
		$src1->writeImage($folder."/".$filename);
		$this->log('Info written to image');

	}
}

?>