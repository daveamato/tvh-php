<?php session_start();
if(!isset($_SESSION['UserData']['Username'])){
	header("location:login.php");
	exit;
}
?>


<html>
    <head>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript">
       var auto_refresh = setInterval(
          function ()
          {
             $('#load_tweets').load('stat.php').fadeIn("slow");
          }, 1000);
    </script>
    </head>
    <body>

        
    <div id="load_tweets"> </div>
    </body>
    </html>