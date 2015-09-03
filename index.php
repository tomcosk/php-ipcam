<?php
date_default_timezone_set("Europe/Bratislava");
include("Ipcam.php");
include("dataSources/Background.php");
include("dataSources/Povodia.php");
include("dataSources/Signature.php");
include("dataSources/Weather.php");

$cam = new Ipcam();
$cam->setDebug(1);
$cam->addDataSource(new Background())
		->setPosX(0)
		->setPosY(0)
		->setWidth(230)
		->setHeight(250)
		->setFillColor("#ffffff99");
$cam->addDataSource(new Povodia('http://www.povodia.sk/bh/sk/mereni_28.htm'))
		->setPosX(10)
		->setPosY(10)
		->setFontSize(20);
$cam->addDataSource(new Weather('http://www.yr.no/place/Slovakia/Košice/Vinné/forecast.xml'))
		->setPosX(10)
		->setPosY(70)
		->setFontSize(20);
$cam->addDataSource(new Signature());


while (true) {
	$cam->composeImage()->publish();
	sleep(30);
}

?>