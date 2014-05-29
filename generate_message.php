<?php

$keys = json_decode(file_get_contents(__DIR__.'/keys.json'), true);
$apiKey = $keys['apiKey'];
$secretKey = $keys['secretKey'];

if (empty($apiKey) || empty($secretKey)) {
    throw new \LogicException('No api key or secret key');
}

$message = 'This is my legit message';
$endpoint = 'http://www.api.com/orders';
$queryParts = array(
    'apiKey' => $apiKey,
    'message' => $message,
    'timestamp' => time()
);
$url = $endpoint.'?'.http_build_query($queryParts);

$signature = base64_encode(hash_hmac('sha256', $url, $secretKey));

$queryParts['signature'] = $signature;

$url = $endpoint.'?'.http_build_query($queryParts);

file_put_contents(__DIR__.'/request.txt', $url);
