<?php
require '../includes/db.php';
require '../includes/init.php';
    $getIp = file_get_contents("https://api.ipify.org");
    $ipcountry = @json_decode(@file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $getIp))->{'geoplugin_region'};
    $username = $_POST['username'];
    $password = $_POST['password']; 
    $errors = [];          
if (!isset($_POST['tos']))
    {
    $errors = 'You must read and accept our TOS.';
    }
if (empty($username) || empty($password))
    {
    $errors = 'Please fill all.';   
    }
if (!ctype_alnum($username) && !filter_var($username, FILTER_VALIDATE_EMAIL))
    {
    $errors = 'Please insert a correct username.';   
    }
    $SQL = $odb -> prepare("SELECT `status` FROM `users` WHERE `username` = :username AND `password` = :password");
    $SQL -> execute(array(':username' => $username, ':password' => SHA1($password)));
    $result = $SQL -> fetchColumn(0);
if ($result == 1)
    {
    $ban = $odb -> query("SELECT `motifban` FROM `users` WHERE `username` = '$username'") -> fetchColumn(0);
    $errors = 'You are banned. Reason: '.htmlspecialchars($ban). '.'; 
    }
    $SQLCheckLogin = $odb -> prepare("SELECT COUNT(*) FROM `users` WHERE `username` = :username AND `password` = :password");
    $SQLCheckLogin -> execute(array(':username' => $username, ':password' => SHA1($password)));
    $countLogin = $SQLCheckLogin -> fetchColumn(0);
if ($countLogin == 0)
    {
    $SQL = $odb -> prepare('INSERT INTO `loginlogs` VALUES(NULL, :username, :status, :ip, UNIX_TIMESTAMP(), :country)');
    $SQL -> execute(array(':status' => 'Failed', ':ip' => substr_replace($getIp, "*****", -6), ':username' => htmlentities($username), ':country' => $ipcountry));
    $t = $odb -> prepare('SELECT COUNT(*) FROM `loginlogs` WHERE status = ? AND ip = ? AND `date` > ?');
    $t -> execute(array('Failed', substr_replace($getIp, "*****", -6), time() - 86400));
    $checking = $t->fetchColumn();
    $errors = 'Login Failed '.$checking.'/5.';
    }
if (!empty($errors)){
    session_start();
    $_SESSION['errors'] = $errors;
    header('Location: ../login');
    }else{
    $SQLGetInfo = $odb -> prepare("SELECT `username`, `ID` FROM `users` WHERE `username` = :username AND `password` = :password");
    $SQLGetInfo -> execute(array(':username' => $username, ':password' => SHA1($password)));
while ($userInfo = $SQLGetInfo -> fetch(PDO::FETCH_ASSOC))
    {
    $_SESSION['username'] = $userInfo['username'];
    $_SESSION['ID'] = $userInfo['ID'];
    $_SESSION['token'] = bin2hex(md5(random_bytes(66)));
    $SQL = $odb -> prepare('INSERT INTO `loginlogs` VALUES(NULL, :username, :status, :ip, UNIX_TIMESTAMP(), :country)');
    $SQL -> execute(array(':status' => 'Successful', ':ip' => substr_replace($getIp, "*****", -6), ':username' => $_SESSION['username'], ':country' => $ipcountry));
    $update = $odb->prepare("UPDATE users SET lastlogin = ? WHERE ID = ?");
    $update->execute(array(time(), $_SESSION['ID']));
    $update = $odb->prepare("UPDATE users SET lastact = ? WHERE ID = ?");
    $update->execute(array(time(), $_SESSION['ID']));
    $update = $odb->prepare("UPDATE users SET lastip = ? WHERE ID = ?");
    $update->execute(array(substr_replace($getIp, "*****", -6), $_SESSION['ID']));
    $update = $odb->prepare("UPDATE users SET ah = ? WHERE ID = ? AND ah = ?");
    $update->execute(array('1', $_SESSION['ID'], '2'));
    session_start();
    $_SESSION['actived'] = 'You are now logged';
    header('Location: ../home');
    }
    } 
 ?>