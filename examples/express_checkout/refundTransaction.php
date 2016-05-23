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
//Search transactions
$transactions = $transaction->transactionSearch();

echo "Full Refund test <br/><br/>";
//Get transaction details
$details = $transaction->getTransactionDetails($transactions[0]->getTxnId());
//Full Refund transaction
$response = $transaction->refundTransaction($details->getTxnId(), 'Full');
if(isset($response['ACK']) && $response['ACK'] == "Success"){
    echo "Success on Full Refund transaction. REFUNDTRANSACTIONID: ".$response['REFUNDTRANSACTIONID']."<br/><br/>";
}
var_dump($response);

//function refundTransaction($transactionId, $refundType, $amount=null, $currencyCode=null, $note=null, $payerId=null, $invoiceId=null){
echo "<br/><br/>";

echo "Partial Refund test <br/><br/>";
//Get transaction details
$details = $transaction->getTransactionDetails($transactions[1]->getTxnId());
//Full Refund transaction
$response = $transaction->refundTransaction($details->getTxnId(), 'Partial', 1, $details->getCurrencyCode(), "Partial Refund test", $details->getCustomer()->getPaypalId());
if(isset($response['ACK']) && $response['ACK'] == "Success"){
    echo "Success on Full Refund transaction. REFUNDTRANSACTIONID: ".$response['REFUNDTRANSACTIONID']."<br/><br/>";
}
var_dump($response);

