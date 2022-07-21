<?php
require '../includes/db.php';
require '../includes/init.php';
    $getIp = file_get_contents("https://api.ipify.org");
    $username = $_POST['username'];
    $password = $_POST['password'];
    $rpassword = $_POST['rpassword']; 
    $errors = [];             
if (!isset($_POST['tos']))
    {
    $errors = 'You must read and accept our TOS.';
    }
if (empty($_POST['captcha']))
    {
    $errors = 'Wrong Captcha. '.$_SESSION['securecode'].'.';
    }
    $secure = isset($_POST['captcha']) ? strtolower($_POST['captcha']) : '';
if ($secure != $_SESSION['securecode'])
    {
    $errors = 'Wrong Captcha. '.$_SESSION['securecode'].'.';
    }
    unset($_SESSION['securecode']);

if (empty($username) || empty($password) || empty($rpassword))
    {
    $errors = 'Please fill all.';   
    }
if (!ctype_alnum($username) || strlen($username) < 4 || strlen($username) > 15)
    {
    $errors = 'Username must be 4-15 characters length.';  
    } 
if (strlen($password) < 5)
    {
    $errors = 'Password must be 5-20 characters length.';
    }
if ($password != $rpassword)
    {
    $errors = 'Passwords must be same.';
    }
    $checkUsername = $odb -> prepare("SELECT COUNT(*) FROM `users` WHERE `username` = :username");
    $checkUsername -> execute(array(':username' => $username));
    $countUsername = $checkUsername -> fetchColumn(0);
if (!$countUsername == 0)
    {
    $errors = 'This username is already taken.';
    }
if  (!empty($errors)){
    session_start();
    $_SESSION['errors'] = $errors;
    header('Location: ../register');
    }else{
    $insertUser = $odb -> prepare("INSERT INTO `users` VALUES(NULL, :username, :password, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 0, 0, 0, 0, 1, 0, 0)");
    $insertUser -> execute(array(':username' => $username, ':password' => SHA1($password)));
                {
                
    $ipcountry = @json_decode(@file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $getIp))->{'geoplugin_region'};
    $SQL = $odb -> prepare('INSERT INTO `loginlogs` VALUES(NULL, :username, :status, :ip, UNIX_TIMESTAMP(), :country)');
    $SQL -> execute(array(':status' => 'Register', ':ip' => substr_replace($getIp5, "*****", -6), ':username' => $username, ':country' => $ipcountry));

    $message = 'register';
    $SQLinsert = $odb -> prepare("INSERT INTO `tickets` VALUES(NULL, :content, :status, :username, :view, UNIX_TIMESTAMP())");
    $SQLinsert -> execute(array(':content' => nl2br($message), ':status' => 'Waiting for admin response', ':view' => '', ':username' => $username));

    $id_data = $odb->lastInsertId();
    $messages = 'Welcome';
    $private = openssl_encrypt($messages, "AES-128-ECB" ,$keykey);
    $SQLinsert = $odb -> prepare("INSERT INTO `messages` VALUES(NULL, :ticketid, :content, :sender, :view, UNIX_TIMESTAMP())");
    $SQLinsert -> execute(array(':sender' => 'Automatic System', ':view' => '', ':content' => $private, ':ticketid' => $id_data));

    $SQLUpdate = $odb -> prepare("UPDATE `users` SET `message` = :message WHERE `username` = :username");
    $SQLUpdate -> execute(array(':message' => $id_data, ':username' => $username));
    session_start();
    $_SESSION['active'] = 'Account Create Successful';
    header('Location: ../login');

          
}

                }
?>