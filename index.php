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

/**
 * Show YES/NO output for form input
 *
 */

       if( $_GET["location"] || $_GET["temperature"] )
  {
     list($latitude, $longitude, $city, $state) = geoLocate($_GET["location"]);       
// Make request to the API for the current forecast
$forecast  = new Forecast('2b38343bfe06085955dff3a788734678');
$response = $forecast->getData($latitude, $longitude);
$currently = $response->getCurrently();
$time = date("h:i A", $currently->getTime());
$temp = number_format($currently->getTemperature(), 0);
echo "<div class=\"forecast__location\">$city, $state</div>";
echo "<div class=\"forecast__question\">$question</div>";

echo "<div class=\"forecast--answer\">";

if ($temp >= $_GET["temperature"]) {
    echo "NO";
} else {
    echo "YES";
}

echo "</div>";
     exit();
  }

?>
</div>
<div class="form">
  <form action="<?php $_PHP_SELF ?>" method="GET">
  <div class="form__prompt">Where are you?</div>
  <input type="text" name="location" placeholder="Address or zip code" />
  <div class="form__prompt">Sweater weather is below:</div>
  <input type="text" name="temperature" placeholder="55&#176; F" />
  <input type="submit" />
  </form>
  </div>
</body>
</html>