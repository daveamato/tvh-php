<?php
if($_POST["name"]) {
   $new_user_name = $_POST['name'];
}

if($_POST["password"]) {
    $passwordas = $_POST['password'];
 }

$url = "@ip_adresas";
$user_pass = "vartotojas:kodas";

 $curlHandler2 = curl_init();
 curl_setopt_array($curlHandler2, [
     CURLOPT_URL => "http://$user_pass$url/api/passwd/entry/create",
     CURLOPT_RETURNTRANSFER => true,
     CURLOPT_POST => true,
     CURLOPT_POSTFIELDS => "conf=%7B%22enabled%22%3Atrue%2C%22username%22%3A%22$new_user_name%22%2C%22password%22%3A%22$passwordas%22%2C%22auth%22%3A%5B%5D%2C%22comment%22%3A%22%22%7D", 
     
 
     ]);          
 $response2 = curl_exec($curlHandler2);
 curl_close($curlHandler2);
 //echo($response2);





$curlHandler = curl_init();
curl_setopt_array($curlHandler, [
    CURLOPT_URL => "http://$user_pass$url/api/access/entry/create",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => "conf=%7B%22enabled%22%3Atrue%2C%22username%22%3A%22$new_user_name%22%2C%22change%22%3A%5B%22change_rights%22%2C%22change_chrange%22%2C%22change_chtags%22%2C%22change_dvr_configs%22%2C%22change_profiles%22%2C%22change_conn_limit%22%2C%22change_lang%22%2C%22change_lang_ui%22%2C%22change_theme%22%2C%22change_uilevel%22%2C%22change_xmltv_output%22%2C%22change_htsp_output%22%5D%2C%22webui%22%3Atrue%2C%22admin%22%3Afalse%2C%22streaming%22%3A%5B%22basic%22%2C%22htsp%22%5D%2C%22dvr%22%3A%5B%22basic%22%2C%22htsp%22%2C%22all%22%5D%2C%22comment%22%3A%22$passwordas%22%2C%22prefix%22%3A%22%22%2C%22lang%22%3A%22%22%2C%22themeui%22%3A%22%22%2C%22langui%22%3A%22%22%2C%22profile%22%3A%5B%22b551335c41c72a7a602c4b0f70283615%22%2C%224853b0a7bed6078b91e9095d5b26f740%22%5D%2C%22dvr_config%22%3A%5B%22633736f8433e2c18620ace9c39e27283%22%5D%2C%22channel_min%22%3A%220%22%2C%22channel_max%22%3A%220%22%2C%22channel_tag_exclude%22%3Afalse%2C%22channel_tag%22%3A%5B%227894dee07c8118cdfa8d35a34d802d23%22%2C%2293af97bd1fdf3750472e5c16f241b6e7%22%5D%2C%22xmltv_output_format%22%3A0%2C%22htsp_output_format%22%3A0%2C%22uilevel%22%3A0%2C%22uilevel_nochange%22%3A0%2C%22conn_limit_type%22%3A0%2C%22conn_limit%22%3A2%2C%22htsp_anonymize%22%3Afalse%7D", 
    

    ]);          
$response = curl_exec($curlHandler);
curl_close($curlHandler);
//echo($response);
?>





<?php
header("Location: stat.php"); 
exit();
?>