<?php
$origin = isset($_SERVER['HTTP_ORIGIN'])? $_SERVER['HTTP_ORIGIN'] : '';  
  
$allow_origin = array(
    'http://domain1',
    'http://domain2'
);
  
if(in_array($origin, $allow_origin)){
    header('Access-Control-Allow-Origin:'.$origin);
}

// header('Access-Control-Allow-Origin:*');