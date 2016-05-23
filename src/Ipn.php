<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 11/05/16
 * Time: 16:39
 */

namespace easyPaypal;

/**
 * Class Ipn
 * @package easyPaypal\ipn
 */
class Ipn{
    private $sandbox;
    private $receiverEmail;
    private $endpoint;

    /**
     * Ipn constructor.
     * @param $sandbox
     * @param $receiverEmail
     */
    public function __construct($receiverEmail, $sandbox=false)
    {
        $this->sandbox = $sandbox;
        $this->receiverEmail = $receiverEmail;
        $this->endpoint = $sandbox ? 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_notify-validate' : 'https://www.paypal.com/cgi-bin/webscr?cmd=_notify-validate';
    }

    /**
     * Verifica se uma notificação IPN é válida, fazendo a autenticação
     * da mensagem segundo o protocolo de segurança do serviço.
     *
     * @param array $message Um array contendo a notificação recebida.
     * @return boolean TRUE se a notificação for autência, ou FALSE se não for.
     *
     */
    function handleIpn(array $message){
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $this->endpoint);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_SSLVERSION, 6);
        //curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
        //curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($message));

        $response = curl_exec($curl);
        $error = curl_error($curl);
        $errno = curl_errno($curl);

        curl_close($curl);

        if(!empty($error) || $errno > 0 || $response != 'VERIFIED'){
            return array('error'=>'Not verified', 'errors'=>$error);
        }

        if ($_POST['receiver_email'] != $this->getReceiverEmail()) {
            return array('error'=>'Receiver mail is different', 'errors'=>array('expected'=>$this->receiverEmail, 'returned'=>$_POST['receiver_email']));
        }

        $arr = array_merge(array(
	        'txn_id' => null,
	        'txn_type' => null,
	        'payment_status' => null,
	        'pending_reason' => null,
	        'reason_code' => null,
	        'custom' => null,
	        'invoice' => null,
            'address_country' => null,
	        'address_city' => null,
	        'address_country_code' => null,
	        'address_name' => null,
	        'address_state' => null,
	        'address_status' => null,
	        'address_street' => null,
	        'address_zip' => null,
	        'contact_phone' => null,
	        'first_name' => null,
	        'last_name' => null,
	        'business_name' => null,
	        'payer_email' => null,
	        'payer_id' => null,
	        'mc_currency' => null,
	        'mc_gross' => null,
	        'mc_fee' => null,
	        'mc_handling' => null,
	        'mc_shipping' => null,
	        'tax' => null,
	    ), $message);

        //$this->ipnLog("....");
        //$this->ipnLog(json_encode($arr));

        $notification = new Notification();
        $notification->setTxnId($arr['txn_id']);
        $notification->setTxnType($arr['txn_type']);
        $notification->setReceiverEmail($arr['receiver_email']);
        $notification->setPaymentStatus($arr['payment_status']);
        $notification->setPendingReason($arr['pending_reason']);
        $notification->setReasonCode($arr['reason_code']);
        $notification->setCustom($arr['custom']);
        $notification->setInvoice($arr['invoice']);

        $customer = new Customer();
        $customer->setAddressCountry($arr['address_country']);
        $customer->setAddressCity($arr['address_city']);
        $customer->setAddressCountryCode($arr['address_country_code']);
        $customer->setAddressName($arr['address_name']);
        $customer->setAddressState($arr['address_state']);
        $customer->setAddressStatus($arr['address_status']);
        $customer->setAddressStreet($arr['address_street']);
        $customer->setAddressZip($arr['address_zip']);
        $customer->setContactPhone($arr['contact_phone']);
        $customer->setFirstName($arr['first_name']);
        $customer->setLastName($arr['last_name']);
        $customer->setBusinessName($arr['business_name']);
        $customer->setEmail($arr['payer_email']);
        $customer->setPaypalId($arr['payer_id']);

        $transaction = new Transaction();
        $transaction->setId(null);
        $transaction->setTxnId($arr['txn_id']);
        $transaction->setTxnType($arr['txn_type']);
        $transaction->setPaymentStatus($arr['payment_status']);
        $transaction->setPendingReason($arr['pending_reason']);
        $transaction->setReasonCode($arr['reason_code']);
        $transaction->setCustom($arr['custom']);
        $transaction->setInvoice($arr['invoice']);
        $transaction->setPayerId($arr['payer_id']);
        $transaction->setCurrency($arr['mc_currency']);
        $transaction->setGross($arr['mc_gross']);
        $transaction->setFee($arr['mc_fee']);
        $transaction->setHandling($arr['mc_handling']);
        $transaction->setShipping($arr['mc_shipping']);
        $transaction->setTax($arr['tax']);
        
        return array('notification'=>$notification, 'customer'=>$customer, 'transaction'=>$transaction);
    }

    /**
     * @return boolean
     */
    public function isSandbox()
    {
        return $this->sandbox;
    }

    /**
     * @param boolean $sandbox
     */
    public function setSandbox($sandbox)
    {
        $this->sandbox = $sandbox;
    }

    /**
     * @return mixed
     */
    public function getReceiverEmail()
    {
        return $this->receiverEmail;
    }

    /**
     * @param mixed $receiverEmail
     */
    public function setReceiverEmail($receiverEmail)
    {
        $this->receiverEmail = $receiverEmail;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @param string $endpoint
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
    }
}