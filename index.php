<?php
if(isset($_POST['zipcode']) && is_numeric($_POST['zipcode'])){
    $zipcode = $_POST['zipcode'];
}else{
    $zipcode = '368150';
}
$result = file_get_contents('http://weather.yahooapis.com/forecastrss?w=' . $zipcode . '&u=c');
$xml = simplexml_load_string($result);
 
//echo htmlspecialchars($result, ENT_QUOTES, 'UTF-8');
 
$xml->registerXPathNamespace('yweather', 'http://xml.weather.yahoo.com/ns/rss/1.0');
$location = $xml->channel->xpath('yweather:location');
 
if(!empty($location)){
    foreach($xml->channel->item as $item){
        $current = $item->xpath('yweather:condition');
        $forecast = $item->xpath('yweather:forecast');
        $current = $current[0];
        $output = <<<END
            <h1 style="margin-bottom: 0">Clima de {$location[0]['city']}, {$location[0]['region']}</h1>
            <small>{$current['date']}</small>
            <h2>Current Conditions</h2>
            <p>
            <span style="font-size:72px; font-weight:bold;">{$current['temp']}&deg;C</span>
            <br/>
            <img src="http://l.yimg.com/a/i/us/we/52/{$current['code']}.gif" style="vertical-align: middle;"/>&nbsp;
            {$current['text']}
            </p>
            <h2>Pronostico</h2>
            {$forecast[0]['day']} - {$forecast[0]['text']}. High: {$forecast[0]['high']} Low: {$forecast[0]['low']}
            <br/>
            {$forecast[1]['day']} - {$forecast[1]['text']}. High: {$forecast[1]['high']} Low: {$forecast[1]['low']}
            </p>
END;
    }
}else{
    $output = '<h1>No hay resultados, intenta nuevamente mas tarde.</h1>';
}
?>
<html>
<head>
<title>Clima</title>
<style>
body {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 12px;
}
label {
    font-weight: bold;
}
</style>
</head>
<body>
<form method="POST" action="">
<label>Seleccione alguna ciudad:</label><br/>
<select name="zipcode">
	<option value="368150">Medellin</option>
    <option value="368148">Bogota</option>
    <option value="368149">Cali</option>
    <option value="368153">Cartagena</option>
    <option value="368164">Monteria</option>
    <option value="368167">Pasto</option>
    <option value="368154">Cucuta</option>
    <option value="368159">Barrancabermeja</option>
    <option value="368335">Leticia</option>
    
</select><br/>
<input type="submit" name="submit" value="Consultar Clima" />
</form>
<hr />
<?php echo $output; 
		
		
		if(($forecast[0]['text']=='Scattered Thunderstorms')||($forecast[0]['text']=='Thunderstorms')||($forecast[0]['text']=='Thunderstorms Early')||($forecast[0]['text']=='Isolated Thunderstorms')){
			echo "<h3>Hoy es tormenta, llevar abrigo y sombrilla, desconectar electrodomesticos</h3>";
		}else if(($forecast[0]['text']=='Partly Cloudy')||($forecast[0]['text']=='Cloudy')||($forecast[0]['text']=='Mostly Cloudy')){
			echo "Hoy sera nublado, para prevenir, llevar abrigo";
		}else if(($forecast[0]['text']=='Fair')){
			echo "Hoy sera despejado, se puede usar ropa fresca";
		}
		else if(($forecast[0]['text']=='Light Rain')){
			echo "Hoy llueve, llevar sombrilla y ropa abrigada";
		}

?>

</body>
</html>
