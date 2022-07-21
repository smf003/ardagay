<?php
ob_start();
session_start();
define('DIRECT', TRUE);
function getRealIpAddr()
{	
	if (!empty($_SERVER['HTTP_CF_CONNECTING_IP']))
	{
		$ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
	}
	else if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
	{
		$ip=$_SERVER['HTTP_CLIENT_IP'];
	}
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
	{
		$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	else
	{
		$ip=filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
	}
	return $ip;
}

function APIinit($adresse, $odb)
{
	$options = array('http' => array('user_agent' => 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36'));
    $context = stream_context_create($options); // Création d'un en-tête afin que cloudflare ne bloque pas votre requête.
    @file_get_contents($adresse, false, $context); // Envoi des données à l'API.
	
}
$_SERVER['REMOTE_ADDR'] = getRealIpAddr();
require 'functions.php';
$user = new user;
$stats = new stats;

$SQLGetInfo = $odb -> query("SELECT * FROM `informations`");
$info = $SQLGetInfo -> fetch(PDO::FETCH_ASSOC);
$url = $info['url'];
$urlapi = 'http://api.undisclosed.fr/';
?>