<?php
include __DIR__.'/../../vendor/autoload.php';
include "credentials.php";


//App url
$appUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$returnUrl = $appUrl;
$cancelUrl = $appUrl;
$logoUrl = '';

//Create Recurring
$request = new \easyPaypal\Request(true, $creed['username'], $creed['password'], $creed['signature'], $returnUrl, $cancelUrl, $logoUrl);
$nvp = new \easyPaypal\Recurring('setExpressCheckout', 'Recurring payment test');
$nvp->setRequest($request);

$profileId = "I-59X538Y3RK29";
$response = $nvp->getRecurringProfileDetails($profileId);
var_dump($response);