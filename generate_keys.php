<?php

$apiKey = base64_encode(openssl_random_pseudo_bytes(30));
$secretKey = base64_encode(openssl_random_pseudo_bytes(60));

file_put_contents(__DIR__.'/keys.json', json_encode(array('apiKey' => $apiKey, 'secretKey' => $secretKey)));
