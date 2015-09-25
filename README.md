# php-ipcam
PHP IP Camera processor and publisher.

Usefull for:

1. capturing image from IP camera using RTSP protocol
2. then insert some usefull information into picture like current weather, watermark, do facedetect and blur faces, etc. 3. then publish it to some web site via publisher.

# Dependencies
* ImageMagick extension to php - if you want to use some datasource which is doing some image manipulation
* avconv binary to capture image from rtsp ip camera stream
* opencv and python if you want to use facedetection and blur faces

# Usage
```php
$cam = new Ipcam();
$cam->setDebug(1);
$cam->addDataSource(new Signature());

while (true) {
	$cam->composeImage()->publish();
	sleep(30);
}
```
For more complex usage, see index.php

### Run script from command line
* basic run 
```
php index.php
```
* run in background
```
nohup php index.php &
```

# List of available data Sources
you can use these as insiration to for creating your custom one

* Povodia - get actual height water from website
* Weather - get actual weather for city
* Signature - add watermark
* Capture - capture 1 actual frame from RTSP ip camera stream

# List of available publishers
you can use these publishers as inspiration of how to publish file to remote server

* FTPPublusher - publish via FTP
* SFTPPublusher - publish via SFTP (Secure FTP)
