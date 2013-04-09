
<?php


/*
 * Author: Tatiana Taylor
 */
// Uncomment that instead of having plain csv file - this script will process the csv over and ove


$data = file_get_contents('http://www.webpagetest.org/runtest.php?url=http://google.com//&k=28acbc72b51e4aaca585d14e8148aa12&f=xml');
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



//$csvFile = trim(file_get_contents("testCSVOutput.csv"));
//var_dump($csvFile);

$row = explode("\n", $csvFile);
//var_dump($row);
$col = array();
$tb_name = "raw_object_data";
$template = "CREATE TABLE ". $tb_name . "(";
$col_titles = array_shift($row);
$col_titles = explode(",", $col_titles);
//var_dump($row);
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
//var_dump($template);

$insert_template = "INSERT INTO ". $tb_name. " VALUES ";
$values = ($row);


foreach($values as $line){
  //var_dump($line);
    $insert_template .= "(".$line."),";
    $insert_template = str_replace(',"",)', ")", $insert_template);

}

$insert_template = substr($insert_template,0, -1);
$insert_template .= ";" ;
//var_dump($insert_template);


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
