<?php
date_default_timezone_set("Europe/Bratislava");
//require_once 'autoload.php';
require 'vendor/autoload.php';
use Tomcosk\Config as c;

$cam = new \Tomcosk\Ipcam();
$cam->setDebug(1);

$cam->addDataSource(new Tomcosk\dataSources\CaptureJpeg("http://localhost/livecamera.jpg"));
//$cam->addDataSource(new \dataSources\Capture("rtsp://192.168.1.161:554/11"));
//$cam->addDataSource(new \dataSources\Facedetect());
$cam->addDataSource(new Tomcosk\dataSources\Background())
		->setPosX(0)
		->setPosY(0)
		->setWidth(230)
		->setHeight(250)
		->setFillColor("#ffffff99");	// last two chars are alpha channel (transparency)
$cam->addDataSource(new Tomcosk\dataSources\Povodia('http://www.povodia.sk/bh/sk/mereni_28.htm'))
        ->setStorageConnection($connection = new \Pixie\Connection('mysql', c::get("DB")))
		->setPosX(10)
		->setPosY(10)
		->setFontSize(20);

//$cam->addDataSource(new \dataSources\Weather('http://www.yr.no/place/Slovakia/Košice/Vinné/forecast.xml'))
$cam->addDataSource(new Tomcosk\dataSources\OpenWeatherMap('http://api.openweathermap.org/data/2.5/weather?id=723224&units=metric', c::get("openWeatherApiKey")))
		->setAPIKey(c::get("openWeatherApiKey"))
		->setPosX(10)
		->setPosY(70)
		->setFontSize(20);
$cam->addDataSource(new Tomcosk\dataSources\Signature());

//$cam->setPublisher(new publisher\SFTPPublisher());

while (true) {
	$time = $cam->composeImage()->publish();
	$sleepTime = c::get("sleep")-$time;
	if ($sleepTime < 0) {
		$sleepTime = 0;
	}
	// we want to do it every number of seconds. So we must calculate the time spent on processing and substract it from this time of sleep
	$cam->log("Sleeping $sleepTime secs");
	sleep($sleepTime);
}

?>