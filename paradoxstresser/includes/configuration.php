<?php
ob_start();
require_once 'db.php';
require_once 'init.php';
if (!$user -> LoggedIn())
{
    header('Location: login');
	die();
}
  if (isset($_GET['token']) && $_GET['token'] != $_SESSION['token'])
{
    header('Location: logout');
  die();
}
setlocale(LC_TIME, 'fr_FR.UTF-8');

$plansql = $odb -> prepare("SELECT `users`.*,`plans`.`name`, `plans`.`mbt`, `plans`.`nbr`, `plans`.`code`, `plans`.`api` FROM `users`, `plans` WHERE `plans`.`ID` = `users`.`membership` AND `users`.`ID` = :id LIMIT 1");
$plansql -> execute(array(":id" => $_SESSION['ID']));
$userInfo = $plansql -> fetch(PDO::FETCH_ASSOC);

$SQLGetInfo = $odb -> prepare("SELECT * FROM `users` WHERE `ID` = :id");
$SQLGetInfo -> execute(array(':id' => $_SESSION['ID']));
$userInf = $SQLGetInfo -> fetch(PDO::FETCH_ASSOC);
$user -> hasMembership($odb);
$stats -> ServeursClients($odb);
$keykey = ")yWyu2hnvuWA|yH";
    $apikey = "";
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