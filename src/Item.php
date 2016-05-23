<?php
namespace easyPaypal;

class Item
{
    private $name;
    private $description;
    private $amount;
    private $quantity;
    private $billingType;
    private $billingAgreementDescription;
    private $category;

    /**
     * Item constructor.
     * @param $name
     * @param $description
     * @param double $amount
     * @param double $quantity
     */
    public function __construct($name, $description, $amount, $quantity, $billingType=null, $billingAgreementDescription=null)
    {
        $this->name = $name;
        $this->description = $description;
        $this->amount = $amount;
        $this->quantity = $quantity;
        $this->billingType = $billingType;
        $this->billingAgreementDescription = $billingAgreementDescription;
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

    /**
     * @return mixed
     */
    public function getBillingType()
    {
        return $this->billingType;
    }

    /**
     * @param mixed $billingType
     */
    public function setBillingType($billingType)
    {
        $this->billingType = $billingType;
    }

    /**
     * @return mixed
     */
    public function getBillingAgreementDescription()
    {
        return $this->billingAgreementDescription;
    }

    /**
     * @param mixed $billingAgreementDescription
     */
    public function setBillingAgreementDescription($billingAgreementDescription)
    {
        $this->billingAgreementDescription = $billingAgreementDescription;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     * Allowed only Physical or Digital
     */
    public function setCategory($category)
    {
        if(!in_array($category, array('Digital', 'Physical'))){
            throw new Easy_paypal_Exception('Item category must be Digital or Physical');
        }
        $this->category = $category;
    }

}