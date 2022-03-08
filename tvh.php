<?php 

print_r(get_headers($url)); // paima tuos pacius headerius kuris praso
header('Content-Type: text/plain');  // atiduoda kaip txt faila
header("Access-Control-Allow-Origin: *");


date_default_timezone_set('Europe/Vilnius');


$user = $_GET['user'];
$pass = $_GET['pass'];

$tvheadend_ip = "tvheadend_adresas";

//kanalai

$json_url = "http://$user:$pass@$tvheadend_ip/api/channel/grid?limit=999&sort=name";
$json_url3 = "http://$user:$pass@$tvheadend_ip/api/channeltag/grid";

$json1 = file_get_contents($json_url);
$json3 = file_get_contents($json_url3);

$array1 = json_decode($json1, true);
$array3 = json_decode($json3, true);
echo "#EXTM3U x-tvg-url=\"https://epg.adreas.lt\"";
echo " url-tvg=\"https://epg.adreas.lt\"\n";

$x = array();

foreach($array3['entries'] as $value) {

	$x[$value['uuid']] = $value['name'];

}

foreach($array1['entries'] as $value) {

	if (array_key_exists($value['tags'][0],$x)) {
		$name[] = $x[$value['tags'][0]];
	
	}
	
	echo "#EXTINF:-1 tvg-logo=\"";
	echo $value['icon_public_url'];
	echo "\" group-title=\"";
	echo $x[$value['tags'][0]];
	echo "\" tvg-id=\"";
	echo $value['name'];
	echo "\",";
	echo $value['name'];
	echo "\n";
	echo "http://$user:$pass@$tvheadend_ip/stream/channel/";
	echo $value['uuid'];
	echo "\n";
}



?>