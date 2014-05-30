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
    'message' => $message,
    'apiKey' => $apiKey,
    'timestamp' => time(),
);

// Build the signature
$signatureQueryParts = $queryParts;

// Lowercase query part keys and sort case insensitively by field name
ksort($signatureQueryParts, SORT_STRING | SORT_FLAG_CASE);
$signatureUrl = strtolower($endpoint.'?'.http_build_query($signatureQueryParts));
$signature = base64_encode(hash_hmac('sha256', $signatureUrl, $secretKey));

$queryParts['signature'] = $signature;

$url = $endpoint.'?'.http_build_query($queryParts);

file_put_contents(__DIR__.'/request.txt', $url);
