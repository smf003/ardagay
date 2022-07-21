<?php
if (!$user-> notBanned($odb))
{
	header('Location: logout');
}
if (!$user-> Question($odb))
{
	header('Location: google');
}
?>