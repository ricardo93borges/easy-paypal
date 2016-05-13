<?php
include __DIR__."/../vendor/autoload.php";

$receiverEmail = 'ricardo_borges26-facilitator_api1.hotmail.com';
$sandbox = true;

$ipn = new \easyPaypal\ipn\Ipn($receiverEmail, $sandbox);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = $ipn->handleIpn($_POST);

    if(empty($response)){
        die("empty response");
    }

    if (isset($response['error'])) {
        die(print_r($response));
    }

    $notification = $response['notification'];
    $customer = $response['customer'];
    $trasaction = $response['transaction'];


    print_r($notification);
    print_r($customer);
    print_r($trasaction);
}