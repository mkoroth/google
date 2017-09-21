<?php 
// MS SQL server 2005 using PHP and php_pdo_odbc.dll driver
// DB configuration
$serverName = "72.143.59.108\GOOGLE, 1494";     // Your database server
$dbuser = "hmsservice";      // Your db username
$dbpass = "MFD4You!";      // Your db password
$dbname = "GOOGLE";      // Your database name
$dbtable = "STORE_BOUNDARY";  // the table we're using
//
$conn = new PDO("odbc:Driver={SQL Server Native Client 10.0};Server=$serverName;Database=$dbname;", $dbuser, $dbpass);

?>
