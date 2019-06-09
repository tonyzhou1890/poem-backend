<?php
// 引入数据库相关信息
require('../common/database.php');
// 引入工具文件
require('../common/utils.php');
// 允许跨域
require('../common/cros.php');

// 全局变量
$GLOBALS['globalLimit'] = 20;

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
mysqli_select_db($db,"poem");

// 响应请求
if (isset($_GET["home"])) { // 首页
  if('true' === $_GET["home"]) {
    home();
  } else {
    printResult(20, 'home');
  }
} else if (isset($_GET["id"])) {  // 获取某篇诗词
  if (is_numeric($_GET["id"])) {
    getPoem($_GET["id"]);
  } else {
    printResult(20, 'id');
  }
} else if (isset($_GET['author'])) { // 查询作者
  if ('all' === $_GET['author']) { // 获取作者列表
    if (isset($_GET['p']) && is_numeric($_GET['p']) && $_GET['p'] >= 1) {
      getAuthors($_GET['p']);
    } else {
      printResult(20, 'p');
    }
  } else {  // 获取某个作者介绍和诗词列表
    if (isset($_GET['p']) && is_numeric($_GET['p']) && $_GET['p'] >= 1) {
      getSomeonePoems($_GET['author'], $_GET['p']);
    } else {
      printResult(20, 'p');
    }
  }
} else if (isset($_GET['poem'])) { // 查询所有诗词
  if ('all' === $_GET['poem']) { // 获取诗词列表
    if (isset($_GET['p']) && is_numeric($_GET['p']) && $_GET['p'] >= 1) {
      getPoems($_GET['p']);
    } else {
      printResult(20, 'p');
    }
  } else {
    printResult(20, 'poem');
  }
} else if (isset($_GET['keyword']) && isset($_GET['type'])) { // 关键字搜索
  if (!strlen($_GET['keyword'])) {
    printResult(20, 'keyword');
  }
  // if (!is_string($_GET['type'])) {
  //   printResult(20, 'type');
  // }
  if (!strlen($_GET['type'])) {
    printResult(20, 'type');
  }
  if ('all' === $_GET['type']) {  // 作者、标题、内容都搜索
    searchKeywordInAll($_GET['keyword']);
  }
  if (isset($_GET['p'])) {
    if (is_numeric($_GET['p']) && $_GET['p'] >= 1) {
      if ('author' === $_GET['type']) { // 根据作者搜索
        searchKeywordInAuthor($_GET['keyword'], $_GET['p']);
      } else if ('title' === $_GET['type']) { // 根据标题搜索
        searchKeywordInTitle($_GET['keyword'], $_GET['p']);
      } else if ('content' === $_GET['type']) { // 根据内容搜索
        searchKeywordInContent($_GET['keyword'], $_GET['p']);
      } else {
        printResult(20, 'type');
      }
    } else {
      printResult(20, 'p');
    }
  } else {
    printResult(23, '');
  }
} else {
  printResult(23, '');
  // print_r(file_get_contents('./README.md'));
}

// 首页请求
// function home() {
//   // 随机诗词
//   $poems = random_poem();
//   // 所有作者
//   $author = get_author();
//   // 随机20名作者
//   $author_keys = array_rand($author,20);
//   $authors = [];
//   foreach($author_keys as $key => $val){
//     $authors[] = $author[$val];
//   }
//   $result = new StdClass();
//   $result -> poems = $poems;
//   $result -> authors = $authors;
//   printResult(0, $result);
// }
function home() {
  $poem_sql = "SELECT _id, mingcheng, zuozhe, zhaiyao FROM poem LIMIT 10";
    $poem_result_array = commonQuery($poem_sql);
    $author_result_array = commonQuery("SELECT xingming FROM author LIMIT 20");
    foreach($author_result_array as $key=>$val){
    $author_result_array[$key] = $author_result_array[$key]["xingming"];
  }
  $result = new StdClass();
  $result -> poems = $poem_result_array;
  $result -> authors = $author_result_array;
  printResult(0, $result);
}


// 随机十篇诗
function random_poem(){
  // 获取诗词总数量
  $max = getTotal('_id', 'poem');
  // 随机数池
  $rand_pool = [];
  while(count($rand_pool) < 10){
    $rand = mt_rand(1, $max);
    if(!in_array($rand, $rand_pool)){
         $rand_pool[] = $rand;
    }
  }
  // 要预先处理下$rand_pool
  $str = implode(",",$rand_pool);
  // 查询诗词语句
  $sql = "SELECT _id, mingcheng, zuozhe, zhaiyao FROM poem WHERE _id in ($str)"; 
  $result_array = commonQuery($sql);
  $result_obj = array2object($result_array);
  return $result_array;
}

// 获取某篇诗词
function getPoem($id) {
  $sql = "SELECT _id, mingcheng, zuozhe, yuanwen FROM poem WHERE _id = ".$id;
  $resultArray = commonQuery($sql);
  $resultObj = new StdClass();
  if (count($resultArray) !== 0) {
    $resultObj = $resultArray[0];
  }
  printResult(0, $resultObj);
}

// 获取作者列表
function getAuthors($page) {
  $limit = 200;
  $start = ($page - 1) * $limit;
  $max = getTotal('_id', 'author');
  $sql = "SELECT xingming FROM author ORDER BY _id LIMIT $start, $limit";
  $resultArray = commonQuery($sql);
  foreach($resultArray as $key => $val) {
    $resultArray[$key] = $val['xingming'];
  }
  $resultObj = new StdClass();
  $resultObj -> total = $max;
  $resultObj -> limit = $limit;
  $resultObj -> data = $resultArray;
  printResult(0, $resultObj);
}

// 获取诗词列表
function getPoems($page) {
  $limit = $GLOBALS['globalLimit'];
  $start = ($page - 1) * $limit;
  $max = getTotal('_id', 'poem');
  $sql = "SELECT _id, mingcheng, zuozhe, zhaiyao FROM poem ORDER BY _id LIMIT $start, $limit";
  $resultArray = commonQuery($sql);
  $resultObj = new StdClass();
  $resultObj -> total = $max;
  $resultObj -> limit = $limit;
  $resultObj -> data = $resultArray;
  printResult(0, $resultObj);
}

// 获取所有作者
function get_author(){
  $result_array = commonQuery("SELECT xingming FROM author");
  foreach($result_array as $key=>$val){
    $result_array[$key] = $result_array[$key]["xingming"];
  }
  return $result_array; 
}

// 获取某位作者的诗词
function getSomeonePoems($author, $page) {
  $limit = $GLOBALS['globalLimit'];
  $start = ($page - 1) * $limit;
  $max = getParamTotal('zuozhe', $author, 'poem');
  $authorInfo = getAuthorInfo($author);
  $sql = "SELECT _id, mingcheng, zuozhe, zhaiyao FROM poem WHERE zuozhe = '$author' LIMIT $start, $limit";
  $resultArray = commonQuery($sql);
  $resultObj = new StdClass();
  $resultObj -> total = $max;
  $resultObj -> limit = $limit;
  $resultObj -> authorInfo = $authorInfo;
  $resultObj -> poems = $resultArray;
  printResult(0, $resultObj);
}

// 获取作者信息
function getAuthorInfo($author) {
  $sql = "SELECT * FROM author WHERE xingming = '$author'";
  $resultArray = commonQuery($sql);
  $resultObj = new StdClass();
  if (count($resultArray) !== 0) {
    $resultObj = $resultArray[0];
  }
  return $resultObj;
}

// 搜索关键字，作者、标题、内容都包括
function searchKeywordInAll($keyword) {
  $author = searchKeywordInParam($keyword, 1, 'zuozhe');
  $title = searchKeywordInParam($keyword, 1, 'mingcheng');
  $content = searchKeywordInParam($keyword, 1, 'yuanwen');
  $resultObj = new StdClass();
  $resultObj -> author = $author;
  $resultObj -> title = $title;
  $resultObj -> content = $content;
  printResult(0, $resultObj);
}

// 搜索关键字——作者
function searchKeywordInAuthor($keyword, $page) {
  $resultObj = searchKeywordInParam($keyword, $page, 'zuozhe');
  printResult(0, $resultObj);
}

// 搜索关键字——标题
function searchKeywordInTitle($keyword, $page) {
  $resultObj = searchKeywordInParam($keyword, $page, 'mingcheng');
  printResult(0, $resultObj);
}

// 搜索关键字——内容
function searchKeywordInContent($keyword, $page) {
  $resultObj = searchKeywordInParam($keyword, $page, 'yuanwen');
  printResult(0, $resultObj);
}

// 搜索关键字
function searchKeywordInParam($keyword, $page, $param) {
  $limit = $GLOBALS['globalLimit'];
  $start = ($page - 1) * $limit;
  $max = getParamLikeTotal($param, $keyword, 'poem');
  $sql = "SELECT _id, mingcheng, zuozhe, zhaiyao FROM poem WHERE $param LIKE '%$keyword%' LIMIT $start, $limit";
  $resultArray = commonQuery($sql);
  $resultObj = new StdClass();
  $resultObj -> total = $max;
  $resultObj -> limit = $limit;
  $resultObj -> data = $resultArray;
  return $resultObj;
}