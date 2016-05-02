<?php

/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 02/05/16
 * Time: 17:29
 */
class Item
{
    /*
        'L_PAYMENTREQUEST_0_NAME0'          => 'Teste Daslu',
        'L_PAYMENTREQUEST_0_DESC0'          => 'Teste Daslu',
        'L_PAYMENTREQUEST_0_AMT0'          => '50.00',
        'L_PAYMENTREQUEST_0_QTY1' => '1',
        'L_PAYMENTREQUEST_0_ITEMAMT' => '11.00',
     */
    private $name;
    private $description;
    private $amount;
    private $quantity;
    private $itemamt;

    /**
     * Item constructor.
     * @param $name
     * @param $description
     * @param $amount
     * @param $quantity
     */
    public function __construct($name, $description, $amount, $quantity)
    {
        $this->name = $name;
        $this->description = $description;
        $this->amount = $amount;
        $this->quantity = $quantity;
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