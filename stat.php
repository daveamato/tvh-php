<!doctype html>
<html lang="en">
<head>

    <title>Stats</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <style>
    body {
        font-family: Poppins;
    }
    </style>
</head>

<?php
   if( $_POST["name"] || $_POST["password"] ) {          
      exit(); 
   } 
?>


<?php session_start(); /* Starts the session */

if(!isset($_SESSION['UserData']['Username'])){
	header("location:login.php");
	exit;
}
?>

<?php
require_once 'vendor/autoload.php';
use GeoIp2\Database\Reader;

date_default_timezone_set('Europe/Vilnius');

// error_reporting(-1);
//ini_set('display_errors', 'On');

header("Access-Control-Allow-Origin: *");
header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Connection: close");


$url = "vartotojas:kodas@ip_adresas_tvheadend";

$json_url = "http://$url/api/status/subscriptions";
$json_url2 = "http://$url/api/status/inputs";
$json_url_status = "http://$url/api/status/connections";
$json_users = "http://$url/api/access/entry/grid";
$json_password = "http://$url/api/access/entry/grid";

$json1 = file_get_contents($json_url);
$json2 = file_get_contents($json_url2);
$json_url_status = file_get_contents($json_url_status);
$json_users = file_get_contents($json_users);
$json_password = file_get_contents($json_password);

$array1 = json_decode($json1);
$array2 = json_decode($json2);
$array_status = json_decode($json_url_status);
$array_users = json_decode($json_users);
$array_users2 = json_decode($json_users, true);
$array_password = json_decode($json_password);

$contents_d = json_decode($json2);
$contents_c = json_decode($json1);


function elapsed_time($timestamp, $precision = 2) {
    $time = time() - $timestamp;
    $a = array('decade' => 315576000, 'year' => 31557600, 'month' => 2629800, 'week' => 604800, 'day' => 86400, 'hour' => 3600, 'min' => 60, 'sec' => 1);
    $i = 0;
    foreach($a as $k => $v) {
      $$k = floor($time/$v);
      if ($$k) $i++;
      $time = $i >= $precision ? 0 : $time - $$k * $v;
      $s = $$k > 1 ? 's' : '';
      $$k = $$k ? $$k.' '.$k.$s.' ' : '';
      @$result .= $$k;
    }
    return $result ? $result.'ago' : '1 sec to go';
  }


echo '<div class="container" align="center">';
echo '<br>';
echo '<h1 class="text-center">Main</h1>';
echo '<a href="logout.php">Logout</a>';
echo ' / ';
echo '<a href="stat_auto.php">Auto Update</a>';
echo ' / ';
echo '<a href="stat.php">Disable Auto Update</a>';
echo '<div class="table-responsive">';
echo '<table class="table table-striped table-hover w-auto small table-bordered table-condensed">';
echo "<th style=\"width: 5%\">User</th>";
echo "<th style=\"width: 7%\">IP Flag</th>";
echo "<th style=\"width: 8%\">IP</th>";
echo "<th style=\"width: 15%\">Client</th>";
echo "<th style=\"width: 20%\">Channel</th>";
echo "<th style=\"width: 16.66%\">Service</th>";
echo "<th style=\"width: 5%\">In</th>";
echo "<th style=\"width: 5%\">Out</th>";
echo "<th style=\"width: 3%\">Error</th>";
echo "<th style=\"width: 30%\">Watch time</th>";
echo "<th style=\"width: 3%\">Profile</th>";

$insum = 0;
$outsum = 0;

foreach ($array1->entries as $o) 
{   
    $epgkanalas = $o->channel;
    $epgadresas = rawurlencode($epgkanalas);
    
    $json_url3 = "http://$url/api/epg/events/grid?channel=$epgadresas&mode=now";

    $json3 = file_get_contents($json_url3);
    $array3 = json_decode($json3);


    $date = date("H:i (d-m)", $o->start);
    $elapsed = elapsed_time($o->start).'<br />';
    $in = $o->in * 1 / 1000;
    $in = round($in, 0);
    $out = $o->out * 1 / 1000;
    $out = round($out, 0);

    $insum = $insum + $in;
    $outsum = $outsum + $out;


    $reader = new Reader('/usr/local/share/GeoIP/GeoIP2-City.mmdb');

    $ipas = str_replace('::ffff:', '', $o->hostname);
    $ipas2 = str_replace('192.168.1.1', 'pagrindinis_ip_adresas', $ipas);

    $record = $reader->city("$ipas2");
    $ip_salies_kodas = $record->country->name;
    
    $salies_trumpas_pav = $record->country->isoCode;
    
    echo '<tr>'; 
    echo "<th>$o->username</th>";
    echo "<th><img src=\"https://www.countryflags.io/$salies_trumpas_pav/flat/32.png\">";
    echo "</th>";
    echo "<th>";
    echo "$ipas2";

  if(empty($array3->entries[0]->channelIcon)) {
    $channelicons = "https://image.flaticon.com/icons/svg/2916/2916379.svg";
   } else {
    $channelicons = $array3->entries[0]->channelIcon;
   }

    echo "</th>";
    echo "<th>$o->client</th>";
    echo "<th><img src=\"$channelicons\" class=\"rounded float-left img-fluid\" width=\"38\">";    
    echo "$o->channel > " . $array3->entries[0]->title . "</th>";
    echo "<th>$o->service</th>";
    echo "<th>$in KB/s</th>";
    echo "<th>$out KB/s</th>";
    echo "<th>$o->errors</th>";
    echo "<th>$date $elapsed</th>";
    echo "<th>$o->profile</th>";
    echo "</tr>";
}
echo '<tr>';
echo '<tr>';
echo '</tr>';
echo "</table>";
echo '</div>';
echo '<div class="jumbotron">';
echo '<h5 class="">';
echo "Traffic In Total:   $insum KB/s";
echo "<br>";
echo "Traffic Out Total: $outsum KB/s";
echo '</h5>';
echo '<h5 class="">';
echo 'Total Input Connections: ';
echo $contents_d->totalCount;
echo "<br>";
echo 'Total Output Connections: ';
echo $contents_c->totalCount;
echo "</div>";


echo '<h1 class="text-center">Users</h1>';
echo "<br>";
echo"<form method=\"POST\" action=\"user_add.php\">";
echo"<form class=\"form-inline\">";
echo"<div class=\"form-group\">";
echo"<label for=\"exampleInputName2\">NEW Username</label>";
echo"<input type=\"text\" name=\"name\" class=\"form-control\" id=\"exampleInputName2\" placeholder=\"username\"/>";
echo "</div>";
echo"<div class=\"form-group\">";
echo"<label for=\"exampleInputName2\">Password</label>";
echo"<input type = \"text\" name = \"password\" class=\"form-control\" id=\"exampleInputEmail2\" placeholder=\"password\" />";
echo "</div>";
echo"<input type = \"submit\"class=\"btn btn-light\"></input>";
echo"</form>";
echo "<br>";
echo '<div class="table-responsive">';
echo '<table class="table table-striped table-hover w-auto small table-bordered table-condensed">';
echo "<th>User</th>";
echo "<th>Status</th>";
echo "<th>Disable / Enable / Delete</th>";
echo "<th>IPTV list</th>";
echo "<th>VOD list</th>";
echo '</tr>';

foreach ($array_users->entries as $u   )
{
 echo '<script type="text/javascript" language="javascript">';
 echo '$(document).ready(function() {';
  echo '$("#disable-'.$u->uuid.'").click(function(event){';
    echo '$.post("user.php",';
    echo "{node: \"%5B%7B%22enabled%22%3Afalse%2C%22uuid%22%3A%22$u->uuid%22%7D%5D\"},";
    echo 'function(data) { ';
      echo '$(".result-'.$u->uuid.'").html(data);}';
      echo ');';
      echo '});';
      echo '}); ';
      echo '</script>';

 
  echo '<script type="text/javascript" language="javascript">';
  echo '$(document).ready(function() {';
   echo '$("#enable-'.$u->uuid.'").click(function(event){';
     echo '$.post("user.php",';
     echo "{node: \"%5B%7B%22enabled%22%3Atrue%2C%22uuid%22%3A%22$u->uuid%22%7D%5D\"},";
     echo 'function(data) { ';
       echo '$(".result2-'.$u->uuid.'").html(data);}';
       echo ');';
       echo '});';
       echo '}); ';
       echo '</script>';
       echo " ";

 
       echo '<script type="text/javascript" language="javascript">';
       echo '$(document).ready(function() {';
        echo '$("#delete-'.$u->uuid.'").click(function(event){';
          echo '$.post("user_delete.php",';
          echo "{uuid: \"$u->uuid\"},";
          echo 'function(data) { ';
            echo '$(".delete-'.$u->uuid.'").html(data);}';
            echo ');';
            echo '});';
            echo '}); ';
            echo '</script>';
            echo " ";
  

       echo '<tr>';
       echo "<th>$u->username</th>";
       echo "<th>";
       echo $u->enabled ? 'enabled' : 'disabled';
       echo "</th>";


       $iptv_and_vod_address = "php_ip_adresas";

       echo "<th>";
       echo "<div class=\"btn-group\" role=\"group\" aria-label=\"\">";
       echo "<button type='button' onclick=\"location=URL\" class=\"btn btn-light\"id='disable-".$u->uuid."'>Disable</button>";
       echo "<button type='button' onclick=\"location=URL\" class=\"btn btn-light\"id='enable-".$u->uuid."'>Enable</button>";
       echo "<button type='button' onclick=\"location=URL\" class=\"btn btn-light\"id='delete-".$u->uuid."'>Delete</button>";

       echo "</th>";
       echo "<th>";
       echo "<a href=\"http://$iptv_and_vod_address/tvh.php?user=$u->username&pass=$u->comment\" target=\"_blank\"> iptv m3u</a>";
       echo "</th>";
       echo "<th>";
       echo "<a href=\"http://$iptv_and_vod_address/dvr.php?user=$u->username&pass=$u->comment\" target=\"_blank\"> vod m3u</a>";
       echo "</th>";
       echo '</tr>';

  echo "</div>";
  echo "</div>";
  echo " ";
  echo " ";
  }

  echo "<div class=\"#result-$u->uuid\"></div>";
  echo "<div class=\"#result2-$u->uuid\"></div>";
  echo "<div class=\"#delete-$u->uuid\"></div>";
  echo "</table>";
  echo "</div>";


?>

</body>
</html>