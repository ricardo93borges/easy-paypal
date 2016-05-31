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
//Referencia
$ref=null;

//App url
$appUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$returnUrl = $appUrl;
$cancelUrl = $appUrl;
$logoUrl = '';

//Create Checkout
$request = new \easyPaypal\Request(true, $creed['username'], $creed['password'], $creed['signature'], $returnUrl, $cancelUrl, $logoUrl);
$checkout = new \easyPaypal\Checkout('expressCheckout');
$checkout->setRequest($request);

//set notify url
//$checkout->setNotifyUrl('url');

//Create sellers
$seller = new \easyPaypal\Seller();
$seller2 = new \easyPaypal\Seller();

//Add itens to sellers
$item1 = new \easyPaypal\Item('Texugo', 'um texugo', 40.00, 1);
$item1->setCategory('Digital');
$item2 = new \easyPaypal\Item('Texugo 2', 'outro texugo', 40.00, 1);
$seller->addItem($item1);
$seller2->addItem($item2);

$sellers = array($seller, $seller2);

//Set params
$checkout->setParams($seller);
$response = $checkout->send();
var_dump($response);