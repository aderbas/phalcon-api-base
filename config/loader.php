<?php
$loader = new \Phalcon\Loader();
/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
  array(
   __DIR__.$config->application->controllersDir,
   __DIR__.$config->application->collectionsDir,
   __DIR__.$config->application->modelsDir
  )
);
// register namespaces
$map = require __DIR__.'/../vendor/composer/autoload_namespaces.php';
$namespaces = array();
foreach ($map as $ns => $path) {
  $namespaces[$ns] = $path;
}
$loader->registerNamespaces($namespaces);

$loader->register();
