<?php
require_once('creed.php');
require_once('NvpRequest.php');

$total = '0.5'; //Total do carrinho do cliente
$appUrl = "http://127.0.0.1/paypal/express-checkout/";


    $nvp = new NvpRequest($creed['username'], $creed['password'], $creed['signature'], true);

$params = array(
        'LOCALECODE' => 'pt_BR',
        'METHOD' => 'SetExpressCheckout',
        'RETURNURL' => $appUrl.'checkout.php',
        'CANCELURL' => $appUrl.'checkout.php',
        'BUTTONSOURCE' => 'BR_EC_EMPRESA',
        'PAYMENTACTION' => 'SALE',
        'sellers' => array(
            'ricardo'=>array(
                'PAYMENTACTION'=>'SALE',
                'AMT'=>'10.00',
                'CURRENCYCODE'=>'BRL',
                'ITEMAMT'=>'10.00',
                'INVNUM'=>'ref123',
                'items'=>array(
                    array(
                    'NAME'=>'texugo',
                    'DESC'=>'apenas um texugo',
                    'AMT'=>'10.00',
                    'QTY'=>'1',
                    'ITEMAMT'=>'10.00',
                    )
                )
            )
        ));

if (isset($_GET['token'])) {
    $params['TOKEN'] = $_GET['token'];
    $params['METHOD'] = 'GetExpressCheckoutDetails';

    $response = $nvp->send($params);

    if (isset($response['TOKEN']) && isset($response['ACK'])) {
        //if ($response['TOKEN'] == $token && $responseNvp['ACK'] == 'Success') {
        if ($response['ACK'] == 'Success') {
            $params['PAYERID'] = $response['PAYERID'];
            $params['METHOD'] = 'DoExpressCheckoutPayment';

            $response = $nvp->send($params);
            if ($response['PAYMENTINFO_0_PAYMENTSTATUS'] == 'Completed') {
                echo 'Parabéns, sua compra foi concluída com sucesso';
                echo '<br />';
                echo '<br />';
            } else {
                echo 'Não foi possível concluir a transação, status: ' . $response['PAYMENTINFO_0_PAYMENTSTATUS'] . " \n";
            }
        } else {
            echo 'Não foi possível concluir a transação, Tokens: ' . $response['TOKEN'] . ' | ' . $_GET['token'] . " \n";
        }
    } else {
        echo "Não foi possível concluir a transação. No token or ack \n";
    }

}else {
    echo "<br>SetExpressCheckout<br>";
    $response = $nvp->send($params);

    if (isset($response['ACK']) && $response['ACK'] == 'Success') {
        $paypalURL = 'https://www.sandbox.paypal.com/br/cgi-bin/webscr';
        $query = array(
            'cmd' => '_express-checkout',
            'useraction' => 'commit',
            'token' => $response['TOKEN']
        );
        header('Location: ' . $paypalURL . '?' . http_build_query($query));
    } else {
        echo "ACK not set <br>";
        echo var_dump($response);
    }
}