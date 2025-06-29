<?php
require 'vendor/autoload.php';

use GuzzleHttp\Client;

if (isset($_POST['bankCode']) && isset($_POST['accNum'])) {
    $bank_code = $_POST['bankCode'];
    $acc_num = $_POST['accNum'];
    $bank = new Client([
        'base_uri' => 'https://api.paystack.co',
        'headers' => [
            'Authorization' => 'Bearer sk_test_bce4e36c3c8fc2e08cbadca9023bf5122e069275',
            'Cache-Control' => 'no-cache'
        ]
    ]);
    try {
        $response = $bank->request('GET', 'bank/resolve', [
            'query' => [
                "account_number" => $acc_num,
                "bank_code" => $bank_code

            ]
        ]);
    } catch (\Throwable $th) {
        echo json_encode([
            'status' => false,
            'message' => 'Account not found kindly confirm the number'
        ]);
        exit;
    }
    $body = $response->getBody()->getContents();
    header('Content-Type: application/json');
    echo $body;
} else {
    echo "missing parameters";
}
