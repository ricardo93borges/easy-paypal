<?php
include __DIR__.'/../../vendor/autoload.php';
include "credentials.php";


//App url
$appUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$returnUrl = $appUrl;
$cancelUrl = $appUrl;
$logoUrl = '';

//Create Recurring
$nvp = new \easyPaypal\Recurring($creed['username'], $creed['password'], $creed['signature'], true, $returnUrl, $cancelUrl, 100, 'Recurring payment test', 'expressCheckout');
//Trial
$nvp->setTrialAmt(0);
$nvp->setTrialBillingFrequency(1);
$nvp->setTrialBillingPeriod('Month');//(Month|Day|Week|Year)
$nvp->setTrialTotalBillingCycles(1);
//Create sellers
$seller = new \easyPaypal\Seller('SALE', null, 'BRL');
//Add itens to sellers
$item1 = new \easyPaypal\Item('Texugo', 'um texugo', 40.00, 1, 'RecurringPayments', 'Recurring payment item');
$item2 = new \easyPaypal\Item('Texugo 2', 'outro texugo', 40.00, 1, 'RecurringPayments', 'Recurring payment item');
$seller->addItem($item1);
$seller->addItem($item2);
//Set request
$nvp->setRequest($seller);
$response = $nvp->send($seller);
var_dump($response);
