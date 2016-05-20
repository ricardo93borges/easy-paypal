<?php
include __DIR__."/../../vendor/autoload.php";
include 'db.php';

$receiverEmail = 'ricardo_borges26-facilitator_api1.hotmail.com';
$sandbox = true;

$ipn = new \easyPaypal\Ipn($receiverEmail, $sandbox);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    ipnLog("init");
    $response = $ipn->handleIpn($_POST);

    if(empty($response)){
        ipnLog("empty response");
        die();
    }

    if (isset($response['error'])) {
        ipnLog("error");
        ipnLog(json_encode($response));
        die();
    }

    $notification = $response['notification'];
    $customer = $response['customer'];
    $trasaction = $response['transaction'];

    //ipnLog("....");
    //ipnLog(json_encode($notification));

    addNotification($notification);
    addCustomer($customer);
    addTransaction($trasaction);
}

/**
 * @param String $data
 */
function ipnLog($data){
    //Check log.log file permissions
    $date = date('Y-m-d H:i');
    file_put_contents("log.log", "[".$date."]".$data." \n", FILE_APPEND);
}

function addNotification($notification){
    $params = array(
        'id' => null,
        'txn_id' => $notification->getTxnId(),
        'txn_type' => $notification->getTxnType(),
        'receiver_email' => $notification->getReceiverEmail(),
        'payment_status' => $notification->getPaymentStatus(),
        'pending_reason' => $notification->getPendingReason(),
        'reason_code' => $notification->getReasonCode(),
        'custom' => $notification->getCustom(),
        'invoice' => $notification->getInvoice()
    );
    insert('notification', $params);
}

function addCustomer($customer){
    $params = array(
        'id' => null,
        'address_country' => $customer->getAddressCountry(),
        'address_city' => $customer->getAddressCity(),
        'address_country_code' => $customer->getAddressCountryCode(),
        'address_name' => $customer->getAddressName(),
        'address_state' => $customer->getAddressState(),
        'address_status' => $customer->getAddressStatus(),
        'address_street' => $customer->getAddressStreet(),
        'address_zip' => $customer->getAddressZip(),
        'contact_phone' => $customer->getContactPhone(),
        'first_name' => $customer->getFirstName(),
        'last_name' => $customer->getLastName(),
        'business_name' => $customer->getBusinessName(),
        'email' => $customer->getEmail(),
        'paypal_id' => $customer->getPaypalId()
    );
    insert('customer', $params);
}

function addTransaction($transaction){
    $params = array(
        'id' => null,
	    'invoice' => $transaction->getInvoice(),
	    'custom' => $transaction->getCustom(),
	    'txn_type' => $transaction->getTxnType(),
	    'txn_id' => $transaction->getTxnId(),
	    'payer_id' => $transaction->getPayerId(),
	    'currency' => $transaction->getCurrency(),
	    'gross' => $transaction->getGross(),
	    'fee' => $transaction->getFee(),
	    'handling' => $transaction->getHandling(),
	    'shipping' => $transaction->getShipping(),
	    'tax' => $transaction->getTax(),
	    'payment_status' => $transaction->getPaymentStatus(),
	    'pending_reason' => $transaction->getPendingReason(),
	    'reason_code' => $transaction->getReasonCode(),
    );
    insert('transaction', $params);
}