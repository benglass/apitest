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

// Remove the signature from the url and verify it
$verifyUrl = substr($url, 0, strpos($url, '&signature='));

$signature = base64_encode(hash_hmac('sha256', $verifyUrl, $secretKey));
$submittedSignature = $query['signature'];

if ($signature === $submittedSignature) {
    echo 'Signature OK!';
} else {
    echo 'Bad signature';
}
