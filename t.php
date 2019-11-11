<?php
//phpinfo();
//test PHP for Lambda
$host = 'rockstardb.cin9ds8s68f4.eu-central-1.rds.amazonaws.com';
$db = 'rockstar';
$username = "rockstardbmaster";
$password = "RockStarDB2017";
/*
$dsn = "mysql:host=$host;dbname=$db";
$conn = new PDO($dsn, $username, $password); //echo 'wwwww';
if($conn)
{
    echo "Connected to the <strong>$db</strong> database successfully!";
}
else {
    echo 'wewew';
}*/

$link = mysqli_connect($host, $username, $password);
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
echo '123456123';
?>