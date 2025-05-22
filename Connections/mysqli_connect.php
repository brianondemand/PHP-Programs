<?php
// MySQLi

$servername ="localhost";  
$username ="root";  
$password ="dbadmin";  
  
// Create connection  
$connect = mysqli_connect($servername, $username, $password);  
  
// Check connection  
if (!$connect) {  
 die("Connection failed: ". mysqli_connect_error());  
}  
echo "Connected successfully"; 


