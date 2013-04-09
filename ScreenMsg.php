<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tatiana
 * Date: 8/3/12
 * Time: 11:10 AM
 * To change this template use File | Settings | File Templates.
 */

//http://www.webpagetest.org/result/120803_M8_M2S/1/screen_shot/
/*
$info = file_get_contents('http://www.webpagetest.org/runtest.php?url=http://yahoo.com//&k=28acbc72b51e4aaca585d14e8148aa12&f=xml');
$xml= simplexml_load_string($info);
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
$sceenMessage = trim(file_get_contents((string)$xml->data->xmlUrl->response));
var_dump($sceenMessage);

*/






$data = file_get_contents('http://www.webpagetest.org/result/120803_M8_M2S/1/screen_shot/');
//$regex = "/(Status Messages.*)/";

$regex = "#Status Messages.*?</table>#is";
$extracted_table = preg_match($regex,$data,$match);
var_dump($match);

$match = implode(",", $match);

$table_name = "#Status Messages.*?#is";
$extracted_table = preg_match($table_name,$data,$table);
var_dump($table);


$col_time = "#(Time).*?</th>#is";
$extracted_time = preg_match($col_time, $match, $time);
var_dump($time);

$col_message = "#Message.*?#is";
$extracted_time = preg_match($col_message, $match, $mess);
var_dump($mess);



$time = "#time.*?</td>#s";
$extracted_time = preg_match_all($time, $match, $m_time);
var_dump($m_time);

$messages = "#<td>.*?</td>#is";
$extracted_message = preg_match_all($messages, $match, $m_message);
var_dump($m_message);

