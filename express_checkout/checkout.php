<?php
namespace paypal\express_checkout;
require_once('api/autoload.php');
/*
 * Referencia / Invoice ID: Campo importante para o acompanhamento e controle interno do comerciante
 * Este campo na requisição aparece como INVNUM
 *
 * Esse campo descreve o número do pedido do cliente dentro de sua própria loja.
 * É seu identificador interno, que pode ser utilizado para a PayPal para ajudá-lo a
 * identificar as transações durante as notificações.
 *
 * Defina a referencia ao criar um objeto Seller senão será usado uma String aleatória
 *
 */
$ref=null;

//Credentials
$creed = array(
    'username' => 'ricardo_borges26-facilitator_api1.hotmail.com',
    'password' => '53BTEC9TEFQMXUEB',
    'signature' => 'AFcWxV21C7fd0v3bYYYRCpSSRl31AtMJGOkep0UQaULI7-wxg9S7MPtb'
);
//App url
$appUrl = "http://localhost/paypal/express_checkout/";

//Create NvpRequest
$nvp = new api\NvpRequest($creed['username'], $creed['password'], $creed['signature'], true, 'expressCheckout', $appUrl.'checkout.php', $appUrl.'checkout.php', 'http://painel.iprevenda.com/imagens/logo-default.png');
//Create sellers
$seller = new api\Seller('SALE', $ref);
//Add itens to sellers
$item1 = new api\Item('Texugo', 'um texugo', 40.00, 1);
$item2 = new api\Item('Texugo 2', 'outro texugo', 40.00, 1);
$seller->addItem($item1);
$seller->addItem($item2);
//Set request
$nvp->setRequest($seller);

$response = $nvp->send($seller);
var_dump($response);