<?php
include __DIR__.'/../../vendor/autoload.php';
include "credentials.php";

//App url
$appUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$returnUrl = $appUrl;
$cancelUrl = $appUrl;
$logoUrl = '';

//Create Request Object
$request = new \easyPaypal\Request(true, $creed['username'], $creed['password'], $creed['signature'], $returnUrl, $cancelUrl, $logoUrl);
//Create Transaction Object
$transaction = new \easyPaypal\Transaction();
//Set start and end date
$startDate = new \DateTime();
$endtDate = new \DateTime();
$startDate->sub(new DateInterval('P30D'));
$transaction->setStartDate($startDate);
$transaction->setEndDate($endtDate);
//Set request
$transaction->setRequest($request);
//Search transaction
$response = $transaction->transactionSearch();

foreach($response as $t){
    echo $t->getCustomer()->getFirstName()."<br/>";
    echo $t->getCustomer()->getLastName()."<br/>";
    echo $t->getCustomer()->getEmail()."<br/>";
    echo $t->getPaymentDate()."<br/>";
    echo $t->getTxnId()."<br/>";
    echo $t->getPaymentStatus()."<br/>";
    echo $t->getType()."<br/>";
    echo $t->getGross()."<br/>";
    echo $t->getCurrencyCode()."<br/>";
    echo $t->getFee()."<br/>";
    echo "<br/><br/>";
}