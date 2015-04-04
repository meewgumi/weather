<!DOCTYPE html>
   <html>
    <head>
        <title>Do I need a sweater?</title>
         <meta name="description" content="Find out if you need a sweater without going outside." />
         <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
<body>
<div class="forecast">
<?php
date_default_timezone_set('America/Los_Angeles');

require_once 'lib/Forecast.php';

$question = "Do I need a sweater?";

/**
 * Get GPS coordinates from location string using the Google Maps API
 *
 * @param string $addr Address, ZIP code, etc.
 *
 * @return array latitude and longitude
 */

function geoLocate($addr)
{
  $geoapi = "http://maps.googleapis.com/maps/api/geocode/json";
  $params = 'address='.str_replace(" ", "+", $addr).'&sensor=false';
  $response = file_get_contents("$geoapi?$params");
  $json = json_decode($response);
  return array(
    $json->results[0]->geometry->location->lat,
    $json->results[0]->geometry->location->lng,
    $json->results[0]->address_components[1]->long_name,
    $json->results[0]->address_components[3]->short_name
  );
}

list($latitude, $longitude, $city, $state) = geoLocate('94107');


// Make request to the API for the current forecast
$forecast  = new Forecast('2b38343bfe06085955dff3a788734678');
$response = $forecast->getData($latitude, $longitude);
$currently = $response->getCurrently();
$time = date("h:i A", $currently->getTime());
$temp = number_format($currently->getTemperature(), 0);
echo "<div class=\"forecast__location\">$city, $state</div>";
echo "<div class=\"forecast__question\">$question</div>";

echo "<div class=\"forecast--answer\">";

if ($temp >= 55) {
    echo "NO";
} else {
    echo "YES";
}

echo "</div>";

?>
</div>
</body>
</html>