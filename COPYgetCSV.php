
<?php

/*
 * Author: Tatiana Taylor
 */
//using GET
/* Uncomment that instead of having plain csv file - this script will process the csv over and over
$data = file_get_contents('http://www.webpagetest.org/runtest.php?url=http://google.com&k=28acbc72b51e4aaca585d14e8148aa12&f=xml');

$xml = simplexml_load_string($data);
//var_dump($xml);


//var_dump((string)$xml->data->detailCSV);
//var_dump($csvFile);
//$x = file_get_contents("http://www.webpagetest.org/result/120718_B6_T0P/requests.csv");
//var_dump($x);
Request handler keep it
$statusCode = 100;
while($statusCode < 199){
    sleep(15);
    $contentsXML = file_get_contents($xml->data->xmlUrl);
    //var_dump($contentsXML);
    $xmlOutput = simplexml_load_string($contentsXML);
    //var_dump($xmlOutput);
    $statusCode = (int) $xmlOutput->statusCode;
    //var_dump($statusCode);

}
$csvFile = file_get_contents((string)$xml->data->detailCSV);
*/

$csvFile = trim(file_get_contents("test.csv"));
//var_dump($csvFile);
$row = explode("\n", $csvFile);
///var_dump($row);
$col = array();
foreach($row as $ColVal){
    $col[] = explode(",", $ColVal);
    //var_dump($col);


}

//CREATE TABLE
$tb_name = "raw_object_data";
$template = "CREATE TABLE IF NOT EXISTS  ". $tb_name . "(";

$sql = 'insert into '.$table_name;
var_dump($col[0]);
//var_dump($col[1]);



/*
public function insert_values($array_to_insert, $link){
    foreach($array_to_insert as $insert_line){
        echo $insert_line;
        $success = mysql_query($insert_line, $link);
        var_dump($success);
    }
}
*/

//0 -head
//1 ... -val





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


