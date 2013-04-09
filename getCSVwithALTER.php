<pre>
<?php


    /*
    * Author: Tatiana Taylor
    */

    error_reporting(E_ALL | E_STRICT);
    ini_set("display_errors", 1);

    require_once("MySqlConnect.php");
// Uncomment that instead of having plain csv file - this script will process the csv over and ove

    /*
    $data = file_get_contents('http://www.webpagetest.org/runtest.php?url=http://bikesomewhere.com//&k=28acbc72b51e4aaca585d14e8148aa12&f=xml');
    $xml = simplexml_load_string($data);
    var_dump($xml);

    //var_dump((string)$xml->data->detailCSV);
    //var_dump($csvFile);
    //$x = file_get_contents("http://www.webpagetest.org/result/120718_B6_T0P/requests.csv");
    //var_dump($x);

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

    $csvFile = file_get_contents((string)$xml->data->detailCSV);
    var_dump($csvFile);
    */

    class getCSV{

        //   public $tb_name = "raw_object_data_GOOGLE";
        // protected $_csvFile = array();

        public function getFile(){
            $csvgetFile = "testCSVOutput.csv";
            $created = false;
            if (file_exists($csvgetFile)){
                try{
                    $csvFile = trim(file_get_contents("testCSVOutputGOOGLE.csv"));
                    $created = true;
                }
                catch (Exception $e){
                }
            }
            return $csvFile;
        }

        public function parseCSV($db_conn = null){
            if($db_conn === null){
                throw new Exception("database link not given", 0);
                return array();
            }
            $var = $this->getFile();

            $createdTable = $this->createTable($var);
            $insertCreateTable = $this->insertCreateTable($createdTable, $db_conn);
            // var_dump($insertCreateTable);

            $insertedValues = $this->insertData($var);
            $insertInDB = $this->insertValuesInDB($insertedValues, $db_conn);
            //  var_dump($insertInDB);

            $alteredStatement = $this->alterTable();
            $alteredTable = $this->insertAlter($alteredStatement, $db_conn);
            //var_dump($alteredTable);

            $eventSequence = $this->insertLimit();
            $selected = $this->insertSelect($eventSequence, $db_conn);
            //var_dump($selected);

        }
        public function createTable($csvFile){
            $tb_name = "raw_object_data_GOOGLE";
            $row = explode("\n", $csvFile);
            $template = "CREATE TABLE ". $tb_name . "(";
            $col_titles = array_shift($row);
            $col_titles = explode(",", $col_titles);
            foreach($col_titles as $each_title)
            {
                $each_title_trim = trim($each_title,' "" ');
                $new_title = str_replace(" ", "", $each_title_trim);
                $ms_removed = str_replace("(ms)", "ms", $new_title);
                $out_removed = str_replace("(out)", "Out", $ms_removed);
                $dash_removed = str_replace("-", "", $out_removed);
                $sec_removed = str_replace("(sec)", "Sec", $dash_removed);
                $template .= " $sec_removed CHAR(255),";
            }
            $template = substr($template,0, -1);
            $template .= " );";
            var_dump($template);
            return $template;
        }

        public function insertCreateTable($createdTable, $db_conn){
            $success = $this->runSQL($createdTable, $db_conn);
            return $success;
        }

        public function runSQL($query, $db_conn){
            $runQuery = mysql_query($query, $db_conn);
            if ($runQuery === false){
                echo mysql_error();
            }
            //var_dump($query);
            return $runQuery;

        }

        public function insertData($csvFile){
            $tb_name = "raw_object_data_GOOGLE";
            $row = explode("\n", $csvFile);
            $insert_template = "INSERT INTO ". $tb_name. " VALUES ";
            $col_titles = array_shift($row);
            $values = $row;

            foreach($values as $line){
                $insert_template .= "(".$line."),";
                $insert_template = str_replace(',"",)', ")", $insert_template);
            }
            $insert_template = substr($insert_template,0, -1);
            $insert_template .= ";" ;
            //  var_dump($insert_template);
            return($insert_template);
        }

        public function insertValuesInDB($insertedValues, $db_conn){
            $success = $this->runSQL($insertedValues, $db_conn);
            //   mysql_close($db_conn);
            return $success;
        }

        public function alterTable(){
            $tb_name = "raw_object_data_GOOGLE";
            $alterStatement = "ALTER TABLE " . $tb_name . " ADD id INT NOT NULL AUTO_INCREMENT KEY FIRST;";
            return $alterStatement;
        }

        public function insertAlter($alteredStatement, $db_conn){
            $success = $this->runSQL($alteredStatement, $db_conn);
            return $success;
        }

        public function insertLimit(){
            $tb_name = "raw_object_data_GOOGLE";
            $eventSequence = "SELECT EventGUID, SequenceNumber from $tb_name;";
            return $eventSequence;
        }

        public function insertSelect($eventSequence, $db_conn){
            $success = $this->runSQL($eventSequence, $db_conn);
            return $success;
        }

    }

    try{
        $wrap = new MySqlConnect();
        $db_conn = $wrap->_init();
        $getCSV = new getCSV;
        $output = $getCSV->parseCSV($db_conn);

    }
    catch (Exception $e){
        die($e->getMessage());
    }

    /* Do not need this anymore just to see how while works
    $columns = array();
    $columns[] = $parsed;  // make a duplicate of a larger array to keep it
    //var_dump($columns);
    $i = 0;
    while ($i <= 70){
        echo $columns[0][$i] . "\n";
        $i++;
    }
    */

    ?>
