<?php
// Util for anything

class Util{

  const ERROR_MAL_FORMATED_STRING = 306;
  const ERROR_GLOBAL = 300;
  const NO_ERROR = 0;

  // print result
  public static function printResult($data=null){
    try{
      echo json_encode(array(
        "result"=>$data,
        "msg" => "Data Result",
        "error"=>self::NO_ERROR
      ));
    }catch(Exception $e){
      echo json_encode(array(
        "result"=>$data,
        "msg" => "Malformed String",
        "error"=>self::ERROR_MAL_FORMATED_STRING
      ));
    }
  }

  // print error mensage
  public static function printError($msg='An unexpected error occurred, please try again.'){
    echo json_encode(array(
      "result"=>null,
      "msg"=>$msg,
      "error"=>self::ERROR_GLOBAL
    ));
  }

}
