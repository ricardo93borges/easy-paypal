<?php

namespace easyPaypal;

class Transaction extends Request
{
    private $id;
    private $txnId;
    private $txnType;
    private $paymentStatus;
    private $pendingReason;
    private $reasonCode;
    private $custom;
    private $invoice;
    private $payerId;
    private $currency;
    private $gross;
    private $fee;
    private $handling;
    private $shipping;
    private $tax;
    private $request;
    private $params;
    private $startDate;
    private $endDate;
    private $paymentDate;
    private $type;
    private $customer;

    /**
     * Transaction constructor.
     */
    public function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTxnId()
    {
        return $this->txnId;
    }

    /**
     * @param mixed $txnId
     */
    public function setTxnId($txnId)
    {
        $this->txnId = $txnId;
    }

    /**
     * @return mixed
     */
    public function getTxnType()
    {
        return $this->txnType;
    }

    /**
     * @param mixed $txnType
     */
    public function setTxnType($txnType)
    {
        $this->txnType = $txnType;
    }

    /**
     * @return mixed
     */
    public function getPaymentStatus()
    {
        return $this->paymentStatus;
    }

    /**
     * @param mixed $paymentStatus
     */
    public function setPaymentStatus($paymentStatus)
    {
        $this->paymentStatus = $paymentStatus;
    }

    /**
     * @return mixed
     */
    public function getPendingReason()
    {
        return $this->pendingReason;
    }

    /**
     * @param mixed $pendingReason
     */
    public function setPendingReason($pendingReason)
    {
        $this->pendingReason = $pendingReason;
    }

    /**
     * @return mixed
     */
    public function getReasonCode()
    {
        return $this->reasonCode;
    }

    /**
     * @param mixed $reasonCode
     */
    public function setReasonCode($reasonCode)
    {
        $this->reasonCode = $reasonCode;
    }

    /**
     * @return mixed
     */
    public function getCustom()
    {
        return $this->custom;
    }

    /**
     * @param mixed $custom
     */
    public function setCustom($custom)
    {
        $this->custom = $custom;
    }

    /**
     * @return mixed
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * @param mixed $invoice
     */
    public function setInvoice($invoice)
    {
        $this->invoice = $invoice;
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
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return mixed
     */
    public function getGross()
    {
        return $this->gross;
    }

    /**
     * @param mixed $gross
     */
    public function setGross($gross)
    {
        $this->gross = $gross;
    }

    /**
     * @return mixed
     */
    public function getFee()
    {
        return $this->fee;
    }

    /**
     * @param mixed $fee
     */
    public function setFee($fee)
    {
        $this->fee = $fee;
    }

    /**
     * @return mixed
     */
    public function getHandling()
    {
        return $this->handling;
    }

    /**
     * @param mixed $handling
     */
    public function setHandling($handling)
    {
        $this->handling = $handling;
    }

    /**
     * @return mixed
     */
    public function getShipping()
    {
        return $this->shipping;
    }

    /**
     * @param mixed $shipping
     */
    public function setShipping($shipping)
    {
        $this->shipping = $shipping;
    }

    /**
     * @return mixed
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * @param mixed $tax
     */
    public function setTax($tax)
    {
        $this->tax = $tax;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param DateTime $startDate
     */
    public function setStartDate($startDate)
    {
        if(!$startDate instanceof \DateTime){
            throw new Easy_paypal_Exception('Start date must be a DateTime object');
        }
        $this->startDate = $startDate->format("Y-m-d H:i:s");
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param DateTime $endDate
     */
    public function setEndDate($endDate)
    {
        if(!$endDate instanceof \DateTime){
            throw new Easy_paypal_Exception('Start date must be a DateTime object');
        }
        $this->endDate = $endDate->format("Y-m-d H:i:s");
    }

    function exec(){
        $this->request->setParams($this->params);
        return $this->request->exec();
    }

    function transactionSearch(){
        $this->params['METHOD'] = 'TransactionSearch';
        $this->params['STARTDATE'] = $this->getStartDate();
        $this->params['ENDDATE'] = $this->getEndDate();
        $response = $this->exec();
        if(count($response) == 0){
            throw new Easy_paypal_Exception('No transaction found.');
        }else{
            return $this->sanitizeTransactions($response);
        }
    }

    function sanitizeTransactions($response)
    {
        $arrayTransactions = array();
        $transactions = array();

        foreach($response as $k=>$v){
            $param = $this->extractString($k);
            $number = $this->extractNumbers($k);
            if(is_null($param) || is_null($number)){
                continue;
            }
            $arrayTransactions[$number][$param] = $v;
        }

        for($i=0;$i<count($arrayTransactions);$i++) {
            $timestamp = isset($arrayTransactions[$i]['L_TIMESTAMP']) ? $arrayTransactions[$i]['L_TIMESTAMP'] : null;
            $transactionId = isset($arrayTransactions[$i]['L_TRANSACTIONID']) ? $arrayTransactions[$i]['L_TRANSACTIONID'] : null;
            $status = isset($arrayTransactions[$i]['L_STATUS']) ? $arrayTransactions[$i]['L_STATUS'] : null;
            $type = isset($arrayTransactions[$i]['L_TYPE']) ? $arrayTransactions[$i]['L_TYPE'] : null;
            $email = isset($arrayTransactions[$i]['L_EMAIL']) ? $arrayTransactions[$i]['L_EMAIL'] : null;
            $name = isset($arrayTransactions[$i]['L_NAME']) ? $arrayTransactions[$i]['L_NAME'] : null;
            $amt = isset($arrayTransactions[$i]['L_AMT']) ? $arrayTransactions[$i]['L_AMT'] : null;
            $currencyCode = isset($arrayTransactions[$i]['L_CURRENCYCODE']) ? $arrayTransactions[$i]['L_CURRENCYCODE'] : null;
            $feeAmt = isset($arrayTransactions[$i]['L_FEEAMT']) ? $arrayTransactions[$i]['L_FEEAMT'] : null;

            $t = new Transaction();
            $c = new Customer();

            $nameExploded = explode(' ', $name);

            $c->setFirstName($nameExploded[0]);
            $c->setLastName($nameExploded[1]);
            $c->setEmail($email);

            $t->setPaymentDate($timestamp);
            $t->setTxnId($transactionId);
            $t->setPaymentStatus($status);
            $t->setType($type);
            $t->setGross($amt);
            $t->setCurrencyCode($currencyCode);
            $t->setFee($feeAmt);
            $t->setCustomer($c);

            $transactions[] = $t;
        }

        return $transactions;
    }

    function extractNumbers($str){
        preg_match_all('!\d+!', $str, $matches);
        if(isset($matches[0][0])){
            return $matches[0][0];
        }
        return null;
    }

    function extractString($str){
        preg_match_all('!\D+!', $str, $matches);
        if(isset($matches[0][0])){
            return $matches[0][0];
        }
        return null;
    }

    /**
     * @return Request object
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
     * @param array $params
     */
    public function setParams($params)
    {

    }

    /**
     * @return mixed
     */
    public function getPaymentDate()
    {
        return $this->paymentDate;
    }

    /**
     * @param mixed $paymentDate
     */
    public function setPaymentDate($paymentDate)
    {
        $this->paymentDate = $paymentDate;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return Customer object
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
    }


}