<?php 
//ENVIRONMENT
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
date_default_timezone_set('UTC');
//INCLUDES
include 'config.php';
include 'classes/OpenWeatherClient.php';
include 'classes/MoonClient.php';
//CLASSES
$openWeatherClient = new OpenWeatherClient($OpenWeatherAPIKey);
$moonClient = new MoonClient();
?>
<html>
<head>
	<title>WEATHERCHECKER</title>
</head>
<body>
<?php
if ($_REQUEST['pwd'] != $pwd) {
	die('Unauthorized');
}

$ar = [];

$html = '<pre>';
foreach ($cities as $city) {
	$res = $openWeatherClient->call(['q' => $city]);			
	if (isset($res['list']) && !empty($res['list'])) {
		foreach ($res['list'] as $k => $v) {						
			foreach($v['weather'] as $kk => $vv) {												
				if (
					strtolower($vv['main']) == strtolower($check_for)
					 && in_array(date("l", $v['dt']), $check_days)
					  && ($only_night === false || (date('G',$v['dt']) >= 21 || date('G',$v['dt']) <= 8))
				) {
					$moon = $moonClient->moon($v['dt']);
					if ($moon['illumination'] <= $check_moon) {
						$vv['moon_illumination'] = $moon['illumination'];												
						$vv['clouds'] = $v['clouds']['all'];
						if ($vv['clouds'] <= $check_clouds) {
							$ar[$city][date('Y-m-d',$v['dt'])][date('H:i:s',$v['dt'])] = $vv;					
						}
					}
				}
			}
		}
	} else {
		$html .= '<p style="color:red">No results for '.$city.'</p>';		
	}
}

if (empty($ar)) {
	$html .= '<p style="color:red">No results</p>'; 	
} else {
	//Print results
	foreach ($ar as $city => $v) {
		$html .= '<h3>Found in '.$city.'</h3><hr>';		
		foreach ($v as $date => $vv) {
			$html .= '<h4>'.$date.' ('.date('l',strtotime($date)).')</h4>';
			$html .= '<table border=1><tr><th>Hour</th><th>Weather</th><th>Moon %</th><th>Clouds</th></tr>';
			foreach ($vv as $hour => $vvv) {			
				$html .= '<tr><td>'.$hour.'</td><td><img title="'.$vvv['description'].'" src="http://openweathermap.org/img/w/'.$vvv['icon'].'.png"></td><td><i>'.round($vvv['moon_illumination']).'%</i></td><td><i>'.$vvv['clouds'].'%</i></td></tr>';						
			}		
			$html .= '</table>';
		}		
	}	
}
if (isset($_REQUEST['mail']) && $_REQUEST['mail'] == 1) {
	// Always set content-type when sending HTML email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

	// More headers
	$headers .= 'From: <'.$mailFrom.'>' . "\r\n";	

	mail($mailTo,$mailTitle,$html,$headers);
}
echo $html;
?>
</body>
</html>