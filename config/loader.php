<?php
$loader = new \Phalcon\Loader();
require 'vendor/composer/autoload_namespaces.php';
/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
  array(
   __DIR__ .  $config->application->controllersDir,
   __DIR__ .  $config->application->modelsDir
  )
);//->register();
// register vendor from composer
$map = require 'vendor/composer/autoload_namespaces.php';
$namespaces = array();
foreach ($map as $ns => $path) {
  $namespaces[$ns] = $path;
}
$loader->registerNamespaces($namespaces);

$loader->register();
