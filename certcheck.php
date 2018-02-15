<?php
require 'vendor/autoload.php';
require 'mailer.php';
use Carbon\Carbon;
use PHPMailer\PHPMailer\PHPMailer;


$urls =[
  //input all your SSL domains here that you want to recieve a report on.
 "https://my-ssl-site.com",
];

$expiries = [];

foreach($urls as $key => $url){
  $orignal_parse = parse_url($url, PHP_URL_HOST);
  try{
    set_error_handler(function() { /* ignore errors */ });
    $get = stream_context_create(array("ssl" => array("capture_peer_cert" => TRUE)));
    $read = stream_socket_client("ssl://".$orignal_parse.":443", $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $get);
    $cert = stream_context_get_params($read);
    $certinfo = openssl_x509_parse($cert['options']['ssl']['peer_certificate']);
    restore_error_handler();
    if(!$certinfo){throw new Exception('SSL Connection has Failed');}
    $expiries[$url] = Carbon::createFromTimestamp($certinfo['validTo_time_t'])->diffInDays( Carbon::now() );

  }catch(Exception $e){
    $expiries[$url] = $e->getMessage();
  }

}

asort($expiries); //Sort expiries by date; Failures First

$mail = new PHPMailer;
//Server settings
$mail->isSMTP();
$mail->Host = 'your.host.com'; //Enter your Host Here
$mail->SMTPAuth = true;
$mail->Username = 'sslchecker@example.com'; //Set Email accunt username
$mail->Password = 'hunter2'; //Set Secret Pass
$mail->SMTPSecure = 'ssl';
$mail->Port = 465; //Make sure port is correct for server

//Recipients
$mail->setFrom('SSLChecker@example.com', 'SSLChecker'); //Set the FROM email
$mail->addAddress('me@example.com'); //Set to your email


//Content
$mail->isHTML(true);
$mail->Subject = 'SSL Checker Report '.Carbon::now()->toFormattedDateString();
$mail->Body    = makeMailBody($expiries);

$mail->send();
echo "Message has been sent \n";

?>
