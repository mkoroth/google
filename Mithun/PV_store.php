<?php
require("PV_store_dbinfo.php");
require("PVinfo.php");
// Start XML file, create parent node
$dom = new DOMDocument("1.0");
$node = $dom->createElement("markers");
$parnode = $dom->appendChild($node);



if ($conn != "") { 
// --------- Get data from table ---------------
$mssql_query = "SELECT * FROM $dbtable where CLIENT_CODE = 'PV' and STORE = '$Store'";


header("Content-type: text/xml");

// Iterate through the rows, adding XML nodes for each


foreach ($conn->query($mssql_query) as $row){
  // Add to XML document node
  $node = $dom->createElement("marker");
  $newnode = $parnode->appendChild($node);
  $newnode->setAttribute("CLIENT_CODE",$row['CLIENT_CODE']);
  $newnode->setAttribute("STORE",$row['STORE']);
  $newnode->setAttribute("STORE_NAME",$row['STORE_NAME']);
  $newnode->setAttribute("ADDRESS1", $row['ADDRESS1']);
  $newnode->setAttribute("LAT", $row['LAT']);
  $newnode->setAttribute("LONG", $row['LONG']);
  $newnode->setAttribute("CITY", $row['CITY']);
}
}
echo $dom->saveXML();

?>