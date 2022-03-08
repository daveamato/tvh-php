<?php
if($_POST["uuid"]) {
   $uuid = $_POST['uuid'];
}

// uuid: ["2910dfdaf72c7e252491a783d0e1c011"]

$curlHandler = curl_init();


$url = "ip_adresas";
$user_pass = "vartotojas:kodas";

curl_setopt_array($curlHandler, [
    CURLOPT_URL => "http://$user_pass$url/api/idnode/delete",
    CURLOPT_RETURNTRANSFER => true,

    /**
     * Specify POST method
     */
    CURLOPT_POST => true,

    /**
     * Specify request content
     */
    CURLOPT_POSTFIELDS => "uuid=$uuid", 
]);          

$response = curl_exec($curlHandler);

curl_close($curlHandler);

echo($response);
?>