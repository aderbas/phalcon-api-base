<?php
// User controler
class UserController extends Phalcon\Mvc\Controller{

  public function index(){
    // to do
  }

  // get all users
  public function getAll(){
    // return fake data
    Util::printResult(array(
      array("name"=>"Tiago", "email"=>"tiago@domain.com", "id"=>213),
      array("name"=>"Bal", "email"=>"bal@domain.com", "id"=>123)
    ));
  }

  // get user by id
  public function getUser($id){
    // return fake data
    Util::printResult((object)array(
      "name"=>"Bal", "email"=>"bal@domain.com", "id"=>123
    ));
  }

}
