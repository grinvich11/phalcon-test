<?php

$loader = new \Phalcon\Loader();


$loader->registerNamespaces([
	'app\Forms'       => $config->application->formsDir,
]);

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    array(
        $config->application->controllersDir,
        $config->application->modelsDir
    )
)->register();
