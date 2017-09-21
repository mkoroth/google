<?php
function convexHull($points)
{
	
	$cross = function($o, $a, $b) {
		return ($a[0] - $o[0]) * ($b[1] - $o[1]) - ($a[1] - $o[1]) * ($b[0] - $o[0]);
	};
	$pointCount = count($points);
	sort($points);
	if ($pointCount > 1) {
		$n = $pointCount;
		$k = 0;
		$h = array();

		
		for ($i = 0; $i < $n; ++$i) {
			while ($k >= 2 && $cross($h[$k - 2], $h[$k - 1], $points[$i]) <= 0)
				$k--;
			$h[$k++] = $points[$i];
		}

				for ($i = $n - 2, $t = $k + 1; $i >= 0; $i--) {
			while ($k >= $t && $cross($h[$k - 2], $h[$k - 1], $points[$i]) <= 0)
				$k--;
			$h[$k++] = $points[$i];
		}
			if ($k > 1) {
			
			$h = array_splice($h, 0, $k); 
		}
		return $h;
	}
	else if ($pointCount <= 1)
	{
		return $points;
	}
	else
	{
		return null;
	}
}

$connectionInfo = array( "Database" => "GOOGLE", "UID" => "hmsservice", "PWD" => "MFD4You!");
$conn = sqlsrv_connect("72.143.59.108,1494", $connectionInfo); 
$internal_points = array();                                             
if( $conn !== false ) 
{
	
	$store_id = $_GET["store_id"];
	if (is_null($store_id)) {
		$store_id = '936'; 
	}
	
		$sql = "SELECT LAT, LONG FROM GOOGLE.dbo.STORE_BOUNDARY WHERE STORE = ?";
	$stmt = sqlsrv_query( $conn, $sql, array($store_id) );
	if( $stmt === false) {
		die( print_r( sqlsrv_errors(), true) );
	}  
	else {
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
		    
			$xy = array($row["LONG"], $row["LAT"]);
			
			array_push($internal_points, $xy);
		}
		sqlsrv_free_stmt( $stmt);
	}
		$sql = "SELECT TOP 1 LAT, LONG FROM GOOGLE.dbo.STORES WHERE STORE = ?";
	$stmt = sqlsrv_query( $conn, $sql, array($store_id) );
	if ( $stmt === false) {
		die (print_r( sqlsrv_errors(), true ) );
	} else {
		$row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC );
	$marker_coordinates = array( "lat" => floatval($row["LAT"]), "lng" => floatval($row["LONG"]) );
	}
}                                                                                                                                                  

$boundary_points = convexHull($internal_points);
$points_javascript_var = 'var points = [';
foreach($internal_points as $points)
{
     
	$points_javascript_var .= '[' . $points[0] . ", " . $points[1] . '],';
} 

$points_javascript_var = rtrim($points_javascript_var, ',');
$points_javascript_var .= '];';

?>

<html>
	<head>
		<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCBeIvJweS4ZzyJRvBA7kZnryBH-SZzbFw&callback=initMap">
    </script>

	<head>
	<body>
		<div id="map" style="width: 90%; height: 80%; margin: 10% auto;"></div>

		<script>
			<?php echo $points_javascript_var ?>
	          markerPoint = <?php echo json_encode($marker_coordinates); ?>;
		   
			function initMap() {
			   
				var mapBounds = new google.maps.LatLngBounds();
								var averageLat = 0;
				var averageLon = 0;
				var polygonPoints = []; 
				for (var i = 0; i < points.length; i++) {
					averageLon += points[i][0]; 
					averageLat += points[i][1]; 
					var latLng = new google.maps.LatLng(points[i][1], points[i][0]);
					mapBounds.extend(latLng);
					polygonPoints.push({lat: points[i][1], lng: points[i][0]});
				}
				
				polygonPoints.push({lat: points[0][1], lng: points[0][0]}); 
				averageLat = averageLat / polygonPoints.length; 
				averageLon = averageLon / polygonPoints.length; 
				
						var map = new google.maps.Map(document.getElementById('map'), {
					zoom: 8,
					center: { lat: averageLat, lng: averageLon }
				});
				
            var polygonOutline = new google.maps.Polygon({
					paths: polygonPoints,
					strokeColor: '#FF0000', 
					strokeOpacity: 0.8, 
					strokeWeight: 3, 
					fillColor: '#FF0000', 
					fillOpacity: 0 
				});
				
	         var marker = new google.maps.Marker({ position: markerPoint, map: map });
	         polygonOutline.setMap(map);
				
				map.fitBounds(mapBounds);
			}
		</script>
	</body>
</html>