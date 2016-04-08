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
  // /error_log(print_r($app->response,true));
  if(!in_array($app->router->getRewriteUri(), $app->routerignore)){
    if(!$app->request->getHeader('Authorization')){
      $app->response->setStatusCode(403, 'Unauthorized');
      $app->response->setContent('You have no rights');
      $app->response->send();
      return false;
    }else{
      try{
        $decode = JWT::decode($app->request->getHeader('Authorization'), $app->jwt->secret, $app->jwt->type);
      }catch(ExpiredException $e){
        $app->response->setJsonContent(array('error'=>'Expired token'));
      }
      return $app->response;
    }
  }
  return true;
});

$app->post('/api/auth', function() use ($app){
  $params = $app->request->getJsonRawBody();
  if(!isset($params)) $params = $_POST;
  //error_log(print_r($params, true));
  $app->response->setJsonContent(array('error'=>'No params'));
  if(isset($params)){
    // try login
    if($params['email'] == 'aderbal@aderbalnunes.com' && $params['pwd'] == md5('123456')){
      $user = (object) array(
        "iat" => 1356999524,
        "nbf" => 1357000000
      );
      $user->name = 'Aderbal Nunes';
      $user->email = 'aderbal@aderbal.com';
      $app->response->setJsonContent(array('token'=> JWT::encode($user, $app->jwt->secret)));
      //$app->response->setJsonContent(array('token'=> $user));
    }else{
      $app->response->setJsonContent(array('error'=>'Email or Password not match'));
    }
  }
  return $app->response;
});

$app->get('/api/token', function() use ($app){
  $key = "example_key";

  $token = array(
    "iss" => "http://example.org",
    "aud" => "http://example.com",
    "iat" => 1356999524,
    "nbf" => 1357000000
  );

  $jwt = JWT::encode($token, $key);
  echo $jwt;
});

$app->get('/api/test', function() use ($app){
  $app->response->setJsonContent(array(''=>''));
  return $app->response;
});

$app->get('/api/user', function() use ($app){
  $app->response->setJsonContent(array('result'=>array('Foo', 'Bar')));
  return $app->response;
});

$app->error(
  function (Exception $e) {
    echo $e->getMessage();
  }
);

//
$app->handle();
