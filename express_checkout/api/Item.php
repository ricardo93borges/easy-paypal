<?php
namespace paypal\express_checkout\api;

class Item
{
    private $name;
    private $description;
    private $amount;
    private $quantity;

    /**
     * Item constructor.
     * @param $name
     * @param $description
     * @param double $amount
     * @param double $quantity
     */
    public function __construct($name, $description, $amount, $quantity)
    {
        $this->name = $name;
        $this->description = $description;
        $this->amount = $amount;
        $this->quantity = $quantity;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

}