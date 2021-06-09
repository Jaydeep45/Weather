<?php
    $weather = file_get_contents("https://api.openweathermap.org/data/2.5/onecall?lat=23.0333&lon=72.6167&exclude=daily&units=metric&appid=2655fc057532593907bd27cc9c40565b");
    $report = json_decode($weather,true);
    date_default_timezone_set($report['timezone']);
    $img = $report['current']['weather'][0]['icon'];
    $url = "http://openweathermap.org/img/wn/".$img."@2x.png";

    function wind_cardinals($deg) {
        $cardinalDirections = array(
            'N' => array(348.75, 361),
            'N2' => array(0, 11.25),
            'NNE' => array(11.25, 33.75),
            'NE' => array(33.75, 56.25),
            'ENE' => array(56.25, 78.75),
            'E' => array(78.75, 101.25),
            'ESE' => array(101.25, 123.75),
            'SE' => array(123.75, 146.25),
            'SSE' => array(146.25, 168.75),
            'S' => array(168.75, 191.25),
            'SSW' => array(191.25, 213.75),
            'SW' => array(213.75, 236.25),
            'WSW' => array(236.25, 258.75),
            'W' => array(258.75, 281.25),
            'WNW' => array(281.25, 303.75),
            'NW' => array(303.75, 326.25),
            'NNW' => array(326.25, 348.75)
        );
        foreach ($cardinalDirections as $dir => $angles) {
                if ($deg >= $angles[0] && $deg < $angles[1]) {
                    $cardinal = str_replace("2", "", $dir);
                }
            }
            return $cardinal;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Weather</title>
</head>
<body>
    <div class="container">
    <div class="left">
        <h2>
        Ahmedabad<br>
        <?php echo date("d/M/Y",$report['current']['dt']); ?>
        </h2>
        <img src="<?php echo $url ?>" alt="" width="100px" height="100px">
    </div>
    <div class="right">
    <table class="currTable">
        <caption>Current Weather</caption>    
        <tr class="currRow"><th class="currHead">Sunrise</th><td class="currData"><?php echo date('H:i:s',$report['current']['sunrise']);  ?></td> </tr>
        <tr class="currRow"><th class="currHead">Sunset</th><td class="currData"><?php echo date('H:i:s',$report['current']['sunset']); ?></td></tr>
        <tr class="currRow"><th class="currHead">Temperature</th><td class="currData"><?php echo $report['current']['temp']." &degC" ?></td></tr>
        <tr class="currRow"><th class="currHead">Pressure</th><td class="currData"><?php echo $report['current']['pressure'] ?></td></tr>
        <tr class="currRow"><th class="currHead">Humidity</th><td class="currData"><?php echo $report['current']['humidity'] ?></td></tr>
        <tr class="currRow"><th class="currHead">Wind Speed</th><td class="currData"><?php echo $report['current']['wind_speed']." m/s ".wind_cardinals($report['current']['wind_deg']); ?></td></tr>
    </table>
    </div>
    </div>
    <div class="data">
    <table class="dataTable">
        <caption>Next <?php echo count($report['hourly']); ?> Hour Weather</caption>
    <tr class="dataRow">
    <thead class="dataHead">
            <th class="dataHead">Date/time</th><th class="dataHead">Sky</th><th class="dataHead">Temp</th><th class="dataHead">Rain(%)</th><th class="dataHead">Wind Speed(m/s)</th>
    </thead></tr>
    <?php
        $i = 0;
        while($i < count($report['hourly'])) {
    ?>
    <tr class="dataRow">
    <td class="dataD"><?php echo date('d/M/Y  H:i',$report['hourly'][$i]["dt"]) ?></td><td class="dataD"><img src="<?php  echo "http://openweathermap.org/img/wn/".$report['hourly'][$i]['weather'][0]['icon']."@2x.png" ?>" alt=""></td>
    <td class="dataD"><?php echo $report['hourly'][$i]['temp']."&deg C" ?></td><td class="dataD"><?php echo $report['hourly'][$i]["pop"]*100 ?></td><td class="dataD"><?php echo $report['hourly'][$i]["wind_speed"]." ".wind_cardinals($report['hourly'][$i]['wind_deg'])  ?></td>
    </tr>
    <?php 
    $i++;
    }?>
    </table>
    </div>
</body>
</html>