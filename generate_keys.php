<?php

$apiKey = base64_encode(openssl_random_pseudo_bytes(128));
$secretKey = base64_encode(openssl_random_pseudo_bytes(128));

file_put_contents(__DIR__.'/keys.json', json_encode(array('apiKey' => $apiKey, 'secretKey' => $secretKey)));
