<?php
require("PV_distribution_dbinfo.php");
require("PVinfo.php");
// Start XML file, create parent node
$dom = new DOMDocument("1.0");
$node = $dom->createElement("markers");
$parnode = $dom->appendChild($node);



if ($conn != "") { 
// --------- Get data from table ---------------
$mssql_query = "SELECT * FROM $dbtable where project = 'PV701' and store = '$Store'and A_Selected = 'Y'";

//$result = $conn->query($mssql_query);
//if (!$result) {
//  die('Invalid query: ' . mssql_error());
//}



header("Content-type: text/xml");

// Iterate through the rows, adding XML nodes for each


foreach ($conn->query($mssql_query) as $row){
  // Add to XML document node
  $node = $dom->createElement("marker");
  $newnode = $parnode->appendChild($node);
  $newnode->setAttribute("CLIENT_CODE",$row['CLIENT_CODE']);
  $newnode->setAttribute("PROJECT",$row['PROJECT']);
  $newnode->setAttribute("CITY",$row['CITY']);
  $newnode->setAttribute("STORE",$row['STORE']);
  $newnode->setAttribute("ROUTE", $row['ROUTE']);
  $newnode->setAttribute("LAT", $row['LAT']);
  $newnode->setAttribute("LONG", $row['LONG']);
  $newnode->setAttribute("MFDCODE", $row['MFDCODE']);
}
}
echo $dom->saveXML();

?>