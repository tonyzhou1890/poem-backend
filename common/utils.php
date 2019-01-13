<?php
//查询数据库
function commonQuery($query_statement){
  global $db;
  $result = mysqli_query($db,$query_statement);

  // 查询错误
  if(!$result){
    printResult(2, '');
  }

  // 结果为空
  if(!mysqli_num_rows($result)){
    return [];
  }
  
  $result_array = mysqli_fetch_all($result, MYSQLI_ASSOC);
  mysqli_free_result($result);
  return $result_array;
}

//输出结果
function printResult($code, $param){
  global $db;
  $obj = new StdClass();
  $obj -> code = $code;
  $errorMsg = '';
  $data = new StdClass();
  switch($code) {
    case 0:
      $errorMsg = '';
      $data = $param;
      break;
    case 1:
      $errorMsg = '数据库连接错误';
      break;
    case 2:
      $errorMsg = '查询错误';
      break;
    case 20:
      $errorMsg = '参数'.$param.'错误';
      break;
    case 21:
      $errorMsg = '参数'.$param.'不能为空';
      break;
    case 22:
      $errorMsg = '参数'.$param.'类型错误';
      break;
    case 23:
      $errorMsg = '参数错误';
      break;
    default:
      $errorMsg = '未知错误';
      break;
  }
  $obj -> errorMsg = $errorMsg;
  $obj -> data = $data;

  // 关闭数据库连接
  mysqli_close($db);

  // 输出结果
  print_r(json_encode($obj));
  // print_r($obj);
  die();
}

// 没有请求参数
function hasParams() {
  if((is_array($_GET)&&count($_GET)==0)&&(is_array($_POST)&&count($_POST)==0)){
    echo '不是正确的请求';
    die();
  }
}

// 获取总数
function getTotal($param, $table) {
  $query_count = "SELECT COUNT(".$param.") FROM ".$table;
  $count_result = commonQuery($query_count);
  $max = $count_result[0]['COUNT('.$param.')'];
  return $max;
}

// 根据字段筛选获取总数
function getParamTotal($param, $value, $table) {
  $sql = "SELECT COUNT($param) FROM $table WHERE $param = '$value'";
  $query_count = $sql;
  $count_result = commonQuery($sql);
  $max = $count_result[0]['COUNT('.$param.')'];
  return $max;
}

// 根据字段(like)筛选获取总数
function getParamLikeTotal($param, $value, $table) {
  $sql = "SELECT COUNT($param) FROM $table WHERE $param LIKE '%$value%'";
  $count_result = commonQuery($sql);
  $max = $count_result[0]['COUNT('.$param.')'];
  return $max;
}

//数组和对象的转换
//数组转对象
function array2object($array) {
  if (is_array($array)) {
    $obj = new StdClass();
    foreach ($array as $key => $val){
      $obj->$key = $val;
    }
  }
  else { $obj = $array; }
  return $obj;
}
//对象转数组
function object2array($object) {
  if (is_object($object)) {
    foreach ($object as $key => $value) {
      $array[$key] = $value;
    }
  }
  else {
    $array = $object;
  }
  return $array;
}