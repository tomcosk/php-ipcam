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

# List of available data Sources
you can use these as insiration to for creating your custom one

* Povodia - get actual height water from website
* Weather - get actual weather for city
* Signature - add watermark
