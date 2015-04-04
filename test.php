<?php

include ('Forecast/Forecast.php');

use Forecast\Forecast;

$forecast = new Forecast('2b38343bfe06085955dff3a788734678');

// Get the current forecast for a given latitude and longitude
// var_dump($forecast->get('37.8267','-122.423'));

// Get the forecast at a given time
// var_dump($forecast->get('37.8267','-122.423', '2015-04-03T12:00:00-0400'));

// Use some optional query parameters
var_dump($forecast->get(
    '37.8267',
    '-122.423',
    null,
    array(
        'units' => 'us',
        'exclude' => 'flags,minutely,currently,alerts,hourly'
        )
    )
);

//var_dump(json_decode($forecast));
//var_dump(json_decode($forecast, true));

?>