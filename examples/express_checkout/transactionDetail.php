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
$transactions = $transaction->transactionSearch();

//Get transaction details
$details = $transaction->getTransactionDetails($transactions[0]->getTxnId());

echo $details->getCustomer()->getFirstName()."<br/>";
echo $details->getCustomer()->getLastName()."<br/>";
echo $details->getCustomer()->getEmail()."<br/>";
echo $details->getPaymentDate()."<br/>";
echo $details->getTxnId()."<br/>";
echo $details->getTxnType()."<br/>";
echo $details->getPaymentStatus()."<br/>";
echo $details->getGross()."<br/>";
echo $details->getCurrencyCode()."<br/>";
echo $details->getFee()."<br/>";
