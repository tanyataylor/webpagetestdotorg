<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tatiana
 * Date: 7/18/12
 * Time: 4:16 PM
 * To change this template use File | Settings | File Templates.
 */


error_reporting(E_ALL | E_STRICT);
ini_set("display_errors", 1);
//require_once(getDirName()."/MySQL.php");
require_once("MySQL.php");

class MySqlConnect{

    public function _init(){
        try{
            $cred = $this->getCredentials();
            return($this->assignValues($cred));

        }
        catch (Exception $e){
            die($e->getMessage());
        }
    }

    public function getCredentials(){
        $credentials = parse_ini_file("/var/www/testWebSite/credentialsWPT.ini");
        $keys = array();
        $values = array();
        foreach ($credentials as $key => $value){
            $keys[] = $key;
            $values[] = $value;
        }
       // var_dump($values);
        return($values);
    }

    public function assignValues(array $values = array()){
        $hostname = $values[0];
        $password = $values[1];
        $username = $values[2];
        $database = "web_page_test";
        $db = new MySQL($hostname, $password, $username, $database);
        return $db->getDbConn();

    }

}
/*
try {
    $wrap = new MySqlConnect();
    $db_conn = $wrap->__init();
}
catch (Exception $e){
    echo $e->getMessage(), "\n";
}
*/