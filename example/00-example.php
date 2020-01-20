<?php

use GuzzleHttp\Client;
use koulab\googledrive\GDrivePDFSniffParser;
use koulab\googledrive\GDrivePDFSniffRequestor;

require '../vendor/autoload.php';
$client = new Client();

$to = new GDrivePDFSniffRequestor();
$signatureRequest =  $to->getDownloadCredentials('https://drive.google.com/file/d/1s5t-RCZ3e-piWFVDp6F3ok-cmY8MGasc/view');
$response = $client->send($signatureRequest);
$signature = GDrivePDFSniffParser::parseItemJsonSignature($response);
var_dump($signature);

$imageKeyRequest = $to->getImageKey($signature);
$response = $client->send($imageKeyRequest);
$imageKey = GDrivePDFSniffParser::parseImageToken($response,$signature);
var_dump($signature);

$metaRequest = $to->getMeta($signature);
$response = $client->send($metaRequest);
$meta = GDrivePDFSniffParser::parseMeta($response);
var_dump($meta);

//First page = 0
for($i = 0 ; $i < $meta->getPages(); $i++){
    $downloadRequest = $to->download($signature, $meta, $i);
    $response = $client->send($downloadRequest);
    file_put_contents('download/' . $i . '.webp', $response->getBody());
    echo 'page:'.$i.' downloaded'.PHP_EOL;
}