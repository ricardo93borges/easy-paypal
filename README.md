### Easy Paypal

Easy paypal é um SDK para facilitar a integração do Paypal NVP API e Paypal IPN com sua aplicação.

#### Pré-Requisitos
* PHP >= 5.3
* Curl extension 
* Composer

#### Exemplos

##### Express checkout

```php

include "autoload.php";

//Referencia / Invoice ID: Campo para o acompanhamento e controle interno do comerciante
$ref=null;

$appUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$returnUrl = $appUrl;
$cancelUrl = $appUrl;

$request = new \easyPaypal\Request(true, $username, $password, $signature, $returnUrl, $cancelUrl, $logoUrl);
$nvp = new \easyPaypal\Checkout('expressCheckout');
$nvp->setRequest($request);

//Create sellers
$seller = new \easyPaypal\Seller('SALE', $ref);
//Add itens to sellers
$item1 = new \easyPaypal\Item('Item 1', 'Description', 40.00, 1);
$item2 = new \easyPaypal\Item('Item 2', 'Description', 40.00, 1);

$seller->addItem($item1);
$seller->addItem($item2);

//Set request
$nvp->setParams($seller);
$response = $nvp->send($seller);
```

O exemplo de express checkout passo a passo pode ser encontrado em examples/checkout_step_by_step.php

##### Criar perfil de recorrência

```php
$request = new \easyPaypal\Request(true, $username, $password, $signature, $returnUrl, $cancelUrl, $logoUrl);
$nvp = new \easyPaypal\Recurring('expressCheckout', 100, 'Recurring payment test');
$nvp->setRequest($request);
//Create sellers
$seller = new \easyPaypal\Seller('SALE', null, 'BRL');
//Add itens to sellers
$item1 = new \easyPaypal\Item('Item 1', 'Description', 40.00, 1, 'RecurringPayments', 'Recurring payment item');
$seller->addItem($item1);
//Set request
$nvp->setParams($seller);
$response = $nvp->send($seller);
```

##### Perfil de recorrência com periodo de teste

```php
$request = new \easyPaypal\Request(true, $username, $password, $signature, $returnUrl, $cancelUrl, $logoUrl);
$nvp = new \easyPaypal\Recurring('expressCheckout', 100, 'Recurring payment test');
$nvp->setRequest($request);
//Trial
$nvp->setTrialAmt(0);
$nvp->setTrialBillingFrequency(1);
$nvp->setTrialBillingPeriod('Month');//(Month|Day|Week|Year)
$nvp->setTrialTotalBillingCycles(1);
//Create sellers
$seller = new \easyPaypal\Seller('SALE', null, 'BRL');
//Add itens to sellers
$item1 = new \easyPaypal\Item('Item 1', 'Description', 40.00, 1, 'RecurringPayments', 'Recurring payment item');
$seller->addItem($item1);
//Set request
$nvp->setParams($seller);
$response = $nvp->send($seller);
```

##### Reembolso

```php
$request = new \easyPaypal\Request(true, $username, $password, $signature, $returnUrl, $cancelUrl, $logoUrl);
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

//Get transaction details
$details = $transaction->getTransactionDetails($transactions[0]->getTxnId());
//Full Refund
$response = $transaction->refundTransaction($details->getTxnId(), 'Full');
if(isset($response['ACK']) && $response['ACK'] == "Success"){
    echo "Success on Full Refund transaction. REFUNDTRANSACTIONID: ".$response['REFUNDTRANSACTIONID']."<br/><br/>";
}

//Get transaction details
$details = $transaction->getTransactionDetails($transactions[1]->getTxnId());
//Partial Refund
$response = $transaction->refundTransaction($details->getTxnId(), 'Partial', 1, $details->getCurrencyCode(), "Partial Refund test", $details->getCustomer()->getPaypalId());
if(isset($response['ACK']) && $response['ACK'] == "Success"){
    echo "Success on Full Refund transaction. REFUNDTRANSACTIONID: ".$response['REFUNDTRANSACTIONID']."<br/><br/>";
}
```


