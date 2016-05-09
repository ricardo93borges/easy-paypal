<?php

//require_once('api/autoload.php');
include "creed.php";
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

//App url
$appUrl = "http://localhost/paypal/express_checkout/";

//Create NvpRequest
$nvp = new easyPaypal\NvpRequest($creed['username'], $creed['password'], $creed['signature'], true, 'expressCheckout', $appUrl.'checkout.php', $appUrl.'checkout.php', 'http://painel.iprevenda.com/imagens/logo-default.png');
//Create sellers
$seller = new Seller('SALE', $ref);
//Add itens to sellers
$item1 = new Item('Texugo', 'um texugo', 40.00, 1);
$item2 = new Item('Texugo 2', 'outro texugo', 40.00, 1);
$seller->addItem($item1);
$seller->addItem($item2);
//Set request
$nvp->setRequest($seller);

$response = $nvp->send($seller);
var_dump($response);