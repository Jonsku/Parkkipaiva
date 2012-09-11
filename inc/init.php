<?php
//This line must be the first of the script
$_SERVER['DOCUMENT_ROOT'] = $_SERVER['DOCUMENT_ROOT']."/";
//The 2 lines below are required to be able to keep track og logged in users
ini_set("session.save_path",$_SERVER['DOCUMENT_ROOT']."session");
session_start();
//parse config
$config = parse_ini_file($_SERVER['DOCUMENT_ROOT']."site.ini", true);
//redirect
if(preg_match("/^".$config['server']['server_name']."$/i", $_SERVER["SERVER_NAME"]) == 0){
   header( 'Location: http://'.$config['server']['server_name'].$_SERVER["REQUEST_URI"] ) ;
}
?>

