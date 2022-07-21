<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'stress');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');

$odb = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USERNAME, DB_PASSWORD);
$odb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $apikey = "8NKHIcDJ0zEdJy9lU97OY3ZhxW1UGaGvqKtDped7m4M";
    $url = "https://www.blockonomics.co/api/";
    
    $options = array( 
        'http' => array(
            'header'  => 'Authorization: Bearer '.$apikey,
            'method'  => 'POST',
            'content' => '',
            'ignore_errors' => true
        )   
    );
?>