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
  $return = true;
  if(!in_array($app->router->getRewriteUri(), $app->routerignore)){
    if(!$app->request->getHeader('Authorization')){
      $app->response->setStatusCode(403, 'Unauthorized');
      $app->response->setContent('You have no rights');
      $return = false;
    }else{
      try{
        // improvise Bearer token scheme
        $parts = explode(" ", $app->request->getHeader('Authorization'));
        if(trim($parts[0]) === 'Bearer'){
          JWT::decode($parts[1], $app->jwt->secret, $app->jwt->type);
        }else{
          $app->response->setJsonContent(array('error'=>'Invalid token'));
          $return = false;
        }
      }catch(Exception $e){
        $app->response->setJsonContent(array('error'=>'Expired token'));
        $return = false;
      }
    }
  }
  $app->response->send();
  return $return;
});
// auth
$app->post('/auth', function() use ($app){
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
