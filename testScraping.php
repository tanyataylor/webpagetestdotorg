<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tatiana
 * Date: 7/20/12
 * Time: 2:01 PM
 * To change this template use File | Settings | File Templates.
 */
include("simple_html_dom.php");

$html = file_get_dom('http://www.google.com/');

foreach($html->find('a') as $element)
    echo $element->href;
