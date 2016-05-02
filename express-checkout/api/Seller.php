<?php

/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 02/05/16
 * Time: 17:29
 */
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

    /**
     * Seller constructor.
     * @param $paymentAction
     * @param $currencyCode
     */
    public function __construct($paymentAction, $currencyCode)
    {
        $this->paymentAction = $paymentAction;
        $this->currencyCode = $currencyCode;
        $this->items = array();
    }

    function __get($name)
    {
        return $this->$name;
    }

    function __set($name, $value)
    {
        $this->$name = $value;
    }


}