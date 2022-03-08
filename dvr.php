<?php 

header('Content-Type: text/plain');

//tvheadend irasai

$user = $_GET['user'];
$pass = $_GET['pass'];
$tvheadend_ip = "tvheadend_adresas";

$json_url = "http://$user:$pass@$tvheadend_ip/api/dvr/entry/grid_finished";
$json = file_get_contents($json_url);
$array2 = json_decode($json);

foreach ($array2->entries as $o) 
 {
$date = date("H:i d-m-Y", $o->start);

echo "#EXTINF:-1 tvg-logo=\"$o->channel_icon\" group-title=\"Įrašai ($o->channelname)\",$o->channelname|$o->disp_title|$date\n";
echo "http://$user:$pass@$tvheadend_ip/$o->url\n";

}

?>