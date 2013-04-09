<pre>
<?php
#!/usr/bin/php
/*
 * Author: Tatiana Taylor
 */
error_reporting(E_ALL | E_STRICT);
ini_set("display_errors", 1);
require_once("MySqlConnect.php");

class StatusMessages{
    protected $headers;
    public function getFile($singleUrl){
        try{
            $data = file_get_contents('http://www.webpagetest.org/runtest.php?url='.urlencode($singleUrl).'&k=28acbc72b51e4aaca585d14e8148aa12&f=xml');
            $xml = simplexml_load_string($data);
            //var_dump($xml);

            $statusCode = 100;
            while($statusCode < 199)
            {
                sleep(15);
                $contentsXML = file_get_contents($xml->data->xmlUrl);
                //var_dump($contentsXML);
                $xmlOutput = simplexml_load_string($contentsXML);
                //var_dump($xmlOutput);
                $statusCode = (int) $xmlOutput->statusCode;
                //var_dump($statusCode);
            }
            echo "<br />";
            $csvFile = trim(file_get_contents($xml->data->xmlUrl));
            //var_dump($csvFile);
            $response = simplexml_load_string($csvFile);
            //var_dump($response);
            $message = (file_get_contents($response->data->run->firstView->pages->screenShot));
            echo "---------------------------------------------------------------------------------------------------";
            //var_dump($message);
            //var_dump($response->data->run->firstView->pages->screenShot);
            return $message;


            //$data = file_get_contents('http://www.webpagetest.org/result/120803_M8_M2S/1/screen_shot/');
            //var_dump($data);
        }
        catch(Exception $e){}
    }
    public function parseFile($db_conn = null, $singleUrl){
        if($db_conn === null){
            throw new Exception("database link not given", 0);
            return array();
        }
        $var = $this->getFile($singleUrl);
        //var_dump($var);
        $messageStr = $this->getStatusMessageStr($var);
        var_dump($messageStr);
        $table_name = $this->getTableName($messageStr);
        $column_names = $this->getColumnNames($messageStr, $table_name);
        var_dump($column_names);
        $inserted_table = $this->insertTable($column_names, $db_conn);
        $values_name = $this->getValues($messageStr, $column_names);
        var_dump($values_name);
        $inserted_values = $this->insertValues($values_name, $db_conn);
        //var_dump($inserted_values);
    }
    public function getStatusMessageStr($data){
        $regex = "#Status Messages.*?</table>#is";
        $extracted_table = preg_match($regex,$data,$match);
        $match = implode(",", $match);
        return $match;
    }
    public function getTableName($data){
        $table_name = "#Status Messages.*?#is";
        $extracted_table = preg_match($table_name,$data,$table);
        $table = $table[0];
        $table = str_replace(" ", "_", $table);
        $table = str_replace("S", "s", $table);
        $table = str_replace("M", "m", $table);
        $template = "CREATE TABLE IF NOT EXISTS ". $table . " ( id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,";
        return $template;
    }
    public function runSQL($query, $db_conn){
        $runQuery = mysql_query($query,$db_conn);
        if($runQuery === false){
            echo mysql_error();
        }
        return $runQuery;
    }

    public function getColumnNames($messageStr, $template){
        $col_time = "#(Time).*?</th>#is";
        $extracted_time = preg_match($col_time, $messageStr, $time);
        $time = $time[1];
        $template .= $time . " TEXT, ";
        $col_message = "#Message.*?#is";
        $extracted_time = preg_match($col_message, $messageStr, $mess);
        $mess = $mess[0];
        $template .= $mess . " TEXT);";
        $this->headers = $template;
        //var_dump($template);
        return($template);
    }
    public function insertTable($created_table, $db_conn){
        $success = $this->runSQL($created_table, $db_conn);
        return $success;
    }
    public function getValues($messageStr, $headers){
        $insert_statement = $headers;
        $insert_statement = str_replace("TEXT", "", $insert_statement);
        $insert_statement = str_replace("id INT NOT NULL AUTO_INCREMENT PRIMARY KEY," , "", $insert_statement);
        $insert_statement = str_replace(";", "", $insert_statement);
        $insert_statement = str_replace("CREATE TABLE IF NOT EXISTS ", "INSERT INTO ", $insert_statement);
        $insert_statement .= " VALUES ";
        $time = "#time.*?</td>#s";
        $extracted_time = preg_match_all($time, $messageStr, $m_time);
        $m_time = $m_time[0];
        $messages = "#<td>.*?</td>#is";
        $extracted_message = preg_match_all($messages, $messageStr, $m_message);
        $m_message = $m_message[0];
        foreach($m_time as $key=>$line){
            $input_array = array("time"=>$line, "message"=>$m_message[$key]);
            $insert_statement .= "('$line', '$m_message[$key]'),";
        }
        $insert_statement = strip_tags($insert_statement);
        $insert_statement = str_replace('time">', "", $insert_statement);
        $insert_statement .= ";";
        $insert_statement = str_replace("),;", ");", $insert_statement);
        //var_dump($insert_statement);
        return $insert_statement;
    }
    public function insertValues($insertedValues, $db_conn){
        $success = $this->runSQL($insertedValues, $db_conn);
        return $success;
    }


}
/*
try{
    $wrap = new MySqlConnect();
    $db_conn = $wrap->_init();
    $StatusMessages = new StatusMessages;
    $url = file_get_contents("StaticURLs.txt");
    $url = explode("\n", $url);
    foreach($url as $singleUrl){
        $output = $StatusMessages->parseFile($db_conn, $singleUrl);
    }

}
catch(Exception $e){
    die($e->getMessage());
}

*/
?>