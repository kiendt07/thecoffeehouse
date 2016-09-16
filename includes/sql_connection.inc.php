<?php
  $host 		= " host=ec2-54-225-72-148.compute-1.amazonaws.com ";
 $port 		= "port=5432 ";
 $database 	= "dbname=dmm21h6vgpnuo ";
$username 	= "user=rgsyjaksdrabim ";
$password 	= "password=JrI60M9r-gKk2xcgNS7QtJempJ ";
$option = "sslmode=require ";

$url = "host=ec2-54-225-72-148.compute-1.amazonaws.com dbname=dmm21h6vgpnuo port=5432 user=rgsyjaksdrabim password=JrI60M9r-gKk2xcgNS7QtJempJ sslmode=require";
$DBC = pg_connect($url);
?>
