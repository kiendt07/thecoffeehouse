<?php
  $host 		= " host=ec2-54-225-72-148.compute-1.amazonaws.com ";
 $port 		= "port=5432 ";
 $database 	= "dbname=dmm21h6vgpnuo ";
$username 	= "user=rgsyjaksdrabim ";
$password 	= "password=JrI60M9r-gKk2xcgNS7QtJempJ ";
$option = "sslmode=require ";

$url = $host + $database + $username + $port + $option + $password;
var_dump($url);
$DBC = pg_connect($url);
?>
