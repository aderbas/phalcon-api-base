<?php
// User routes collection
use Phalcon\Mvc\Micro\Collection as MicroCollection;

class UserCollection{

  public static function getCollection(){
    $collection = new MicroCollection();
    // set handler
    $collection->setHandler(new UserController());
    // set common prefix
    $collection->setPrefix('/user');
    // use metod of controller
    // get all
    $collection->get('/', 'getAll');
    // get by id
    $collection->get('/{id}', 'getUser');

    return $collection;
  }

}
