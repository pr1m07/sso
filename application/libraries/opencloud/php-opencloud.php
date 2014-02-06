<?php

require_once(__DIR__ . '/Autoload.php');

$classLoader = new ClassLoader;
$classLoader->registerNamespaces(array(
	'OpenCloud' => array(__DIR__, __DIR__ . '/../tests')
));
$classLoader->register();