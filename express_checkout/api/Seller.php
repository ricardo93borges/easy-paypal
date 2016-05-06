<?php
namespace paypal\express_checkout\api;

class Seller
{
    private $paymentAction;
    private $amount;
    private $currencyCode;
    private $itemAmount;
    private $items;
    private $reference;

    /**
     * Seller constructor.
     * @param $paymentAction
     * @param null $reference
     * @param string $currencyCode
     */
    public function __construct($paymentAction, $reference=null, $currencyCode='BRL'){
        $this->paymentAction = $paymentAction;
        $this->reference = $reference;
        $this->currencyCode = $currencyCode;
        $this->items = array();

        if(is_null($reference)){
            $this->reference = md5(uniqid(rand(), true));
        }
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