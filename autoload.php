<?php
function __autoload($class) {
	$class = "" . str_replace("\\", "/", $class) . ".php";
	if(file_exists($class)) {
		require_once($class);
	} else {
		throw new Exception("Class $class not found", 1001);
	}
}
