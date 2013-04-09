<pre>
<?php

include("simple_html_dom.php");
/*
 * Author: Tatiana Taylor
 */
//using GET
$data = file_get_contents('http://www.webpagetest.org/runtest.php?url=http://google.com&k=28acbc72b51e4aaca585d14e8148aa12&f=xml');

$xml = simplexml_load_string($data);
var_dump($xml);


//echo "FIRST--------------------------------";

//var_dump((string)$xml->data->detailCSV);
//var_dump($csvFile);
//$x = file_get_contents("http://www.webpagetest.org/result/120718_B6_T0P/requests.csv");
//var_dump($x);

$statusCode = 100;
while($statusCode < 199){
    sleep(15);
    $contentsXML = file_get_contents($xml->data->xmlUrl);
    //var_dump($contentsXML);
    //echo "SECOND-----------------------------------------";
    $xmlOutput = simplexml_load_string($contentsXML);
    //var_dump($xmlOutput);

    //echo "THIRD-----------------------------------------";
    $statusCode = (int) $xmlOutput->statusCode;
    //var_dump($statusCode);

    //echo"----------------------------MESSAGE PAGE VIEW-----------------------------------";
    //$statusMessage = $xmlOutput->data->run->firstView->pages;
    //$statusMessage = $xmlOutput->data->run;
    //$statusMessage = $xmlOutput->data->run->firstView->pages->screenShot;
    $statusMessage = $xmlOutput->data->run->firstView->pages->screenShot;

    var_dump($statusMessage);

    /* $html = new simple_html_dom();
    // Load an html file

    $html->load_file($statusMessage);
    $element = $html->find("Status Messages");
    echo $html->save();
    var_dump($html);
*/

}
//echo "FORTH-------------------------------------------------";
$csvFile = file_get_contents((string)$xml->data->userUrl);
//var_dump($csvFile);