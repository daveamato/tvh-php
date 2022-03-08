<?php
if($_POST["node"]) {
   $uuid = $_POST['node'];
}

$url = "@ip_adresas";
$user_pass = "vartotojas:kodas";

$curlHandler = curl_init();

curl_setopt_array($curlHandler, [
    CURLOPT_URL => "http://$user_pass$url/api/idnode/save",
    CURLOPT_RETURNTRANSFER => true,

    /**
     * Specify POST method
     */
    CURLOPT_POST => true,

    /**
     * Specify request content
     */
    CURLOPT_POSTFIELDS => "node=$uuid", 
]);          

$response = curl_exec($curlHandler);

curl_close($curlHandler);

echo($response);
?>