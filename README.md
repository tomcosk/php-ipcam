# php-ipcam
PHP IP Camera processor and publisher

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
