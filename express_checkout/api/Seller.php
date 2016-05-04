<?php
namespace paypal\express_checkout\api;

class Seller
{
    /*
        'PAYMENTREQUEST_0_PAYMENTACTION'	=> 'Sale',
        'PAYMENTREQUEST_0_AMT'              => '40.00',
        'PAYMENTREQUEST_0_CURRENCYCODE'     => 'BRL',
        'PAYMENTREQUEST_0_ITEMAMT'          => '40.00',
     */
    private $paymentAction;
    private $amount;
    private $currencyCode;
    private $itemAmount;
    private $items;
    private $reference;

    /**
     * Seller constructor.
     * @param $paymentAction
     * @param $currencyCode
     */
    public function __construct($paymentAction, $reference, $currencyCode='BRL'){
        $this->paymentAction = $paymentAction;
        $this->reference = $reference;
        $this->currencyCode = $currencyCode;
        $this->items = array();
    }

    /**
     * @return mixed
     */
    public function getPaymentAction()
    {
        return $this->paymentAction;
    }

    /**
     * @param mixed $paymentAction
     */
    public function setPaymentAction($paymentAction)
    {
        $this->paymentAction = $paymentAction;
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
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * @param mixed $currencyCode
     */
    public function setCurrencyCode($currencyCode)
    {
        $this->currencyCode = $currencyCode;
    }

    /**
     * @return mixed
     */
    public function getItemAmount()
    {
        return $this->itemAmount;
    }

    /**
     * @param mixed $itemAmount
     */
    public function setItemAmount($itemAmount)
    {
        $this->itemAmount = $itemAmount;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param array $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * @return mixed
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param mixed $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    /**
     * @param $item
     */
    function addItem($item){
        $this->amount += $item->getAmount();
        $this->itemAmount += $item->getAmount();
        $this->items[] = $item;
    }

}