<?php
/*
Developed by : Akn via Zote Innovation
Date : 26-Oct-2020
Last Modify Date : 26-Oct-2020
*/
$mod = getenv('MODE');
$allow = getenv($mod.'_AccessControlAllowOrigin');
$cors = 'Access-Control-Allow-Origin: '.($allow?$allow:"*");  
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // The request is using the POST method
    header("HTTP/1.1 200 OK");

    header($cors);
    header('Access-Control-Allow-Methods: POST, PUT, GET, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type,  Authorization');
    return;

}
//header("HTTP/1.1 200 OK");

header($cors);
header('Access-Control-Allow-Methods: POST, PUT, GET, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type,  Authorization');
            
include '../../core/app.php'
    
?>