<?php
include __DIR__.'/../../vendor/autoload.php';
include "credentials.php";

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
$appUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$returnUrl = $appUrl;
$cancelUrl = $appUrl;
$logoUrl = '';

//Create Checkout
$nvp = new \easyPaypal\Checkout($creed['username'], $creed['password'], $creed['signature'], true, 'expressCheckout', $returnUrl, $cancelUrl, $logoUrl);
//set notify url
//$nvp->setNotifyUrl('url');
//Create sellers
$seller = new \easyPaypal\Seller('SALE', $ref);
//Add itens to sellers
$item1 = new \easyPaypal\Item('Texugo', 'um texugo', 40.00, 1);
$item2 = new \easyPaypal\Item('Texugo 2', 'outro texugo', 40.00, 1);
$seller->addItem($item1);
$seller->addItem($item2);
//Set request
$nvp->setRequest($seller);
$response = $nvp->send($seller);
var_dump($response);