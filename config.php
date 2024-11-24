<?php 
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASSWORD' ,'12345678');
define('DB_NAME', 'newbookstotre');

$conn=mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD) or die("Failed to connect to MySQL: " . mysql_error()); 
$db=mysqli_select_db($conn,DB_NAME) or die("Failed to connect to MySQL: " . mysql_error()); 
?>