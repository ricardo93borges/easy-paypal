<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 18/05/16
 * Time: 14:27
 */

namespace easyPaypal;

class Recurring extends Request
{
    private $billingPeriod;
    private $billingFrequency;
    private $totalBillingCycles;
    private $maxFailedPayments;
    private $profileStartDate;
    private $description;
    private $amount;
    private $method;
    private $headerImage;
    private $token;
    private $payerId;

    /**
     * Recurring constructor.
     * @param $billingPeriod
     * @param $billingFrequency
     * @param $totalBillingCycles
     * @param $maxFailedPayments
     * @param $profileStartDate
     * @param $description
     * @param $amount
     */
    public function __construct($user, $password, $signature, $sandbox, $returnUrl, $cancelUrl, $amount, $description, $method='setExpressCheckout', $headerImage='', $buttonSource='BR_EC_EMPRESA', $localecode='pt_BR', $version='73.0', $currencyCode='BRL', $countryCode='BR', $billingPeriod='Month', $billingFrequency=1, $maxFailedPayments=3, $profileStartDate=null)
    {
        parent::__construct($sandbox, $user, $password, $signature, $localecode, $returnUrl, $cancelUrl, $buttonSource, $version, $currencyCode, $countryCode);
        if(!$profileStartDate){
            //$profileStartDate = gmdate("Y-m-d\TH:i:s\Z");
            $dt = new \DateTime();
            $dt->add(new \DateInterval('PT1H'));
            $profileStartDate = $dt->format("Y-m-d H:i:s");
        }
        $this->billingPeriod = $billingPeriod;
        $this->billingFrequency = $billingFrequency;
        $this->maxFailedPayments = $maxFailedPayments;
        $this->profileStartDate = $profileStartDate;
        $this->description = $description;
        $this->amount = $amount;
        $this->method = $method;
        $this->headerImage = $headerImage;
    }

    /**
     * @return mixed
     */
    public function getPayerId()
    {
        return $this->payerId;
    }

    /**
     * @param mixed $payerId
     */
    public function setPayerId($payerId)
    {
        $this->payerId = $payerId;
    }

    /**
     * @return mixed
     */
    public function getBillingPeriod()
    {
        return $this->billingPeriod;
    }

    /**
     * @param mixed $billingPeriod
     */
    public function setBillingPeriod($billingPeriod)
    {
        $this->billingPeriod = $billingPeriod;
    }

    /**
     * @return mixed
     */
    public function getBillingFrequency()
    {
        return $this->billingFrequency;
    }

    /**
     * @param mixed $billingFrequency
     */
    public function setBillingFrequency($billingFrequency)
    {
        $this->billingFrequency = $billingFrequency;
    }

    /**
     * @return mixed
     */
    public function getTotalBillingCycles()
    {
        return $this->totalBillingCycles;
    }

    /**
     * @param mixed $totalBillingCycles
     */
    public function setTotalBillingCycles($totalBillingCycles)
    {
        $this->totalBillingCycles = $totalBillingCycles;
    }

    /**
     * @return mixed
     */
    public function getMaxFailedPayments()
    {
        return $this->maxFailedPayments;
    }

    /**
     * @param mixed $maxFailedPayments
     */
    public function setMaxFailedPayments($maxFailedPayments)
    {
        $this->maxFailedPayments = $maxFailedPayments;
    }

    /**
     * @return mixed
     */
    public function getProfileStartDate()
    {
        return $this->profileStartDate;
    }

    /**
     * @param mixed $profileStartDate
     */
    public function setProfileStartDate($profileStartDate)
    {
        $this->profileStartDate = $profileStartDate;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param mixed $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getHeaderImage()
    {
        return $this->headerImage;
    }

    /**
     * @param string $headerImage
     */
    public function setHeaderImage($headerImage)
    {
        $this->headerImage = $headerImage;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    function CreateRecurringPaymentsProfile(){
        $this->request['METHOD'] = 'CreateRecurringPaymentsProfile';
        $this->request['BILLINGPERIOD'] = $this->getBillingPeriod();
        $this->request['BILLINGFREQUENCY'] = $this->getBillingFrequency();
        $this->request['MAXFAILEDPAYMENTS'] = $this->getMaxFailedPayments();
        $this->request['PROFILESTARTDATE'] = $this->getProfileStartDate();
        $this->request['DESC'] = $this->getDescription();
        $this->request['AMT'] = $this->getAmount();
        $this->request['CURRENCYCODE'] = $this->getCurrencyCode();
        $this->request['COUNTRYCODE'] = $this->getCountryCode();

        if($this->getTotalBillingCycles()) {
            $this->request['TOTALBILLINGCYCLES'] = $this->getTotalBillingCycles();
        }

        //print "CreateRecurringPaymentsProfile";
        //die(print_r($this->request));
        $response = $this->exec();
        return $response;
    }

    function setExpressCheckout(){
        $this->request['METHOD'] = 'setExpressCheckout';
        $response = $this->exec();
        return $response;
    }

    function getExpressCheckoutDetails(){
        $this->request['METHOD'] = 'getExpressCheckoutDetails';
        $this->request['TOKEN'] = $this->getToken();
        $response = $this->exec();
        return $response;
    }

    function doExpressCheckoutPayment(){
        $this->request['METHOD'] = 'doExpressCheckoutPayment';
        $this->request['TOKEN'] = $this->getToken();
        $this->request['PAYERID'] = $this->getPayerId();
        $response = $this->exec();
        return $response;
    }

    function expressCheckout(){
        if(isset($_GET['token'])){
            //2-getExpressCheckoutDetails
            $this->setToken($_GET['token']);
            $response = $this->getExpressCheckoutDetails();

            if (isset($response['TOKEN']) && $response['ACK'] == 'Success') {
                $this->payerId = $response['PAYERID'];
                //3-doExpressCheckoutPayment
                $response = $this->doExpressCheckoutPayment();
                return $response;
            } else{
                return array('error'=>$response);
            }
        }else {
            //1-setExpressCheckout
            $response = $this->setExpressCheckout();
            if (isset($response['ACK']) && $response['ACK'] == 'Success') {
                $url =  $this->isSandbox() ? $this->getPaypalSandboxUrl() : $this->getPaypalUrl();
                $this->setToken($response['TOKEN']);
                $query = array('cmd' => '_express-checkout', 'useraction' => 'commit', 'token' => $this->getToken());
                //header('Location: ' . $url . '?' . http_build_query($query));
                $redirectURL = sprintf('%s?%s', $url, http_build_query($query));
                //carrega a página de redirecionamento
                require 'redirect.php';
            } else{
                return array('error'=>$response);
            }
        }
    }

    function getRequest(){
        return $this->request;
    }
    /**
     * @param Seller array $sellers
     */
    function setRequest($sellers){
        if(!is_array($sellers)){
            $sellers = array($sellers);
        }

        $this->request = array(
            'HDRIMG' =>$this->getHeaderImage(),
            'LOCALECODE' => $this->getLocalecode(),
            'USER' => $this->getUser(),
            'PWD' => $this->getPassword(),
            'SIGNATURE' => $this->getSignature(),
            'VERSION' => $this->getVersion(),
            'METHOD'=> $this->getMethod(),
            'RETURNURL' => $this->getReturnUrl(),
            'CANCELURL' => $this->getCancelUrl(),
            'BUTTONSOURCE' => $this->getButtonSource()
        );

        /*
        if($this->getNotifyUrl()){
            $this->request['NOTIFYURL'] = $this->getNotifyUrl();
        }*/

        $countSeller = 0;
        $countItem = 0;
        foreach($sellers as $seller){
            $this->request['PAYMENTREQUEST_'.$countSeller.'_PAYMENTACTION'] = $seller->getPaymentAction();
            $this->request['PAYMENTREQUEST_'.$countSeller.'_AMT'] = $seller->getAmount();
            $this->request['PAYMENTREQUEST_'.$countSeller.'_CURRENCYCODE'] = $seller->getCurrencyCode();
            $this->request['PAYMENTREQUEST_'.$countSeller.'_ITEMAMT'] = $seller->getItemAmount();
            $this->request['PAYMENTREQUEST_'.$countSeller.'_INVNUM'] = $seller->getReference();

            foreach($seller->getItems() as $item){
                $this->request['L_PAYMENTREQUEST_'.$countSeller.'_NAME'.$countItem] = $item->getName();
                $this->request['L_PAYMENTREQUEST_'.$countSeller.'_DESC'.$countItem] = $item->getDescription();
                $this->request['L_PAYMENTREQUEST_'.$countSeller.'_AMT'.$countItem] = $item->getAmount();
                $this->request['L_PAYMENTREQUEST_'.$countSeller.'_QTY'.$countItem] = $item->getQuantity();
                $this->request['L_BILLINGTYPE'.$countItem] = 'RecurringPayments';
                $this->request['L_BILLINGAGREEMENTDESCRIPTION'.$countItem] = $this->getDescription();
                $countItem++;
            }
            $countSeller++;
        }
    }

    public function send(){
        $response = null;
        switch($this->getMethod()){
            case 'setExpressCheckout':
                $response = $this->setExpressCheckout();
                break;
            case 'getExpressCheckoutDetails':
                $response = $this->getExpressCheckoutDetails();
                break;
            case 'doExpressCheckoutPayment':
                $response = $this->doExpressCheckoutPayment();
                break;
            case 'expressCheckout':
                $response = $this->expressCheckout();
                break;
            case 'CreateRecurringPaymentsProfile':
                $response = $this->createRecurringPaymentsProfile();
        }
        return $response;
    }

}