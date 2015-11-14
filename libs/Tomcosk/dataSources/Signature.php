<?php
namespace Tomcosk\dataSources;

use \Imagick;

/**
* Signature datasource to add watermark
*/
class Signature extends DataSource
{

	protected $url;	
	protected $html;
	protected $name = "Signature Plavba.sk";
	protected $description = "Podpis plavba.sk, resp. logo";
	protected $image = "libs/Tomcosk/dataSources/logo-plavba.png";

	function __construct()
	{
		return $this;
	}

	/**
	 * @param String $url
	 * @return \dataSources\Signature
	 */
	public function setUrl($url) {
		$this->url = $url;
		$this->html = file_get_html($this->url);
		return $this;
	}

	/**
	 * @return String
	 */
	public function getUrl() {
		return $this->url;
	}
	/* (non-PHPdoc)
	 * @see \dataSources\DataSource::getName()
	 */
	public function getName() {
		return $this->name;
	}

	/* (non-PHPdoc)
	 * @see \dataSources\DataSource::getDescription()
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @return string
	 */
	public function getValue() {
		return "Plavba.sk";
	}

	/* (non-PHPdoc)
	 * @see \dataSources\DataSource::apply()
	 */
	public function apply($options) {
		$folder = $options["folder"];
		$filename = $options["filename"];
		$src1 = new \Imagick($folder."/".$filename);
		$src2 = new \Imagick($this->image);

//		$src1->setGravity(Imagick::GRAVITY_SOUTHEAST);
//		var_dump($options);
		$src1->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
//		$src1->setImageArtifact('compose:args', "1,0,-0.5,0.5");
		$src1->compositeImage($src2, Imagick::COMPOSITE_DEFAULT, $src1->getImageWidth()-$src2->getImageWidth(), $src1->getImageHeight()-$src2->getImageHeight());
		$src1->writeImage($folder."/".$filename);
		$this->log("Added watermark");
		return true;
	}
}

?>