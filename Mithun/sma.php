<?php
require("sma_dbinfo.php");
require("PVinfo.php");
// Start XML file, create parent node
$dom = new DOMDocument("1.0");
$node = $dom->createElement("marker");
$parnode = $dom->appendChild($node);



if ($conn != "") { 
// --------- Get data from table ---------------
$mssql_query = "SELECT * FROM $dbtable where project = 'PV745' and store = '$Store' order by BOUND_ID";

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
  $newnode->setAttribute("LAT", $row['LAT']);
  $newnode->setAttribute("LONG", $row['LONG']);
}
}
echo $dom->saveXML();

?>