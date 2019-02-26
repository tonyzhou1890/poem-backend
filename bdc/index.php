<?php
// 引入数据库相关信息
require('../common/database.php');
// 引入工具文件
require('../common/utils.php');
// 允许跨域
require('../common/cros.php');

// 全局变量
$GLOBALS['globalLimit'] = 200;
$GLOBALS['searchLimit'] = 50;

// 是否有请求
hasParams();

// 连接数据库
$db = mysqli_connect($HOST,$USER,$PASSWORD);
if(!$db){
  printResult(1, '');
}

// 设定编码
mysqli_query($db,"set names 'utf8'");

// 选择诗词库
mysqli_select_db($db,"english_dict");

if (isset($_GET['c'])) {  // 获取单词列表
  if (is_numeric($_GET["c"])) {
    getWords($_GET["c"]);
  } else {
    printResult(20, 'c');
  }
} else if (isset($_GET['s'])) {
  if (!empty($_GET['s'])) {
    search($_GET['s']);
  } else {
    printResult(20, 's');
  }
} else {
  printResult(23, '');
}

// 获取单词列表
function getWords($c) {
  $start = ($c - 1) * $GLOBALS['globalLimit'] + 1;
  $end = $start + $GLOBALS['globalLimit'] - 1;
  $sql = "SELECT id, word, phonetic, word_explain FROM 40000_words WHERE id BETWEEN $start AND $end";
  // echo $sql;
  // die();
  $res = commonQuery($sql);
  $arr = [];
  foreach($res as $key => $val) {
    $temp = new StdClass();
    $temp -> order = $val['id'];
    $temp -> word = $val['word'];
    $temp -> phonetic = $val['phonetic'];
    $temp -> explain = $val['word_explain'];
    $arr[$key] = $temp;
  }
  printResult(0, $arr);
}

// 搜索单词
function search($s) {
  $limit = $GLOBALS['searchLimit'];
  $sql = "SELECT id, word, phonetic, word_explain FROM 40000_words WHERE word REGEXP '^$s' ORDER BY word LIMIT $limit";
  $res = commonQuery($sql);
  $arr = [];
  foreach($res as $key => $val) {
    $temp = new StdClass();
    $temp -> order = $val['id'];
    $temp -> word = $val['word'];
    $temp -> phonetic = $val['phonetic'];
    $temp -> explain = $val['word_explain'];
    $arr[$key] = $temp;
  }
  printResult(0, $arr);
}