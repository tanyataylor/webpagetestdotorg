<?php

error_reporting(E_ALL | E_STRICT);
ini_set("display_errors", 1);

function getDirName(){
    $path = getcwd();
    $lib_path = $path."/lib/";
    set_include_path(get_include_path() . PATH_SEPARATOR . $path);
    return $lib_path;
}

function __autoload($class_name){
    $file = getDirName() . $class_name . '.php';
    if(file_exists($file)){
        require_once($file);
    }
    else {
        throw new Exception("Unable to load $class_name.");
    }
}
try{
    $connection = new MySqlConnect();
}
catch (Exception $e){
    echo $e->getMessage(), "\n";
}

?>