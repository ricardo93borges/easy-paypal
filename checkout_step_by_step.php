<?php
include 'vendor/autoload.php';
include "creed.php";


//App url
$appUrl = "http://localhost/easy-paypal/";
$returnUrl = $appUrl."checkout_step_by_step.php";
$cancelUrl = $appUrl."checkout_step_by_step.php";
$logoUrl = '';

//Create NvpRequest
$nvp = new \easyPaypal\NvpRequest($creed['username'], $creed['password'], $creed['signature'], true, 'expressCheckout', $returnUrl, $cancelUrl, $logoUrl);
//Create sellers
$seller = new \easyPaypal\Seller('SALE', null, 'BRL');
//Add itens to sellers
$item1 = new \easyPaypal\Item('Texugo', 'um texugo', 40.00, 1);
$item2 = new \easyPaypal\Item('Texugo 2', 'outro texugo', 40.00, 1);
$seller->addItem($item1);
$seller->addItem($item2);
//Set request
$nvp->setRequest($seller);

//If theres token do getExpressCheckoutDetails and doExpressCheckoutPayment
if (isset($_GET['token'])) {

    //getExpressCheckoutDetails
    $nvp->setToken($_GET['token']);
    $nvp->setMethod('getExpressCheckoutDetails');
    $response = $nvp->send();

    //doExpressCheckoutPayment
    if (isset($response['ACK']) && $response['ACK'] == 'Success') {
        $nvp->setPayerId($response['PAYERID']);
        $nvp->setMethod('doExpressCheckoutPayment');
        $response = $nvp->send();
        var_dump($response);
    }else{
        die('error on doExpressCheckoutPayment');
    }

}else {
    //setExpressCheckout
    $nvp->setMethod('setExpressCheckout');
    $response = $nvp->send();

    if (isset($response['ACK']) && $response['ACK'] == 'Success') {
        $url =  $nvp->isSandbox() ? $nvp->getPaypalSandboxUrl() : $nvp->getPaypalUrl();
        $nvp->setToken($response['TOKEN']);
        $query = array('cmd' => '_express-checkout', 'useraction' => 'commit', 'token' => $nvp->getToken());
        header('Location: ' . $url . '?' . http_build_query($query));
    } else {
        var_dump($response);
        die('error on setExpressCheckout');
    }
}