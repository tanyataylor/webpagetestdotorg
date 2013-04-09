<pre>
<?php

/*
 * Author: Tatiana Taylor
 */
//using GET
$data = file_get_contents('http://www.webpagetest.org/runtest.php?url=http://google.com&k=28acbc72b51e4aaca585d14e8148aa12&f=xml');

$xml = simplexml_load_string($data);
var_dump($xml);
var_dump($xml->data->xmlUrl);

echo "--------------------------------";

$statusCode = 100;
while($statusCode < 199){
    sleep(15);
    $contentsXML = file_get_contents($xml->data->xmlUrl);
    var_dump($contentsXML);
    echo "-----------------------------------------";
    $xmlOutput = simplexml_load_string($contentsXML);
    var_dump($xmlOutput);
    echo "-----------------------------------------";
    $statusCode = (int) $xmlOutput->statusCode;
    var_dump($statusCode);

}
