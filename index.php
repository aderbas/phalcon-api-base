<?php
// config file
@require 'config/config.php';
// loader
@require 'config/loader.php';

/**
 * Simple API using Phalcon and JWT
 * https://phalconphp.com | https://github.com/firebase/php-jwt
 *
 * @author Aderbal Nunes <aderbalnunes@gmail.com>
 * @license https://opensource.org/licenses/MIT
 * @link https://github.com/aderbas/phalcon-api-base
 */

use Phalcon\Mvc\Micro;
use \Firebase\JWT\ExpiredException;
use \Firebase\JWT\JWT;

$app = new Micro($di);
// router not found
$app->notFound(function () use ($app){
  $app->response->setStatusCode(404, "Not Found")->sendHeaders();
  $app->response->setJsonContent(array('error'=> 'Not Found'));
  return $app->response;
});
// return version
$app->get('/version', function() use ($app){
  $app->response->setJsonContent(array('version'=> '0.1.2'));
  return $app->response;
});

$app->before(function() use ($app) {
  if(!in_array($app->router->getRewriteUri(), $app->routerignore)){
    if(!$app->request->getHeader('Authorization')){
      Util::unauthorized('Access is not authorized')->send();
      return false;
    }else{
      try{
        // improvise Bearer token scheme
        $parts = explode(" ", $app->request->getHeader('Authorization'));
        if(trim($parts[0]) === 'Bearer'){
          JWT::decode($parts[1], $app->jwt->secret, $app->jwt->type);
        }else{
          Util::unauthorized('Invalid token format')->send();
          return false;
        }
      }catch(Exception $e){
        Util::unauthorized('Token is expired')->send();
        return false;
      }
    }
  }
  return true;
});
// auth
$app->post('/auth', function() use ($app){
  $params = $app->request->getJsonRawBody();
  //if(!isset($params)) $params = $_POST;
  $app->response->setJsonContent(array('error'=>'No params'));
  if(isset($params)){
    // try login
    // fake login
    if($params->email == 'aderbal@aderbalnunes.com' && $params->pwd == '123456'){
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
