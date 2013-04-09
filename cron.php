<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tatiana
 * Date: 8/6/12
 * Time: 3:37 PM
 * To change this template use File | Settings | File Templates.
 */



error_reporting(E_ALL | E_STRICT);
ini_set("display_errors", 1);

function __autoload($class_name){
    $file = $class_name.'.php';
    if(file_exists($file)){
        var_dump($file);
        require_once($file);
    }
    else{
        throw new Exception("Unable to load $class_name.");
    }
}

try{
    $wrap = new MySqlConnect();
    $db_conn = $wrap->_init();
    $StatusMessages = new StatusMessages;
    $url = file_get_contents("StaticURLs.txt");
    $url = explode("\n", $url);
    foreach($url as $singleUrl){
        $output = $StatusMessages->parseFile($db_conn, $singleUrl);
    }

    $getPageData = new getPageData;
    foreach($url as $singleUrl){
        $outputPage = $getPageData->parseCSV($db_conn, $singleUrl);
    }

    $getCSV = new getCSV;
    foreach($url as $singleUrl){
        $outputCSV = $getCSV->parseCSV($db_conn, $singleUrl);
    }

}

catch(Exception $e){
    die($e->getMessage());
}





