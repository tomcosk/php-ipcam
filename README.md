# php-ipcam
PHP IP Camera processor and publisher

# Dependencies
* ImageMagick extension to php - if you want to use some datasource which is doing some image manipulation
* avconv binary to capture image from rtsp ip camera stream
* opencv and python if you want to use facedetection and blur faces

# Usage
```
$cam = new Ipcam();
$cam->setDebug(1);
$cam->addDataSource(new Signature());

while (true) {
	$cam->composeImage()->publish();
	sleep(30);
}
```

# List of available data Sources
you can use these as insiration to for creating your custom one

* Povodia - get actual height water from website
* Weather - get actual weather for city
* Signature - add watermark
