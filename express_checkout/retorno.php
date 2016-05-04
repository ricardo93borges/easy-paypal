<?php
require_once('creed.php');
$appUrl = "http://127.0.0.1/paypal/express-checkout/";
//URL: http://127.0.0.1/paypal/retorno.php

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    echo "token: ".$token." \n";

    $nvp = array(
        'LOCALECODE' => 'en_US',
        'SUBJECT' => 'paypal2@tapiocacorp.com',
        'PAYMENTREQUEST_0_PAYMENTACTION' => 'Sale',
        'PAYMENTREQUEST_0_AMT' => '40.00',
        'PAYMENTREQUEST_0_CURRENCYCODE' => 'USD',
        'PAYMENTREQUEST_0_ITEMAMT' => '40.00',
        'L_PAYMENTREQUEST_0_NAME0' => 'Teste Daslu',
        'L_PAYMENTREQUEST_0_DESC0' => 'Teste Daslu',
        'L_PAYMENTREQUEST_0_AMT0' => '50.00',
        'L_PAYMENTREQUEST_0_NAME1' => 'Desconto',
        'L_PAYMENTREQUEST_0_DESC1' => 'Desconto',
        'L_PAYMENTREQUEST_0_AMT1' => '-10.00',
        'RETURNURL' => $appUrl . 'retorno.php',
        'CANCELURL' => $appUrl . 'cancelamento.php',
        'PAYMENTREQUEST_0_SHIPTOSTATE' => '1',
        'TOKEN' => $token,
        'METHOD' => 'GetExpressCheckoutDetails',
        'VERSION' => '124.0',
        'PWD'		=> $creed['password'],
        'USER'		=> $creed['username'],
        'SIGNATURE'     => $creed['signature']
    );

    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, 'https://api-3t.sandbox.paypal.com/nvp');
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($nvp));

    $response = urldecode(curl_exec($curl));

    $responseNvp = array();

    echo " \n";
    var_dump($responseNvp);
    echo " \n";


    if (preg_match_all('/(?<name>[^\=]+)\=(?<value>[^&]+)&?/', $response, $matches)) {
        foreach ($matches['name'] as $offset => $name) {
            $responseNvp[$name] = $matches['value'][$offset];
        }
    }

    if (isset($responseNvp['TOKEN']) && isset($responseNvp['ACK'])) {
        if ($responseNvp['TOKEN'] == $token && $responseNvp['ACK'] == 'Success') {
            $nvp['PAYERID'] = $responseNvp['PAYERID'];
            $nvp['PAYMENTREQUEST_0_AMT'] = $responseNvp['PAYMENTREQUEST_0_AMT'];
            $nvp['PAYMENTREQUEST_0_CURRENCYCODE'] = $responseNvp['PAYMENTREQUEST_0_CURRENCYCODE'];
            $nvp['SUBJECT'] = $responseNvp['SUBJECT'];
            $nvp['METHOD'] = 'DoExpressCheckoutPayment';
            $nvp['PAYMENTREQUEST_0_PAYMENTACTION'] = 'SALE';
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($nvp));

            $response = urldecode(curl_exec($curl));
            $responseNvp = array();

            if (preg_match_all('/(?<name>[^\=]+)\=(?<value>[^&]+)&?/', $response, $matches)) {
                foreach ($matches['name'] as $offset => $name) {
                    $responseNvp[$name] = $matches['value'][$offset];
                }
            }
            if ($responseNvp['PAYMENTINFO_0_PAYMENTSTATUS'] == 'Completed') {

                echo 'Parabéns, sua compra foi concluída com sucesso';
                echo '<br />';
                echo '<br />';
            } else {
                echo 'Não foi possível concluir a transação, status: ' . $responseNvp['PAYMENTINFO_0_PAYMENTSTATUS'] . " \n";
            }
        } else {
            echo 'Não foi possível concluir a transação, Tokens: ' . $responseNvp['TOKEN'] . ' | ' . $token . " \n";
        }
    } else {
        echo "Não foi possível concluir a transação. No token or ack \n";
    }
    echo $response;
    curl_close($curl);
}