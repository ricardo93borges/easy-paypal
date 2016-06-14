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
//Create sellers
$seller = new \easyPaypal\Seller();
//Add itens to sellers
$item1 = new \easyPaypal\Item('Texugo', 'um texugo', 40.00, 1, 'RecurringPayments', 'Recurring payment item');
$item2 = new \easyPaypal\Item('Texugo 2', 'outro texugo', 40.00, 1, 'RecurringPayments', 'Recurring payment item');
$seller->addItem($item1);
$seller->addItem($item2);
//Set request
$nvp->setParams($seller);

//If theres token do getExpressCheckoutDetails and doExpressCheckoutPayment
if (isset($_GET['token'])) {

    //getExpressCheckoutDetails
    $nvp->setToken($_GET['token']);
    $nvp->setMethod('getExpressCheckoutDetails');
    $response = $nvp->send();

    //doExpressCheckoutPayment
    if (isset($response['ACK']) && $response['ACK'] == 'Success') {
        $nvp->setPayerId($response['PAYERID']);
        $nvp->setMethod('CreateRecurringPaymentsProfile');
        $response2 = $nvp->send();
        var_dump($response2);
    }else{
        die('error on doExpressCheckoutPayment');
    }

}else {
    //setExpressCheckout
    $nvp->setMethod('setExpressCheckout');
    $response = $nvp->send();

    if (isset($response['ACK']) && $response['ACK'] == 'Success') {
        $url =  $request->isSandbox() ? $request->getPaypalSandboxUrl() : $request->getPaypalUrl();
        $nvp->setToken($response['TOKEN']);
        $query = array('cmd' => '_express-checkout', 'useraction' => 'commit', 'token' => $nvp->getToken());
        header('Location: ' . $url . '?' . http_build_query($query));
    } else {
        var_dump($response);
        die('error on setExpressCheckout');
    }
}