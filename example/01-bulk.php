<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use koulab\googledrive\GDrivePDFSniffParser;
use koulab\googledrive\GDrivePDFSniffRequestor;
use Psr\Http\Message\ResponseInterface;

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


//bulk download with concurrency
//see
//http://docs.guzzlephp.org/en/stable/quickstart.html#async-requests
//https://qiita.com/kanoe/items/b428f6553d81b8e22990
$requests = function() use ($client,$to,$signature,$meta) {
    for($i = 0; $i < $meta->getMaxPageWidth(); ++$i){
        yield function() use ($client,$to,$signature,$meta,$i) {
            $downloadRequest = $to->download($signature, $meta, $i);
            $promise = $client->sendAsync($downloadRequest);
            $promise->then(
            // Fullfilled
                function(ResponseInterface $response) use($i){
                    file_put_contents('../downloadtest/' . $i . '.webp', $response->getBody());
                },
                //Rejected
                function(RequestException $e) {
                    echo $e->getMessage() . "\n";
                    echo $e->getRequest()->getMethod();
                }
            );
            return $promise;
        };
    }
};
$pool = new Pool($client, $requests(),[
    'concurrency'=>3
]);
$promise = $pool->promise();
$promise->wait();