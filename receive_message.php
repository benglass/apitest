<?php

$keys = json_decode(file_get_contents(__DIR__.'/keys.json'), true);
$apiKey = $keys['apiKey'];
$secretKey = $keys['secretKey'];

if (empty($apiKey) || empty($secretKey)) {
    throw new \LogicException('No api key or secret key');
}

$url = file_get_contents(__DIR__.'/request.txt');
$parts = parse_url($url);
parse_str($parts['query'], $query);

// Verify the signature
$submittedApiKey = $query['apiKey'];

// Verify API Key, we'd look this up in the database
if ($apiKey !== $submittedApiKey) {
    throw new \LogicException('Bad api key');
}

// Strip the query string off the url
$endpoint = substr($url, 0, strpos($url, $parts['query']) - 1);

// Remove signaure and sort query parts by case insensitively by field name
$signatureQueryParts = $query;
unset($signatureQueryParts['signature']);
ksort($signatureQueryParts, SORT_STRING | SORT_FLAG_CASE);

// Signature url is lowercased, sorted url
$signatureUrl = strtolower($endpoint.'?'.http_build_query($signatureQueryParts));

$signature = base64_encode(hash_hmac('sha256', $signatureUrl, $secretKey));
$submittedSignature = $query['signature'];

if ($signature === $submittedSignature) {
    echo 'Signature OK!';
} else {
    echo 'Bad signature';
}
