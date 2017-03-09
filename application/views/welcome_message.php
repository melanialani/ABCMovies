<?php    $url = urlencode("http://maps.googleapis.com/maps/api/geocode/json?address=$adr&sensor=false"); ?>
<html>
<head>        
<title>Test File</title>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"> 
</script>
</head>
<body>
<?php    
	$adr = 'Sydney+NSW';
	echo $adr;
	$url = "http://maps.googleapis.com/maps/api/geocode/json?address=$adr&sensor=false";
	echo '<p>'.$url.'</p>';
	echo file_get_contents($url);
	print '<p>'.file_get_contents($url).'</p>';
	$jsonData   = file_get_contents($url);
	echo $jsonData;
?>
</body>
</html>