<?php
namespace paypal\express_checkout;
require_once('api/autoload.php');

//Credentials
$creed = array(
    'username' => '',
    'password' => '',
    'signature' => ''
);
//App url
$appUrl = "http://localhost/paypal/express_checkout/";

//Create NvpRequest

$nvp = new api\NvpRequest($creed['username'], $creed['password'], $creed['signature'], true, 'setExpressCheckout', $appUrl.'checkout_step_by_step.php', $appUrl.'checkout_step_by_step.php');
//Create sellers
$seller = new api\Seller('SALE', 'BRL');
//Add itens to sellers
$item1 = new api\Item('Texugo', 'um texugo', 40.00, 1);
$item2 = new api\Item('Texugo 2', 'outro texugo', 40.00, 1);
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
        die('error on setExpressCheckout');
    }
}