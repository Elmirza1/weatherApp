<?php
$apiKey = "a50f13c39da4498680053439241203";

$cityId = "";
$weatherData = null;
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['city'])) {
        $cityId = $_POST['city'];
        
        // Fetch weather data from API
        $url = "http://api.weatherapi.com/v1/current.json?key={$apiKey}&q=".urlencode($cityId)."&aqi=no";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($statusCode == 200) {
            $weatherData = json_decode($response);
        } else {
            $error = "Error: Failed to fetch weather data. Please try again later.";
        }
    } else {
        $error = "Error: City name is required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Forecast Weather using OpenWeatherMap with PHP</title>
<link rel="stylesheet" type="text/css" href="style.css">

</head>
<body>
    <div class="container">
    <div class="report-container">
        <h2><?php echo isset($weatherData) ? $weatherData->location->name . " Weather Status" : "Weather Report"; ?></h2>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" autocomplete="off">
            <div>
                <input type="text" required name="city" placeholder="Enter the city name..." value="<?php echo htmlspecialchars($cityId); ?>">
                <button type="submit">Get Weather</button>
            </div>
            <?php if ($error) { ?>
                <div class="error"><?php echo $error; ?></div>
            <?php } ?>
        </form>

        <?php if ($weatherData) { ?>
            <div class="time">
                <div>Local Time: <?php echo $weatherData->location->localtime; ?></div>
                <div><?php echo $weatherData->location->tz_id; ?></div>
                <div><?php echo ucwords($weatherData->current->condition->text); ?></div>
            </div>
            <div class="weather-forecast">
                <img src="<?php echo $weatherData->current->condition->icon; ?>" alt="Weather Icon">
                <div>Temperature: <?php echo $weatherData->current->temp_c; ?> &deg;C</div>
            </div>
            <div class="weather-forecast">
                <div>Gust: <?php echo $weatherData->current->gust_kph; ?> kph</div>
                <div>Visibility: <?php echo $weatherData->current->vis_km; ?></div>
                <div>Humidity: <?php echo $weatherData->current->humidity; ?>%</div>
                <div>Wind: <?php echo $weatherData->current->wind_kph; ?> kph, <?php echo $weatherData->current->wind_dir; ?></div>
            </div>
        <?php } ?>
    </div>
    </div>
</body>
</html>
