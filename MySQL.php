<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tatiana
 * Date: 7/18/12
 * Time: 3:14 PM
 * To change this template use File | Settings | File Templates.
 */
class MySQL
{
    var $hostname;
    var $username;
    var $password;
    var $database;
    var $dbConn;  // MySQL Resource link identifier

    public function MySQL($hostname,$username,$password,$database){
        $this->hostname = $hostname;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        $connection = $this->connectToDB();
        $this->select_db('web_page_test', $connection);
        $this->dbConn = $connection;
    }

    public function getDbConn(){
        return $this->dbConn;
    }

    public function connectToDB(){
        $db_conn = mysql_connect($this->hostname, $this->password, $this->username);
        if ($db_conn){
            echo "Connection to database was successful <br /><br />";
        }
        else{
            return mysql_error();
        }
        return $db_conn;
    }

    public function select_db($database, $connected){
        $selected = mysql_select_db($database, $connected);
        if($selected){
            echo "Selection of database ' " . $database . " ' was successful <br />";
        }
        else {
            return mysql_error();
        }
     return($selected);
    }

}
