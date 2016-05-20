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
    private $token;
    private $payerId;
    private $autobillAmt;
    private $trialBillingPeriod;
    private $trialBillingFrequency;
    private $trialAmt;
    private $trialTotalBillingCycles;
    private $request;
    private $params;

    /**
     * Recurring constructor.
     * @param $amount
     * @param $description
     * @param string $method
     * @param string $billingPeriod
     * @param int $billingFrequency
     * @param int $maxFailedPayments
     * @param null $profileStartDate
     * @param int $autobillAmt
     */
    public function __construct($method='setExpressCheckout', $amount, $description, $billingPeriod='Month', $billingFrequency=1, $maxFailedPayments=3, $profileStartDate=null, $autobillAmt=0)
    {
        if(!$profileStartDate){
            $dt = new \DateTime();
            $dt->add(new \DateInterval('PT1H'));
            $profileStartDate = $dt->format("Y-m-d H:i:s");
        }
        if(!$this->validateBillingPeriod($billingPeriod)){
            throw new Easy_paypal_Exception('Billing period invalid, billing period must be Day, Week, Month or Year');
        }
        $this->billingPeriod = $billingPeriod;
        $this->billingFrequency = $billingFrequency;
        $this->maxFailedPayments = $maxFailedPayments;
        $this->profileStartDate = $profileStartDate;
        $this->description = $description;
        $this->amount = $amount;
        $this->method = $method;
        $this->autobillAmt = $autobillAmt;
        $this->trialAmt = null;
        $this->trialBillingFrequency = null;
        $this->trialBillingPeriod = null;
        $this->trialTotalBillingCycles = null;
    }

    public function validateBillingPeriod($billingPeriod){
        if(in_array($billingPeriod, array('Day', 'Week', 'Month', 'Year'))){
            return true;
        }
        return false;
    }
    /**
     * @return mixed
     */
    public function getTrialBillingPeriod()
    {
        return $this->trialBillingPeriod;
    }

    /**
     * @param mixed $trialBillingPeriod
     */
    public function setTrialBillingPeriod($trialBillingPeriod)
    {
        if(!$this->validateBillingPeriod($trialBillingPeriod)){
            throw new Easy_paypal_Exception('Trial billing period invalid, billing period must be Day, Week, Month or Year');
        }
        $this->trialBillingPeriod = $trialBillingPeriod;
    }

    /**
     * @return mixed
     */
    public function getTrialBillingFrequency()
    {
        return $this->trialBillingFrequency;
    }

    /**
     * @param mixed $trialBillingFrequency
     */
    public function setTrialBillingFrequency($trialBillingFrequency)
    {
        $this->trialBillingFrequency = $trialBillingFrequency;
    }

    /**
     * @return mixed
     */
    public function getTrialAmt()
    {
        return $this->trialAmt;
    }

    /**
     * @param mixed $trialAmt
     */
    public function setTrialAmt($trialAmt)
    {
        $this->trialAmt = $trialAmt;
    }

    /**
     * @return mixed
     */
    public function getTrialTotalBillingCycles()
    {
        return $this->trialTotalBillingCycles;
    }

    /**
     * @param mixed $trialTotalBillingCycles
     */
    public function setTrialTotalBillingCycles($trialTotalBillingCycles)
    {
        $this->trialTotalBillingCycles = $trialTotalBillingCycles;
    }

    /**
     * @return mixed
     */
    public function getAutobillAmt()
    {
        return $this->autobillAmt;
    }

    /**
     * @param mixed $autobillAmt
     */
    public function setAutobillAmt($autobillAmt)
    {
        $this->autobillAmt = $autobillAmt;
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
        if(!$this->validateBillingPeriod($billingPeriod)){
            throw new Easy_paypal_Exception('Billing period invalid, billing period must be Day, Week, Month or Year');
        }
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

    function exec(){
        $this->request->setParams($this->params);
        //die(print_r($this->request->getParams()));
        return $this->request->exec();
    }

    function createRecurringPaymentsProfile(){
        $this->params['METHOD'] = 'CreateRecurringPaymentsProfile';
        $this->params['BILLINGPERIOD'] = $this->getBillingPeriod();
        $this->params['BILLINGFREQUENCY'] = $this->getBillingFrequency();
        $this->params['MAXFAILEDPAYMENTS'] = $this->getMaxFailedPayments();
        $this->params['PROFILESTARTDATE'] = $this->getProfileStartDate();
        $this->params['DESC'] = $this->getDescription();
        $this->params['AMT'] = $this->getAmount();
        $this->params['CURRENCYCODE'] = $this->getCurrencyCode();
        $this->params['COUNTRYCODE'] = $this->getCountryCode();
        $this->params['AUTOBILLAMT'] = $this->getAutobillAmt();

        if($this->getTotalBillingCycles()) {
            $this->params['TOTALBILLINGCYCLES'] = $this->getTotalBillingCycles();
        }

        if($this->trialTotalBillingCycles && $this->trialAmt && $this->trialBillingPeriod && $this->trialBillingFrequency){
            $this->params['TRIALBILLINGPERIOD'] = $this->getTrialBillingPeriod();
            $this->params['TRIALBILLINGFREQUENCY'] = $this->getTrialBillingPeriod();
            $this->params['TRIALAMT'] = $this->getTrialBillingPeriod();
            $this->params['TRIALTOTALBILLINGCYCLES'] = $this->getTrialBillingPeriod();
        }

        //print "CreateRecurringPaymentsProfile";
        //die(print_r($this->params));
        $response = $this->exec();
        return $response;
    }

    function setExpressCheckout(){
        $this->params['METHOD'] = 'setExpressCheckout';
        $response = $this->exec();
        return $response;
    }

    function getExpressCheckoutDetails(){
        $this->params['METHOD'] = 'getExpressCheckoutDetails';
        $this->params['TOKEN'] = $this->getToken();
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
                //3-createRecurringPaymentsProfile
                $response = $this->createRecurringPaymentsProfile();
                return $response;
            } else{
                return array('error'=>$response);
            }
        }else {
            //1-setExpressCheckout
            $response = $this->setExpressCheckout();
            if (isset($response['ACK']) && $response['ACK'] == 'Success') {
                $url =  $this->request->isSandbox() ? $this->request->getPaypalSandboxUrl() : $this->request->getPaypalUrl();
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

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param Request $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param mixed $params
     */
    public function setParams($sellers)
    {
        if(!is_array($sellers)){
            $sellers = array($sellers);
        }
        /*
        if($this->getNotifyUrl()){
            $this->request['NOTIFYURL'] = $this->getNotifyUrl();
        }*/

        $countSeller = 0;
        $countItem = 0;
        foreach($sellers as $seller){
            $this->params['PAYMENTREQUEST_'.$countSeller.'_PAYMENTACTION'] = $seller->getPaymentAction();
            $this->params['PAYMENTREQUEST_'.$countSeller.'_AMT'] = $seller->getAmount();
            $this->params['PAYMENTREQUEST_'.$countSeller.'_CURRENCYCODE'] = $seller->getCurrencyCode();
            $this->params['PAYMENTREQUEST_'.$countSeller.'_ITEMAMT'] = $seller->getItemAmount();
            $this->params['PAYMENTREQUEST_'.$countSeller.'_INVNUM'] = $seller->getReference();

            foreach($seller->getItems() as $item){
                $this->params['L_PAYMENTREQUEST_'.$countSeller.'_NAME'.$countItem] = $item->getName();
                $this->params['L_PAYMENTREQUEST_'.$countSeller.'_DESC'.$countItem] = $item->getDescription();
                $this->params['L_PAYMENTREQUEST_'.$countSeller.'_AMT'.$countItem] = $item->getAmount();
                $this->params['L_PAYMENTREQUEST_'.$countSeller.'_QTY'.$countItem] = $item->getQuantity();
                $this->params['L_BILLINGTYPE'.$countItem] = 'RecurringPayments';
                $this->params['L_BILLINGAGREEMENTDESCRIPTION'.$countItem] = $this->getDescription();
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