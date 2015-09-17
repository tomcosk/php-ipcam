<?
namespace dataSources;
use DateTime;
/**
* Povodia datasource
*/
class Povodia extends DataSource
{

	protected $url;	
	protected $html;
	protected $name = "Povodia";
	protected $description = "Aktualne hladina zo stranky povodia.sk";
	public $cacheTimeMin = 720;	// 12 hours
	protected $lastUpdated;
	protected $fontSize = 20;
	protected $posX = 0;
	protected $posY = 0;
	protected $icon = "dataSources/icon-wave.png";

	function __construct($url, $x = 0, $y = 0, $fontsize = 20)
	{
		$this->setUrl($url);
		$this->setPosY($y);
		$this->setPosX($x);
		$this->setFontSize($fontsize);
		return $this;
	}

	public function setUrl($url) {
		$this->url = $url;
		$this->getFreshData();
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
		$tables = $this->html->find('html body table tbody tr td table tbody tr td table');
		$tr = $tables[3]->find("tr");
		unset($tr[0]);
		foreach ($tr as $key => $value) {
			$datum = trim(str_replace("&nbsp;", " ", strip_tags($value->find("td")[0]->innertext)));
			$date = DateTime::createFromFormat('d.m.y H:i', $datum);
			$ar["value"] = trim(str_replace(",", ".", str_replace("&nbsp;", " ", strip_tags($value->find("td")[1]->innertext))));
			$ar["date"] = $date;
			$stav[] = $ar;
		}
		return $stav[0]["value"];
	}

	protected function getFreshData() {
		$this->html = file_get_html($this->url);
		$this->lastUpdated = new DateTime();
		$this->log("Getting fresh data");
	}

	public function apply($options) {
		$currentTime =new \DateTime();
		$diff=$currentTime->diff($this->lastUpdated);
		$diffMinutes = $diff->i + ($diff->h*60) + ($diff->d *24*60);
		if ($diffMinutes > $this->cacheTimeMin) {
			$this->getFreshData();
		}

		$folder = $options["folder"];
		$filename = $options["filename"];
		$src1 = new \Imagick($folder."/".$filename);
		$icon = new \Imagick($this->icon);
		$draw = new \ImagickDraw();
		$src1->setImageVirtualPixelMethod(\Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
		/* Black text */
		$draw->setFillColor('black');

		/* Font properties */
		$draw->setFont('Helvetica.ttf');
		$draw->setFontSize( $this->fontSize );
		$src1->compositeImage($icon, \Imagick::COMPOSITE_DEFAULT, $this->posX, $this->posY);

		/* Create text */
		$src1->annotateImage($draw, $icon->getImageWidth()+5+$this->posX, $this->posY+$this->fontSize*2, 0, $this->getValue()." m.n.m.");
		$src1->writeImage($folder."/".$filename);
		$this->log('Info written to image');
		return true;

	}
}

?>