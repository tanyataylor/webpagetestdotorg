<pre>
<?php

/*
 * Author: Tatiana Taylor
 */

error_reporting(E_ALL | E_STRICT);
ini_set("display_errors", 1);

require_once("MySqlConnect.php");

class getPageData{
    protected $headers;

    public function getFile($singleUrl){
        try{
            var_dump($singleUrl);
            $data = file_get_contents('http://www.webpagetest.org/runtest.php?url='.urlencode($singleUrl).'&k=28acbc72b51e4aaca585d14e8148aa12&f=xml');
            $xml = simplexml_load_string($data); echo"<br />";
            var_dump($xml);

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
            $csvFile = trim(file_get_contents((string)$xml->data->summaryCSV));
            //var_dump($csvFile);
            return $csvFile;
            }
        catch(Exception $e){}
        }

    public function parseCSV($db_conn = null, $singleUrl){
        if($db_conn === null){
            throw new Exception("database link not given", 0);
            return array();
            }
        $var = $this->getFile($singleUrl);
        $createdTable = $this->createTable($var);
        $insertCreateTable = $this->insertCreateTable($createdTable, $db_conn);

        $insertedValues = $this->insertData($var, $this->headers);
        $insertInDB = $this->insertValuesInDB($insertedValues, $db_conn);
        //var_dump($insertInDB);
        }

    public function createTable($csvFile){
        $tb_name = "raw_page_data";
        $row = explode("\n",$csvFile);
        $template = "CREATE TABLE IF NOT EXISTS ". $tb_name . "( id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, ";
        $col_titles = array_shift($row);
        $col_titles = explode(",", $col_titles);

        foreach($col_titles as $each_title){
            $each_title_trim = trim($each_title,' "" ');
            $new_title = str_replace(" ", "", $each_title_trim);
            $ms_removed = str_replace("(ms)", "ms", $new_title);
            $out_removed = str_replace("(out)", "Out", $ms_removed);
            $dash_removed = str_replace("-", "", $out_removed);
            $doc_removed = str_replace("(Doc)", "Doc", $dash_removed);
            $template .= " $doc_removed TEXT,";
            }
        $template = substr($template,0, -1);
        $template .= ");";
        $template= str_replace(array("\n","\t","\r")," ", $template);
        $template = str_replace('Run TEXT, SpeedIndex" ', 'Run TEXT, SpeedIndex', $template);
        var_dump($template);
        $this->headers = $template;
        return $template;
        }

    public function insertCreateTable($createdTable, $db_conn){
        $success = $this->runSQL($createdTable, $db_conn);
        //var_dump($success);
        return $success;
        }

    public function runSQL($query, $db_conn){
        $runQuery = mysql_query($query, $db_conn);
        if ($runQuery === false){
            echo mysql_error();
            }
        return $runQuery;
        }

    public function insertData($csvFile, $headers){
        $columns = $headers;
        $columns = str_replace('TEXT', "", $columns);
        $columns = strstr($columns, '(');
        $columns = str_replace(';', "", $columns);
        $columns = str_replace(" id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, ", "", $columns);
        $tb_name = "raw_page_data";
        $row = explode("\n", $csvFile);
        $insert_template = "INSERT INTO ". $tb_name. $columns ." VALUES ";
        $col_titles = array_shift($row);
        $values = $row;
        foreach($values as $line){
            $insert_template .= "(".$line."),";
            //$insert_template = str_replace(',"")', ")", $insert_template);
            // $eventSequence = mysql_query("SELECT EventGUID, SequenceNumber from $tb_name WHERE EventGUID AND SequenceNumber =  ");
        }
        $insert_temp=str_replace(array("\n","\t","\r")," ", $insert_template);
        $insert_temp = str_replace('"",)' ,  '"")' , $insert_temp);
        $insert_temp = str_replace('"", )' ,  '"")' , $insert_temp);
        $insert_temp = substr($insert_temp,0, -1);
        $insert_temp .= ";" ;
        var_dump($insert_temp);
        return($insert_temp);
    }

    public function insertValuesInDB($insertedValues, $db_conn){
        $success = $this->runSQL($insertedValues, $db_conn);
        return $success;
    }

    }
/*
try{
    $wrap = new MySqlConnect();
    $db_conn = $wrap->_init();
    $getPageData = new getPageData;

    $url = file_get_contents("StaticURLs.txt");
    $url = explode("\n", $url);
    foreach($url as $singleUrl){
        $outputPage = $getPageData->parseCSV($db_conn, $singleUrl);
    }



}
catch (Exception $e){
    die($e->getMessage());
}
*/

?>



