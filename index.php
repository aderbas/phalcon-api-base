<?php
// config file
@require 'config/config.php';
// loader
@require 'config/loader.php';

// Phalcon API
use Phalcon\Mvc\Micro;
use \Firebase\JWT\ExpiredException;
use \Firebase\JWT\JWT;

$app = new Micro($di);
$app->notFound(function () use ($app){
  $app->response->setStatusCode(404, "Not Found")->sendHeaders();
});

$app->before(function() use ($app) {
  if(!in_array($app->router->getRewriteUri(), $app->routerignore)){
    if(!$app->request->getHeader('Authorization')){
      $app->response->setStatusCode(403, 'Unauthorized');
      $app->response->setContent('You have no rights');
      $app->response->send();
      return false;
    }else{
      try{
        JWT::decode($app->request->getHeader('Authorization'), $app->jwt->secret, $app->jwt->type);
      }catch(Exception $e){
        $app->response->setJsonContent(array('error'=>'Expired token'));
        $app->response->send();
        return false;
      }
    }
  }
  return true;
});
// auth
$app->post('/api/auth', function() use ($app){
  $params = $app->request->getJsonRawBody();
  if(!isset($params)) $params = $_POST;
  //error_log(print_r($params, true));
  $app->response->setJsonContent(array('error'=>'No params'));
  if(isset($params)){
    // try login
    // fake login
    if($params['email'] == 'aderbal@aderbalnunes.com' && $params['pwd'] == md5('123456')){
      // token params
      $user = (object) array(
        "iat" => 1356999524,
        "nbf" => 1357000000
      );
      // user param
      $user->name = 'Aderbal Nunes';
      $user->email = 'aderbal@aderbal.com';
      $app->response->setJsonContent(array('token'=> JWT::encode($user, $app->jwt->secret)));
    }else{
      $app->response->setJsonContent(array('error'=>'Email or Password not match'));
    }
  }
  return $app->response;
});
// mount collections
$app->mount(UserCollection::getCollection());

$app->error(
  function (Exception $e) {
    echo $e->getMessage();
  }
);

//
$app->handle();
