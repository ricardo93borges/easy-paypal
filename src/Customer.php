<?php

namespace easyPaypal;

class Customer{
    private $id;
    private $addressCountry;
    private $addressCity;
    private $addressCountryCode;
    private $addressName;
    private $addressState;
    private $addressStatus;
    private $addressStreet;
    private $addressZip;
    private $contactPhone;
    private $firstName;
    private $lastName;
    private $businessName;
    private $email;
    private $paypalId;

    /**
     * Customer constructor.
     */
    public function __construct(){

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
    public function getAddressCountry()
    {
        return $this->addressCountry;
    }

    /**
     * @param mixed $addressCountry
     */
    public function setAddressCountry($addressCountry)
    {
        $this->addressCountry = $addressCountry;
    }

    /**
     * @return mixed
     */
    public function getAddressCity()
    {
        return $this->addressCity;
    }

    /**
     * @param mixed $addressCity
     */
    public function setAddressCity($addressCity)
    {
        $this->addressCity = $addressCity;
    }

    /**
     * @return mixed
     */
    public function getAddressCountryCode()
    {
        return $this->addressCountryCode;
    }

    /**
     * @param mixed $addressCountryCode
     */
    public function setAddressCountryCode($addressCountryCode)
    {
        $this->addressCountryCode = $addressCountryCode;
    }

    /**
     * @return mixed
     */
    public function getAddressName()
    {
        return $this->addressName;
    }

    /**
     * @param mixed $addressName
     */
    public function setAddressName($addressName)
    {
        $this->addressName = $addressName;
    }

    /**
     * @return mixed
     */
    public function getAddressState()
    {
        return $this->addressState;
    }

    /**
     * @param mixed $addressState
     */
    public function setAddressState($addressState)
    {
        $this->addressState = $addressState;
    }

    /**
     * @return mixed
     */
    public function getAddressStatus()
    {
        return $this->addressStatus;
    }

    /**
     * @param mixed $addressStatus
     */
    public function setAddressStatus($addressStatus)
    {
        $this->addressStatus = $addressStatus;
    }

    /**
     * @return mixed
     */
    public function getAddressStreet()
    {
        return $this->addressStreet;
    }

    /**
     * @param mixed $addressStreet
     */
    public function setAddressStreet($addressStreet)
    {
        $this->addressStreet = $addressStreet;
    }

    /**
     * @return mixed
     */
    public function getAddressZip()
    {
        return $this->addressZip;
    }

    /**
     * @param mixed $addressZip
     */
    public function setAddressZip($addressZip)
    {
        $this->addressZip = $addressZip;
    }

    /**
     * @return mixed
     */
    public function getContactPhone()
    {
        return $this->contactPhone;
    }

    /**
     * @param mixed $contactPhone
     */
    public function setContactPhone($contactPhone)
    {
        $this->contactPhone = $contactPhone;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getBusinessName()
    {
        return $this->businessName;
    }

    /**
     * @param mixed $businessName
     */
    public function setBusinessName($businessName)
    {
        $this->businessName = $businessName;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPaypalId()
    {
        return $this->paypalId;
    }

    /**
     * @param mixed $paypalId
     */
    public function setPaypalId($paypalId)
    {
        $this->paypalId = $paypalId;
    }

}